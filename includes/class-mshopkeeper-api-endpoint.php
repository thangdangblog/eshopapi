<?php

class MshopkeeperApiEndPoint
{
    private $url;
    private $MshopkeeperApiData;

    public function __construct(){
        $this->MshopkeeperApiData = new MshopkeeperApiData();
        $this->setUrl();
        $this->getAllBranch();
    }

    public function setUrl(){
        $this->url = MSHOPKEEPER_API_SCHEMA . MSHOPKEEPER_API_URL ."/". $this->MshopkeeperApiData->getEnvironment();
    }

    public function getAllBranch(){
        
        $endPoint = "/api/v1/branchs/all";

        $header = [
            "Authorization" => "Bearer " .  $this->MshopkeeperApiData->getAccessToken(),
            "CompanyCode" => $this->MshopkeeperApiData->getCompanyCode()
        ];

        $body = [
            "IsIncludeInactiveBranch" => true,
            "IsIncludeChainOfBranch" => false,
        ];

        return $this->callApi($endPoint,$header,$body);

    }

    public function callApi($endPoint,$header,$body){
        $url = $this->url . $endPoint;
        $args = [
            "method" => "POST",
            "body" => $body,
            "headers" => $header,
            "timeout" => 30,
        ];
        $res = json_decode(wp_remote_retrieve_body(wp_remote_request($url, $args)));
        if(isset($res->ErrorType)){
            return false;
        }
        return $res->Data;
    }



}
