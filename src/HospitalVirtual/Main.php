<?php

require_once __DIR__ . '/../../vendor/autoload.php'; // Autoload files using Composer autoload

HospitalVirtual\SDK::setApiKey("ef265d97a9d843e4a92dba78e0f72eca");

$paciente = new \HospitalVirtual\Entities\Paciente();


$paciente->setNombreApellido("Juan", "Perugia");
$paciente->setMail("juancarlosasa@yopmail.com");
$paciente->setTelefono(54, 351, 7400275);
$paciente->setDNI(41000000);
$paciente->setDomicilio("Santa Fe", "Capital");
$paciente->setPassword("Franco200!", "Franco200!");
$paciente->setGenero("Masculino");
$paciente->setFechaNacimiento("1997-11-17");
$paciente->setNacionalidad("Argentina");

$paciente->save();


?>