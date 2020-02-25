<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

Class Posapp{

    protected $ci;
    protected $server_api;

    protected $api_urls = array (
        //'genToken'=> $this->ci->config->item('ep_token'), https://sandbox.posindonesia.co.id:8245/utilitas/1.0.1/
        'getFee' => '/getFee',
        'getResi'=> '/getTrackAndTrace',
        'getPropinsi'=> '/getProvince',
        'getKota'=> '/getCity',
        'getKecamatan'=> '/getPostalCodeV2',
        'getPostalCode' => '/getPostalCode',
        'getPostOffice'=> '/getPostOffice',
        'getOffice'=> '/getOffice',
        'getLastStatusAwb'=>'/getTrackAndTraceLastStatus',
        'getResiDetail'=> '/getTrackAndTraceDetail',
        // JASA KEUANGAN
        'getJasKeu'=> 'https://sandbox.posindonesia.co.id:8245/Remittance/1.0.0/getTrackAndTrace',
    );

    function __construct(){
        $this->ci   =& get_instance();
    }

    public $access_token    = false;


    private function __apiCall($url, $params = FALSE, $token = ''){

        $curl   = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        //curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
          
        //curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-type: multipart/form-data"));
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Accept: application/json',
                                                     'Content-type: application/json',
                                                     'Authorization: Bearer '.$token,
                                                     'Content-Length: ' . strlen($params),
                                                     'X-POS-USER: '.$this->ci->config->item('api_user'),
                                                     'X-POS-PASSWORD: '.$this->ci->config->item('api_pass')));
        #curl_setopt($curl, CURLOPT_HTTPHEADER, array("content-type: application/json"));
        #curl_setopt($curl, CURLOPT_HTTPHEADER, array("authorization: Bearer 1f94e60c-82e2-3380-875b-96f82ede4460"));
        
        //if($params !== FALSE){
            curl_setopt($curl, CURLOPT_POSTFIELDS, $params);
        //}
        //if($remove !== FALSE){
        //    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "DELETE");
        //}
        //curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        //curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $content    = curl_exec($curl);
        
        curl_close($curl);
        return json_decode($content);
    }

    public function __apiToken(){
        // dev 
		$key    = $this->ci->config->item('api_key') ? $this->ci->config->item('api_key') : "J2b7Gbf1Mp62ZYgEBjISyBav9Uca"; // dev
        $secret = $this->ci->config->item('api_secret') ? $this->ci->config->item('api_secret') : "YP4xzGZY48X33w1CGyyq92o6cuwa";
        #$key     = "J2b7Gbf1Mp62ZYgEBjISyBav9Uca";
        #$secret  = "YP4xzGZY48X33w1CGyyq92o6cuwa";
        $url    = $this->ci->config->item('ep_token') ? $this->ci->config->item('ep_token') : "https://sandbox.posindonesia.co.id:8245/token";
        #$url    = "https://sandbox.posindonesia.co.id:8245/token";
        
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); 
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query(array('grant_type' => 'client_credentials')));
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Authorization: Basic '. base64_encode($key.':'.$secret), 
                                                     'Content-Type: application/x-www-form-urlencoded',
                                                     'X-POS-USER: '.$this->ci->config->item('api_user'),
                                                     'X-POS-PASSWORD: '.$this->ci->config->item('api_pass')));

                                                    
        $response = curl_exec($curl);
        $response = json_decode($response,true);
        curl_close($curl);
        $token_api = ($response['access_token']);
        return $token_api;
    }

    public function getFee($data = FALSE){
        
        $token  = $this->__apiToken();
        
        $url    = sprintf($this->ci->config->item('ep_util').$this->api_urls['getFee'], "json");
        return $this->__apiCall($url, $data, $token);
    }

    public function getFee2($data = FALSE){
        
        $token  = $this->__apiToken();
        
        $url    = sprintf($this->ci->config->item('ep_utils').$this->api_urls['getFee'], "json");
        return $this->__apiCall($url, $data, $token);
    }

    public function getTracking($data = FALSE)
    {
        $token  = $this->__apiToken();

        $url    = sprintf($this->ci->config->item('ep_util').$this->api_urls['getResi'], "json");
        return $this->__apiCall($url, $data, $token);
    }

    public function getTracking2($data = FALSE)
    {
        $token  = $this->__apiToken();

        $url    = sprintf($this->ci->config->item('ep_utils').$this->api_urls['getResi'], "json");
        return $this->__apiCall($url, $data, $token);
    }

    public function getPropinsi($data = FALSE)
    {
        $token  = $this->__apiToken();
        $url    = sprintf($this->ci->config->item('ep_utils').$this->api_urls['getPropinsi'], "json");
        return $this->__apiCall($url, $data, $token);
    }

    public function getKota($data = FALSE)
    {
        $token  = $this->__apiToken();
        $url    = sprintf($this->ci->config->item('ep_utils').$this->api_urls['getKota'], "json");
        return $this->__apiCall($url, $data, $token);
    }

    public function getKecamatan($data = FALSE)
    {
        $token  = $this->__apiToken();
        $url    = sprintf($this->ci->config->item('ep_utils').$this->api_urls['getKecamatan'], "json");
        return $this->__apiCall($url, $data, $token);
    }

    
    public function getKodePos($data = FALSE)
    {
        $token  = $this->__apiToken();
        $url    = sprintf($this->ci->config->item('ep_utils').$this->api_urls['getPostalCode'], "json");
        return $this->__apiCall($url, $data, $token);
    }

    public function getKantorPos($data = FALSE)
    {
        $token  = $this->__apiToken();
        $url    = sprintf($this->ci->config->item('ep_utils').$this->api_urls['getPostOffice'], "json");
        return $this->__apiCall($url, $data, $token);
    }

    public function getKantorTujuan($data = FALSE)
    {
        $token  = $this->__apiToken();
        $url    = sprintf($this->ci->config->item('ep_util').$this->api_urls['getOffice'], "json");
        return $this->__apiCall($url, $data, $token);
    }
    public function getKantorTujuan2($data = FALSE)
    {
        $token  = $this->__apiToken();
        $url    = sprintf($this->ci->config->item('ep_utils').$this->api_urls['getOffice'], "json");
        return $this->__apiCall($url, $data, $token);
    }

    public function getLastStatusAwb($data = FALSE)
    {
        $token  = $this->__apiToken();
        $url    = sprintf($this->ci->config->item('ep_util').$this->api_urls['getLastStatusAwb'], "json");
        return $this->__apiCall($url, $data, $token);
    }

    public function getLastStatusAwb2($data = FALSE)
    {
        $token  = $this->__apiToken();
        $url    = sprintf($this->ci->config->item('ep_utils').$this->api_urls['getLastStatusAwb'], "json");
        return $this->__apiCall($url, $data, $token);
    }

    public function getTrackingDetail($data = FALSE)
    {
        $token  = $this->__apiToken();
        $url    = sprintf($this->ci->config->item('ep_util').$this->api_urls['getResiDetail'], "json");
        return $this->__apiCall($url, $data, $token);
    }

    public function getJasKeu($data = FALSE)
    {
        $token  = $this->__apiToken();
        $url    = sprintf($this->api_urls['getJasKeu'], "json");
        return $this->__apiCall($url, $data, $token);
    }
    
}