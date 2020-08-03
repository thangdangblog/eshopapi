<?php

class MshopkeeperApiAjax{

    private $MshopkeeperApiEndPoint;

    public function __construct(){
        $this->MshopkeeperApiEndPoint = new MshopkeeperApiEndPoint();
        $this->runAjaxHook();
    }

    // Xử lý đồng bộ khi gọi đồng bộ
    public function syncProduct(){
        $products = $this->MshopkeeperApiEndPoint->getAllProduct(1);

        $product = $products[count($products)-1];

        // Kiểm tra xem sản phẩm có thuộc tính hay không
        if($product->ListDetail){
            
        }else{

            $nameProduct =  $product->Name;  
            $sellingPrice =  $product->SellingPrice ;  
            $description =  $product->NaDescriptionme;  


            $product_id = wp_insert_post([
                "post_type" => "product",
                "post_status" => "publish",
                'post_title'    => $nameProduct,
                'post_content'  => "Hello",
                'post_excerpt'  => "Hello",
            ],true);


            $productWoo = new WC_Product_Simple($product_id);

            
            $productWoo->set_name( $nameProduct);
            $productWoo->set_description( $description);
            $productWoo->set_price( $sellingPrice);
            $productWoo->set_status('publish');

            var_dump($productWoo->save());

        }


        die();
    }

    public function syncCategories(){

    }

    private function runAjaxHook(){
        add_action("wp_ajax_sync_product",[$this,'syncProduct']); 
        add_action("wp_ajax_nopriv_sync_product",[$this,'syncProduct']); 
    }

}