#!/usr/bin/perl

use strict;
use warnings;
use DBI;
use Asterisk::AGI;

my $dbhost = "localhost";
my $dbname = "apis";
my $dbuser = "root";
my $dbpass = "astersql";

my $AGI = new Asterisk::AGI;
my %input = $AGI->ReadParse();

my $mobile_number = $ARGV[0];
my $empresa = $ARGV[1]; # El nombre de la empresa se pasa como argumento desde el dialplan

my $dbh = DBI->connect("DBI:mysql:database=$dbname;host=$dbhost", $dbuser, $dbpass)
    or die "No se pudo conectar a la base de datos: $DBI::errstr";

my $query = "SELECT * FROM sms WHERE nombre_cliente = ?";
my $sth = $dbh->prepare($query)
    or die "No se pudo preparar la consulta: $dbh->errstr";

$sth->execute($empresa)
    or die "No se pudo ejecutar la consulta: $sth->errstr";

# Obtener los datos de la empresa si existe
if (my $row = $sth->fetchrow_hashref) {
    $AGI->verbose("Datos de la empresa '$empresa':");
    $AGI->verbose("ID de Envío: $row->{id_envio}");
    $AGI->verbose("SMS a Enviar: $row->{sms_a_enviar}");

    my $sms_content = $row->{sms_a_enviar};

    # Aquí puedes utilizar $sms_content para enviar el SMS al número $mobile_number
    enviarSMS($mobile_number, $sms_content);

    # Puedes realizar más acciones o validaciones según tus necesidades
} else {
    $AGI->verbose("La empresa '$empresa' no existe en la base de datos.");
    # Puedes hacer más acciones si la empresa no existe
}

$dbh->disconnect();

# Función para enviar el SMS
sub enviarSMS {
    my ($to, $content) = @_;

    # Aquí puedes agregar la lógica para enviar el SMS al número $to con el contenido $content
    # Utiliza servicios de SMS o bibliotecas para enviar SMS, y ajusta esta función según tus necesidades.
}