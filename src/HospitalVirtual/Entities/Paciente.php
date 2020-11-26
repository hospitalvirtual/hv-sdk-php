<?php


namespace HospitalVirtual\Entities;


use HospitalVirtual\HVClient;
use HospitalVirtual\HVException;
use HospitalVirtual\SDK;

class Paciente
{

    public $datos_personales = array(
        "id" => null,
        "id_persona" => null,
        "id_usuario" => null,
        "email" => null,
        "nombre" => null,
        "apellido" => null,
        "fecha_nacimiento" => null,
        "codigo_internacional" => null,
        "codigo_area" => null,
        "telefono" => null,
        "genero" => null,
        "estado_civil" => null,
        "cuit" => null,
        "dni" => null,
    );
    public $domicilio = array(
        "pais" => null,
        "provincia" => null,
        "ciudad" => null,
        "calle" => null,
        "numero" => null,
        "dpto" => null,
        "piso" => null,
        "codigo_postal" => null
    );

    public $lugar_nacimiento = array(
        "pais" => null,
        "provincia" => null,
        "ciudad" => null
    );

    public $datos_medicos = array();

    protected $clave;
    protected $claveRepetida;

    public function setNombreApellido($nombre, $apellido)
    {
        $this->datos_personales["nombre"] = $nombre;
        $this->datos_personales["apellido"] = $apellido;
    }

    public function setGenero($genero)
    {
        $this->datos_personales["genero"] = $genero;
    }

    public function setDNI($dni)
    {
        $this->datos_personales["dni"] = $dni;
    }

    public function setMail($email)
    {
        if ($this->datos_personales["email"] != '' && $this->datos_personales["id"] != '') {
            throw new HVException("Restricción de HVAPI, no es posible modificar el mail registrado\n");
        } else {
            $this->datos_personales["email"] = $email;
        }
    }

    public function setNacionalidad($nacionalidadd)
    {
        $this->datos_personales["nacionalidad"] = $nacionalidadd;
    }

    public function setTelefono($codigoInternacional, $codigoArea, $telefono)
    {
        $this->datos_personales["codigo_internacional"] = $codigoInternacional;
        $this->datos_personales["codigo_area"] = $codigoArea;
        $this->datos_personales["telefono"] = $telefono;
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
        $this->datos_personales["fecha_nacimiento"] = $fecha;
    }

    public function toString()
    {
        print_r($this->datos_personales);
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
        $this->datos_personales = (array)$obj->datos_personales;
        $this->domicilio = (array)$obj->domicilio;
        $this->lugar_nacimiento = (array)$obj->lugar_nacimiento;
        $this->datos_medicos = (array)$obj->datos_medicos;

        $datos_med = var_export($this->datos_medicos, true);
        $this->datos_medicos = $datos_med;
    }

    public function consultarPacientes($cantidad, $pagina)
    {
        if ($cantidad != null && $pagina != null) {
            $cliente = new HVClient();
            $arrayPacientes = Array();
            try {
                $pacientes = $cliente->get("pacientes/", array(
                    "cantidad" => $cantidad,
                    "pagina" => $pagina
                ));

                $pacientesArray = $pacientes->pacientes;


                foreach ($pacientesArray as $pac) {

                    $this->parseObject($pac);

                    array_push($arrayPacientes, $this);
                }

                return $arrayPacientes;

            } catch (HVException $e) {
                throw $e;
            }

        } else {
            throw new HVException("La cantidad de pacientes y el número de página son obligatorios");
        }
    }


    public function save()
    {
        /*
         * Registra un paciente, devuelve su ID de la plataforma, Arroja HVException en caso de respuesta 500.
         */
        try {

            $cliente = new HVClient();

            if ($this->datos_personales["id"] != '') {
                $datosEnviar = $this->datos_personales;
                $datosEnviar["api_key"] = SDK::getApiKey();
                $datosEnviar["domicilio"] = $this->domicilio;
                $datosEnviar["lugar_nacimiento"] = $this->lugar_nacimiento;


                $respuesta = $cliente->put("pacientes/" . $this->datos_personales["id_persona"] . "/", $datosEnviar);
                echo $respuesta->mensaje . "\n";
                return $respuesta;
            } else {
                $datosEnviar = $this->datos_personales;
                $datosEnviar["api_key"] = SDK::getApiKey();
                $datosEnviar["clave"] = $this->clave;
                $datosEnviar["clave_repetida"] = $this->claveRepetida;
                $datosEnviar["email_confirmado"] = true;
                $datosEnviar["pais_nacimiento"] = $datosEnviar["nacionalidad"];
                $datosEnviar["provincia_actual"] = $this->domicilio["provincia"];
                $datosEnviar["ciudad_actual"] = $this->domicilio["ciudad"];


                $respuesta = $cliente->post("pacientes/", $datosEnviar);

                return $respuesta->id_paciente_registrado;
            }

        } catch (\HospitalVirtual\HVException $e) {
            throw $e;
        }
    }

}