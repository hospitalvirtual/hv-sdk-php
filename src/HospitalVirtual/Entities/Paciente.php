<?php


namespace HospitalVirtual\Entities;


use HospitalVirtual\HVClient;
use HospitalVirtual\SDK;

class Paciente
{


    public $datosPersonales = array(
        "id" => null,
        "nombre" => null,
        "apellido" => null,
        "email" => null,
        "nacionalidad" => null,
        "dni" => null,
        "genero" => null,
        "cuit" => null,
        "id_persona" => null,
        "id_usuario" => null,
        "estado_civil" => null,
        "codigo_internacional" => null,
        "codigo_area" => null,
        "telefono" => null,
        "fecha_nacimiento" => null
    );
    public $domicilio = array(
        "provincia" => null,
        "ciudad" => null
    );

    protected $clave;
    protected $claveRepetida;

    public function setNombreApellido($nombre, $apellido)
    {
        $this->datosPersonales["nombre"] = $nombre;
        $this->datosPersonales["apellido"] = $apellido;
    }

    public function setGenero($genero)
    {
        $this->datosPersonales["genero"] = $genero;
    }

    public function setDNI($dni)
    {
        $this->datosPersonales["dni"] = $dni;
    }

    public function setMail($email)
    {
        $this->datosPersonales["email"] = $email;
    }

    public function setNacionalidad($nacionalidadd)
    {
        $this->datosPersonales["nacionalidad"] = $nacionalidadd;
    }

    public function setTelefono($codigoInternacional, $codigoArea, $telefono)
    {
        $this->datosPersonales["codigo_internacional"] = $codigoInternacional;
        $this->datosPersonales["codigo_area"] = $codigoArea;
        $this->datosPersonales["telefono"] = $telefono;
    }

    public function setDomicilio($provincia, $ciudad)
    {
        $this->domicilio["provincia"] = $provincia;
        $this->domicilio["ciudad"] = $ciudad;
    }

    public function setPassword($pswd, $repeatPswd)
    {
        $this->clave = $pswd;
        $this->claveRepetida = $repeatPswd;
    }

    public function setFechaNacimiento($fecha)
    {
        $this->datosPersonales["fecha_nacimiento"] = $fecha;
    }

    public function toString()
    {
        print_r($this->datosPersonales);
        print_r($this->domicilio);
    }


    public function buscarPorId($idPaciente)
    {
        $cliente = new \HospitalVirtual\HVClient();
        try {
            $datosUsuario = $cliente->get("pacientes/" . $idPaciente);

            //$this->otros = $datosUsuario->paciente;
            $this->parseObject($datosUsuario->paciente);


        } catch (\HospitalVirtual\HVException $e) {
            echo $e->getMessage();
        }

    }

    private function parseObject($obj)
    {
        $this->datosPersonales["id"] = $obj->datos_personales->id;
        $this->datosPersonales["nombre"] = $obj->datos_personales->nombre;
        $this->datosPersonales["apellido"] = $obj->datos_personales->apellido;
        $this->datosPersonales["email"] = $obj->datos_personales->email;
        $this->datosPersonales["dni"] = $obj->datos_personales->dni;
        $this->datosPersonales["genero"] = $obj->datos_personales->genero;
        $this->datosPersonales["cuit"] = $obj->datos_personales->cuit;
        $this->datosPersonales["id_persona"] = $obj->datos_personales->id_persona;
        $this->datosPersonales["id_usuario"] = $obj->datos_personales->id_usuario;
    }

    public function save()
    {
        /*
         * Registra un paciente, devuelve su ID de la plataforma, Arroja HVException en caso de respuesta 500.
         */
        try {

            $cliente = new HVClient();

            $datosEnviar = $this->datosPersonales;
            $datosEnviar["api_key"] = SDK::getApiKey();
            $datosEnviar["clave"] = $this->clave;
            $datosEnviar["clave_repetida"] = $this->claveRepetida;
            $datosEnviar["email_confirmado"] = true;
            $datosEnviar["pais_nacimiento"] = $datosEnviar["nacionalidad"];
            $datosEnviar["provincia_actual"] = $this->domicilio["provincia"];
            $datosEnviar["ciudad_actual"] = $this->domicilio["ciudad"];


            $respuesta = $cliente->post("pacientes/", $datosEnviar);

            return $respuesta->id_paciente_registrado;

        } catch (\HospitalVirtual\HVException $e) {
            echo $e->getMessage();
        }
    }

}