<?php


namespace HospitalVirtual;


class HVClient
{
    private $apiUrlTesting = "https://www.hospitalvirtual.pabex.com.ar:8443/api/api_rest/v1/";
    private $apiUrlProduccion = "https://www.hospitalvirtual.com/api/api_rest/v1/";

    private function getApiURL()
    {
        if (SDK::isProduction()) {
            return $this->apiUrlProduccion;
        } else {
            return $this->apiUrlTesting;
        }
    }

    public function get($nombreApi)
    {

        $data = array(
            'api_key' => SDK::getApiKey()
        );

        $params = '';
        foreach ($data as $key => $value)
            $params .= $key . '=' . $value . '&';

        $params = trim($params, '&');

        $curlClient = curl_init();

        $endpoint = $this->getApiURL() . $nombreApi . '/?' . $params;


        curl_setopt($curlClient, CURLOPT_URL, $endpoint);
        curl_setopt($curlClient, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curlClient, CURLOPT_HEADER, 0);

        $result = curl_exec($curlClient);

        $responseCode = curl_getinfo($curlClient, CURLINFO_HTTP_CODE);

        $respuestaJson = json_decode($result);

        if ($responseCode != 200) {
            throw new HVException($respuestaJson->mensaje . "\n");
        }

        return json_decode($result)->datos;

        curl_close($curlClient);


    }

    public function post($nombreApi, $objetoPost)
    {


        $curlClient = curl_init();

        $endpoint = $this->getApiURL() . $nombreApi;


        curl_setopt_array($curlClient, array(
            CURLOPT_URL => $endpoint,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => http_build_query($objetoPost),
            CURLOPT_HTTPHEADER => array(
                "cache-control: no-cache",
                "content-type: application/x-www-form-urlencoded"
            ),
        ));

        $result = curl_exec($curlClient);

        $responseCode = curl_getinfo($curlClient, CURLINFO_HTTP_CODE);

        $respuestaJson = json_decode($result);

        if ($responseCode != 200) {
            throw new HVException($respuestaJson->mensaje . "\n");
        }
        curl_close($curlClient);

        return json_decode($result)->datos;



    }


    public function url()
    {
        return $this->getApiURL();
    }

}