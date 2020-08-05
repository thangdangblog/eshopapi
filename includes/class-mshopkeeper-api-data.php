<?php

class MshopkeeperApiData
{

    public function setAppID($id = ""){
        return $this->updateMisaOption('MisaAppID',$id);
    }

    public function getAppID()
    {
        return get_option('MisaAppID') ? get_option('MisaAppID') : "";
    }

    public function setDomain($domain = ""){
        return $this->updateMisaOption('MisaDomain', $domain);
    }

    public function getDomain()
    {
        return get_option('MisaDomain') ? get_option('MisaDomain') : "";
    }

    public function setSecretCode($code){
        return $this->updateMisaOption('MisaSecretCode', $code);
    }

    public function getSecretCode()
    {
        return get_option('MisaSecretCode') ? get_option('MisaSecretCode') : "";
    }

    public function setAccessToken($accessToken){
        return $this->updateMisaOption('MisaAccessToken', $accessToken);
    }

    public function getAccessToken()
    {
        return get_option('MisaAccessToken') ? get_option('MisaAccessToken') : "";
    }

    public function setCompanyCode($companyCode){
        return $this->updateMisaOption('MisaCompanyCode', $companyCode);
    }

    public function getCompanyCode()
    {
        return get_option('MisaCompanyCode') ? get_option('MisaCompanyCode') : "";
    }

    public function setEnvironment($environment){
        return $this->updateMisaOption('MisaEnvironment', $environment);
    }

    public function getEnvironment()
    {
        return get_option('MisaEnvironment') ? get_option('MisaEnvironment') : "";
    }

    public function setBranchCode($branchCode){
        return $this->updateMisaOption('MisaBranchCode', $branchCode);
    }

    public function getBranchCode(){
        return get_option('MisaBranchCode') ? get_option('MisaBranchCode') : "";
    }

    public function setLastSyncDate(){
        
        return $this->updateMisaOption('MisaLastSyncDate', getCurrentUTC());
    }

    public function getLastSyncDate(){
        return get_option('MisaLastSyncDate') ? get_option('MisaLastSyncDate') : "";
    }

    public function deleteAllOption(){
        $settingNames = [
            'MisaAppID',
            'MisaDomain',
            'MisaSecretCode',
            'MisaAccessToken',
            'MisaCompanyCode',
            'MisaEnvironment',
            'MisaBranchCode',
        ];

        foreach($settingNames as $settingName){
            delete_option( $settingName );
        }

    }

    // Lưu key vào cơ sở dữ liệu
    public function updateMisaOption($key,$value){
        if((get_option($key) || get_option($key) == "") && get_option($key) != $value){
            return update_option($key,$value);
        }else if(get_option($key) == $value){
            return true;
        }else{
            return add_option($key,$value);
        }
    }
}
