#!/usr/bin/perl

use Asterisk::AGI;
use LWP::UserAgent;
use HTTP::Request;
use MIME::Base64;
use DBI;

my $dbhost = "localhost";
my $dbname = "apis";
my $dbuser = "root";
my $dbpass = "astersql";

my $agi = new Asterisk::AGI;
my %input = $agi->ReadParse();

# # Obteniendo el Caller ID desde Asterisk

my $CID = $ARGV[0]; # Ajusta esto si usas una variable diferente en Asterisk.

my $empresa = $ARGV[1];

my $id_envio = $ARGV[2];

# my $CID = "3006609369";
# my $empresa = "FSFB";
# my $id_envio = 2;

# URL de destino
my $url = "https://apitellit.aldeamo.com/SmsiWS/smsSendPost/";

# Nombre de usuario y contrasena para autenticacion basica
my $username = "Dondoctor";
my $password = "MzMzMDMzMzAxNA==";

# Crear un objeto UserAgent
my $ua = LWP::UserAgent->new;

# Configurar la autenticacion basica
$ua->default_header('Authorization' => 'Basic ' . encode_base64("$username:$password"));

my $dbh = DBI->connect("DBI:mysql:database=$dbname;host=$dbhost", $dbuser, $dbpass)
    or die "No se pudo conectar a la base de datos: $DBI::errstr";

my $query = "SELECT * FROM Sms WHERE nombre_cliente = ? AND id_envio = ?";
my $sth = $dbh->prepare($query)
    or die "No se pudo preparar la consulta: $dbh->errstr";

$sth->execute($empresa, $id_envio)
    or die "No se pudo ejecutar la consulta: $sth->errstr";


if (my $row = $sth->fetchrow_hashref) {
    $agi->verbose("ID: $row->{id}");
    $agi->verbose("Datos de la empresa '$empresa':");
    $agi->verbose("SMS Enviado: $row->{sms_a_enviar}");
    $agi->verbose("ID envio: $row->{id_envio}");

    # Convertir la estructura de datos en JSON

    my $json_data = qq({
    "country": "57",
    "message": "$row->{sms_a_enviar}",
    "messageFormat": 1,
    "addresseeList": [
    {
    "mobile": "$CID"
    }
      ]
    });

    my $sms_send = $row->{sms_a_enviar};

    # Crear la solicitud POST con el JSON
    my $req = HTTP::Request->new(POST => $url);
    $req->header('Content-Type' => 'application/json');
    $req->content($json_data);

    # Realizar la solicitud HTTP
    my $response = $ua->request($req);

    # Verificar la respuesta
    if ($response->is_success) {
        $agi->verbose("JSON enviado exitosamente a " . $CID . " con codigo " . $response->status_line);
        #  print $response->status_line;
        my $insert_query = "INSERT INTO Registro_sms (telefono, empresa, sms_enviado, estado, code_response) VALUES (?, ?, ?, ?, ?)";

        my $insert_sth = $dbh->prepare($insert_query)
            or die "No se pudo preparar la consulta de inserción: $dbh->errstr";

        my $code_response = $response->status_line;

        # Definir el valor para 'status' (esto es solo un ejemplo, debes definir el valor correcto)
        my $status = $response->is_success ? "Enviado" : "Error";

        $insert_sth->execute( $CID, $empresa, $sms_send, $status, $code_response)
            or die "No se pudo ejecutar la consulta de inserción: $insert_sth->errstr";

        $agi->verbose("Inserción en la base de datos realizada con éxito.");
        $insert_sth->finish();





    } else {
        $agi->verbose("Error al enviar JSON: " . $response->status_line);
        # print $response->status_line;
    }

    # Puedes hacer m  s acciones si la empresa existe y acceder a otros campos de la fila si es necesario
} else {
    $agi->verbose("La empresa '$empresa' no existe en la base de datos.");
    # Puedes hacer m  s acciones si la empresa no existe

    # print 'la empresa '.$empresa. 'no existe en la Base de datos';
}

$sth->finish();


$dbh->disconnect();



