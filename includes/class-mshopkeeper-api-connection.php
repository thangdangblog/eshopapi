<?php

class MshopkeeperApiConnection
{
    private $appID;
    private $domain;
    private $loginTime;
    private $secretCode;

    public function __construct($appID,$domain,$secretCode){
        $this->setLoginTime();
        $this->setDomain($domain);
        $this->setSecretCode($secretCode);
        $this->setAppID($appID);
    }

    //Kiểm tra dữ liệu nhập
    public function checkData(){
        if($this->getToken()){
            $MshopkeeperApiData = new MshopkeeperApiData();

            return $MshopkeeperApiData->setDomain($this->domain) 
                && $MshopkeeperApiData->setSecretCode($this->secretCode) 
                && $MshopkeeperApiData->setAppID($this->appID);
        }
        return false;
    }

    public function getToken()
    {
        $url = MSHOPKEEPER_API_SCHEMA . MSHOPKEEPER_API_URL_AUTH . "/api/Account/Login";
        
        $body = [
            "Domain" => $this->domain,
            "AppID" => $this->appID,
            "LoginTime" => getCurrentUTC(),
            "SignatureInfo" => $this->getSignatureInfo(),
        ];

        $args = [
            "method" => "POST",
            "body" => $body,
            "timeout" => 30,
        ];

        // Gọi API 
        $res = wp_remote_request($url,$args); 
        
        if(!is_wp_error($res)){
            $MshopkeeperApiData =  new MshopkeeperApiData();
            $res = json_decode(wp_remote_retrieve_body($res));
            if(!isset($res->ErrorType)){
                $companyCode = $res->Data->CompanyCode;
                $environment = $res->Data->Environment;
                $accessToken = $res->Data->AccessToken;

                return $MshopkeeperApiData->setAccessToken($accessToken) 
                    && $MshopkeeperApiData->setCompanyCode($companyCode)
                    && $MshopkeeperApiData->setEnvironment($environment);
            }else{
                $MshopkeeperApiData->setAccessToken("");
                $MshopkeeperApiData->setCompanyCode("");
                $MshopkeeperApiData->setEnvironment("");
                return false;
            }
        }
    }

    public function setLoginTime(){
        $this->loginTime = gmdate("Y-m-d\TH:i:s\Z");
    }

    public function setAppID($appID){
        $this->appID = trim($appID);
    }

    public function setDomain($domain){
        $this->domain = trim($domain);
    }

    public function setSecretCode($secretCode){
        $this->secretCode = trim($secretCode);
    }

    public function getSignatureInfo(){
        $args = [
            "AppID" => $this->appID,
            "Domain" => $this->domain,
            "LoginTime" => $this->loginTime,
        ];
        $text = json_encode((object)$args);
        return hash_hmac("sha256",$text,$this->secretCode);
    }

}
