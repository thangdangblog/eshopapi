<?php

class MshopkeeperApiEndPoint
{
    private $url;
    private $MshopkeeperApiData;
    private $MshopkeeperApiConnection;

    public function __construct()
    {
        $this->MshopkeeperApiData = new MshopkeeperApiData();
        $this->checkRequest();
        $this->setUrl();
    }

    public function setUrl()
    {
        $this->url = MSHOPKEEPER_API_SCHEMA . MSHOPKEEPER_API_URL ."/". $this->MshopkeeperApiData->getEnvironment();
    }

    public function getAllBranch()
    {
        $endPoint = "/api/v1/branchs/all";

        $header = [
            "Authorization" => "Bearer " .  $this->MshopkeeperApiData->getAccessToken(),
            "CompanyCode" => $this->MshopkeeperApiData->getCompanyCode()
        ];

        $body = [
            "IsIncludeInactiveBranch" => true,
            "IsIncludeChainOfBranch" => false,
        ];

        return $this->callApi($endPoint, $header, $body,"POST")->Data;
    }

    public function getAllCategories(){
        $endPoint = "/api/v1/categories/list?includeInactive=false";

        $header = [
            "Authorization" => "Bearer " .  $this->MshopkeeperApiData->getAccessToken(),
            "CompanyCode" => $this->MshopkeeperApiData->getCompanyCode()
        ];

        $res = $this->callApi($endPoint,$header,null,"GET");

        if($res->Code == 200){
            return $res->Data;
        }
        return false;
    }

    public function getProductPaging($page = 1){
        $endPoint = "/api/v1/inventoryitems/pagingwithdetail";

        $header = [
            "Authorization" => "Bearer " .  $this->MshopkeeperApiData->getAccessToken(),
            "CompanyCode" => $this->MshopkeeperApiData->getCompanyCode()
        ];
       
        $body = [
            "Page" =>  $page,
            "Limit" =>  100,
            "SortField"=> "Code",
            "SortType"=> "1",
            "IncludeInventory" => true,
            "LastSyncDate" => null
        ];

        $res = $this->callApi($endPoint, $header, $body,"POST");

        if($res->Code != 200) return false;

        return $res->Data;
    }

    public function getAllProduct($allProduct){
        $page = ceil($allProduct / 100);
        $product = array();
        for($i = 1; $i <= $page; $i++){
            if($this->getProductPaging($i)){
                $product = array_merge($product,$this->getProductPaging($i));
            }
        }

        return $product;
    }

    public function getNumberProduct()
    {
        $count = 0; // Đếm số lượng sản phẩm
        
        $endPoint = "/api/v1/inventoryitems/pagingwithdetail";

        $header = [
            "Authorization" => "Bearer " .  $this->MshopkeeperApiData->getAccessToken(),
            "CompanyCode" => $this->MshopkeeperApiData->getCompanyCode()
        ];
       
        $body = [
            "Page" =>  1,
            "Limit" =>  1,
            "SortField"=> "Code",
            "SortType"=> "1",
            "IncludeInventory" => true,
            "InventoryItemCategoryID" => $this->getAllCategories()[0]->Id,
            "LastSyncDate" => null
        ];

        $res = $this->callApi($endPoint, $header, $body,"POST");

        // Kết quả lỗi
        if(!$res) return 0;

        // Trả về số lượng sản phẩm chưa đồng bộ
        return $res->Total;
    }

    // Kiểm tra xem token còn hạn không, nếu hết thì lấy lại
    public function checkRequest(){
        if(!$this->getAllCategories){
            $appId = $this->MshopkeeperApiData->getAppID(); 
            $nameConnection = $this->MshopkeeperApiData->getDomain(); 
            $secretCode = $this->MshopkeeperApiData->getSecretCode(); 
            $this->MshopkeeperApiConnection = new MshopkeeperApiConnection($appId,$nameConnection,$secretCode);
            $this->MshopkeeperApiConnection->getToken();
        }
    }

    public function callApi($endPoint = null, $header = null, $body = null,$method = "POST")
    {
        $url = $this->url . $endPoint;
        $args = [
            "method" => $method,
            "body" => $body,
            "headers" => $header,
            "timeout" => 30,
        ];
        $res = json_decode(wp_remote_retrieve_body(wp_remote_request($url, $args)));

        // Dữ liệu bị lỗi
        if (isset($res->ErrorType)) {
            return false;
        }
        return $res;
    }
}
