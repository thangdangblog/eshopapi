<?php
class MshopkeeperApiSettingPage
{
    public function __construct()
    {
        $this->runHookSettingPage();
    }


    public function addMenuPage(){
        $page_title = "MshopKeeper API Setting Page";
        $menu_title = "Mshop API Setting";
        $capability = "manage_options";
        $menu_slug = "mshopkeeper-api-setting";
        $icon_url = "dashicons-update-alt";

        add_menu_page($page_title, $menu_title,$capability,$menu_slug,[$this,'showSettingPage'], $icon_url,$position = null );
    }

    // Hiển thị trang view
    public function showSettingPage(){
        require MSHOPKEEPER_API_PATH_PLUGIN . "admin/views/admin-page-setting-api.php";
    }

    // Xử lý dữ liệu khi người dùng lưu thông tin xác thực
    public function handleSaveAuthenticator(){
        // Xác thực form
        if(!isset($_POST['misa_nonce']) || !wp_verify_nonce($_POST['misa_nonce'],'form_save_infomation_misa') ){
            return false;
        }

        if(isset($_POST['save_infomation'])){
            $nameConnection = isset($_POST['name_connection']) ? $_POST['name_connection'] : "";
            $appId = isset($_POST['app_id']) ? $_POST['app_id'] : "";
            $secretCode = isset($_POST['secret_code']) ? $_POST['secret_code'] : "";

            $MshopkeeperApiConnection = new MshopkeeperApiConnection($appId,$nameConnection,$secretCode);

            if($MshopkeeperApiConnection->checkData()){
                wp_redirect($_SERVER['HTTP_REFERER']."&message=save-authenticate-success");
            }else{
                wp_redirect($_SERVER['HTTP_REFERER']."&message=save-authenticate-error");
            }
        }
    }

    public function handleSaveBranch(){
        // Xác thực form
        if(!isset($_POST['misa_nonce']) || !wp_verify_nonce($_POST['misa_nonce'],'form_save_infomation_misa') ){
            return false;
        }

        if(isset($_POST['save_branch'])){
            
            $branchCode = isset($_POST['misa_branch']) && $_POST['misa_branch'] != "null" ? $_POST['misa_branch'] : "";

            if(!$branchCode) {
                return wp_redirect($_SERVER['HTTP_REFERER']."&message=branch-false");
            }

            // Khởi tạo đối tượng lưu dữ liệu
            $MshopkeeperApiData = new MshopkeeperApiData();

            if($MshopkeeperApiData->setBranchCode($branchCode)){
                return wp_redirect($_SERVER['HTTP_REFERER']."&message=save-branch-success");
            }

            return wp_redirect($_SERVER['HTTP_REFERER']."&message=save-branch-false");

        }

    }

    // Khởi tạo hook
    private function runHookSettingPage(){
        add_action('admin_menu',[$this,'addMenuPage']);
        add_action('admin_action_action_save_authenticator',[$this,'handleSaveAuthenticator']);
        add_action('admin_action_action_save_branch',[$this,'handleSaveBranch']);
    }

    

}