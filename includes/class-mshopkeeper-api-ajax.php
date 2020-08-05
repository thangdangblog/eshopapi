<?php

class MshopkeeperApiAjax{

    private $MshopkeeperApiEndPoint;

    public function __construct(){
        $this->MshopkeeperApiEndPoint = new MshopkeeperApiEndPoint();
        $this->runAjaxHook();
    }

    // Xử lý đồng bộ khi nhấn nút đồng bộ
    public function syncProduct(){
        $products = $this->MshopkeeperApiEndPoint->getAllProduct(24);

        $status = "true";
        // $product = $[count($products)-1];
        foreach($products as $product){
            // Kiểm tra xem sản phẩm có thuộc tính hay không
            if($product->ListDetail){
                foreach($product->ListDetail as $productChild){
                    try{
                        $this->saveProduct($productChild);
                    }catch(Exception $e){
                        // Misa
                        $status = "false";
                    }
                }
            }else{
                try{
                    $this->saveProduct($product);
                }catch(Exception $e){
                    // Misa
                    $status = "false";
                }
            }
        }

        // Đồng bộ kết quả lấy sản phẩm
        $this->MshopkeeperApiData = new MshopkeeperApiData();
        $this->MshopkeeperApiData->setLastSyncDate();
        
        die($status);
    }

    // Lưu sản phẩm 
    public function saveProduct($product){
        $nameProduct =  isset($product->Name) ? $product->Name : " ";  
        $sellingPrice =  isset($product->SellingPrice) ? $product->SellingPrice : 0 ;  
        $description =  isset($product->Description) ? $product->Description : " ";  
        $sku =  isset($product->Code) ? $product->Code : "";   
        $thumbnailUrl =  isset($product->Picture) ? $product->Picture : null;   

        $product_id = wp_insert_post([
            "post_type" => "product",
            "post_status" => "publish",
            'post_title'    => $nameProduct,
            'post_content'  => $description,
        ],true);

        // Nếu có ảnh thì lấy ảnh về
        if($thumbnailUrl){
            $this->setFeaturedImage($nameProduct,$thumbnailUrl,$product_id);
        }

        $productWoo = new WC_Product_Simple($product_id);
        $productWoo->set_regular_price($sellingPrice);
        try{
            $productWoo->set_sku($sku);
        }catch(Exception $e){
            // Misa
        }
        return $productWoo->save();
    }

    public function syncCategories(){

    }

    private function runAjaxHook(){
        add_action("wp_ajax_sync_product",[$this,'syncProduct']); 
        add_action("wp_ajax_nopriv_sync_product",[$this,'syncProduct']); 
    }

    function setFeaturedImage($name_image, $image_url, $post_id  ){
        $upload_dir = wp_upload_dir();
        $image_data = file_get_contents($image_url);
        $filename = toSlug($name_image).".png";


        if(wp_mkdir_p($upload_dir['path']))
          $file = $upload_dir['path'] . '/' . $filename;
        else
          $file = $upload_dir['basedir'] . '/' . $filename;
        file_put_contents($file, $image_data);
        
        $wp_filetype = wp_check_filetype($filename, null );
        $attachment = array(
            'post_mime_type' => $wp_filetype['type'],
            'post_title' => sanitize_file_name($filename),
            'post_content' => '',
            'post_status' => 'inherit'
        );
        $attach_id = wp_insert_attachment( $attachment, $file, $post_id );
        require_once(ABSPATH . 'wp-admin/includes/image.php');
        $attach_data = wp_generate_attachment_metadata( $attach_id, $file );
        wp_update_attachment_metadata( $attach_id, $attach_data );
        set_post_thumbnail( $post_id, $attach_id );
    }

}