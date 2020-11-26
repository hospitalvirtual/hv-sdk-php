<?php

require_once __DIR__ . '/../../vendor/autoload.php'; // Autoload files using Composer autoload

HospitalVirtual\SDK::setApiKey("ef265d97a9d843e4a92dba78e0f72eca");

$paciente = new \HospitalVirtual\Entities\Paciente();

try {
    /* EJEMPLO DE COMO CREAR UN PACIENTE */

    /*
    $paciente->setNombreApellido("Juan", "Perez");
    $paciente->setMail("randoma@yopmail.com");
    $paciente->setTelefono(54, 351, 2513524);
    $paciente->setDNI(212312312);
    $paciente->setDomicilio("Cordoba", "Capital");
    $paciente->setPassword("Clave200!", "Clave200!");
    $paciente->setGenero("Masculino");
    $paciente->setFechaNacimiento("1997-11-17");
    $paciente->setNacionalidad("Argentina");
    $paciente->save();
    */

} catch (\HospitalVirtual\HVException $e) {
    echo $e->getMessage();
}


try {

    /* EJEMPLO DE MODIFICACIÓN DE UN PACIENTE EXISTENTE */


    /*
    $paciente->buscarPorId(1164);
    $paciente->setNombreApellido("Juawn", "Perez");
    //$paciente->setMail("gocheeee@yopmail.com"); -> No se permite modificar el mail, este metodo arrojaría una excepción al modificar
    $paciente->setTelefono(54, 333, 213213213);

    $paciente->save();
    */

} catch (\HospitalVirtual\HVException $e) {
    echo $e->getMessage();
}

try {

    /* EJEMPLO DE GENERACION DE UNA COLECCION DE PACIENTES */

    /*
    $pacienteHelper = new \HospitalVirtual\Entities\Paciente();

    $pacientes = $pacienteHelper->consultarPacientes(10, 10);

    print_r($pacientes);
    */

} catch (\HospitalVirtual\HVException $e) {
    echo $e->getMessage();
}


?>