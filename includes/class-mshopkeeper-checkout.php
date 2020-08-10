<?php

class MshopkeeperApiCheckout{

    public function __construct(){
        $this->initHook();
    }

    private function initHook(){
        add_filter( 'woocommerce_checkout_fields' , [$this,'removeFieldCheckoutPage'] );
        add_action( 'woocommerce_after_checkout_billing_form' , [$this,'addCustomFieldCheckoutPage'] );
    }

    public function removeFieldCheckoutPage($fields){
        unset($fields['billing']['billing_postcode']);
        unset($fields['billing']['billing_company']);
        unset($fields['billing']['billing_state']);
        unset($fields['billing']['billing_country']);
        unset($fields['billing']['billing_address_1']);
        unset($fields['billing']['billing_address_2']);
        unset($fields['billing']['billing_city']);
        return $fields;
    }

    public function addCustomFieldCheckoutPage($checkout){

        $allProvinces = $this->getAllProvinces();

        // Thêm field tỉnh thành
        woocommerce_form_field('provinceCheckout',[
            'type' => 'select',
            'class' => ['nationCheckout'],
            'label' => 'Quốc gia',
            'options' => $allProvinces
            ],$checkout->get_value( 'provinceCheckout' )
        );

        // Thêm field quận huyện
        woocommerce_form_field('districtCheckout',[
            'type' => 'select',
            'class' => ['districtCheckout'],
            'label' => 'Quận huyện',
            'options' => [
                'null' => 'Chọn quận huyện'
            ]
            ],$checkout->get_value( 'districtCheckout' )
        );

        
    }

    // Lấy toàn bộ tỉnh thành và sắp xếp theo cấu trúc
    public function getAllProvinces(){
        $allProvinces = array();
        $MshopkeeperApiEndPoint = new MshopkeeperApiEndPoint();
        $provinces = $MshopkeeperApiEndPoint->getAllProvinces();

        foreach($provinces as $province){
            $allProvinces[$province->Id] = $province->Name;
        }

        return $allProvinces;
    }


}