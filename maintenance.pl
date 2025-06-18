#!/usr/bin/perl

use strict;
use warnings;

# Datos de conexión a MySQL
my $db_user = 'root';
my $db_password = 'd57hgtfresh3rdxy78D';

# Obtener la lista de bases de datos
my @databases = `mysql -u$db_user -p$db_password -e "SHOW DATABASES"`;

# Iterar a través de las bases de datos y ejecutar mysqlcheck en cada una
foreach my $db (@databases) {
    chomp $db; # Eliminar el salto de línea al final de cada nombre de base de datos
    next if $db eq 'Database'; # Saltar la primera línea de resultados

    # Ejecutar mysqlcheck en la base de datos actual
    system("mysqlcheck -u$db_user -p$db_password $db -r");
    
    if ($? == 0) {
        print "Mantenimiento exitoso para la base de datos $db\n";
    } else {
        print "Error en el mantenimiento para la base de datos $db: $!\n";
    }
}

