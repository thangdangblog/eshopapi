<?php

class WC_Helper_Product
{

    public static function saveSimpleProduct($product,$skuUpdate){
        $nameProduct =  isset($product->Name) ? $product->Name : " ";  
        $sellingPrice =  isset($product->SellingPrice) ? $product->SellingPrice : 0 ;  
        $description =  isset($product->Description) ? $product->Description : " ";  
        $sku =  isset($product->Code) ? $product->Code : "";   
        $thumbnailUrl =  isset($product->Picture) ? $product->Picture : null;  

        // Nếu tồn tại sku có mã sản phẩm thì cập nhật, nếu không thì thêm mới
        if(wc_get_product_id_by_sku($skuUpdate)){
            $simpleProduct = new WC_Product_Simple(wc_get_product_id_by_sku($skuUpdate));
        }else{
            $simpleProduct = new WC_Product_Simple();
        }

        $simpleProduct->set_props(
            array(
                'name' => $nameProduct,
                'sku'  => $sku,
                'description' => $description,
                'regular_price' => $sellingPrice
            )
        );

        $simpleProductID = $simpleProduct->save();

        // Nếu có ảnh thì lấy ảnh về
        if($thumbnailUrl){
            self::setFeaturedImage($nameProduct,$thumbnailUrl,$simpleProductID);
        }
    }

    public static function createAttribute($raw_name = 'size', $terms = array( 'small' ))
    {
        global $wpdb, $wc_product_attributes;

        // Make sure caches are clean.
        delete_transient('wc_attribute_taxonomies');
        WC_Cache_Helper::incr_cache_prefix('woocommerce-attributes');

        // These are exported as labels, so convert the label to a name if possible first.
        $attribute_labels = wp_list_pluck(wc_get_attribute_taxonomies(), 'attribute_label', 'attribute_name');
        $attribute_name   = array_search($raw_name, $attribute_labels, true);
        
        if (! $attribute_name) {
            $attribute_name = wc_sanitize_taxonomy_name($raw_name);
        }

        $attribute_id = wc_attribute_taxonomy_id_by_name($attribute_name);

        if (! $attribute_id) {
            $taxonomy_name = wc_attribute_taxonomy_name($attribute_name);

            // Degister taxonomy which other tests may have created...
            unregister_taxonomy($taxonomy_name);

            $attribute_id = wc_create_attribute(
                array(
                    'name'         => $raw_name,
                    'slug'         => $attribute_name,
                    'type'         => 'select',
                    'order_by'     => 'menu_order',
                    'has_archives' => 0,
                )
            );

            // Register as taxonomy.
            register_taxonomy(
                $taxonomy_name,
                apply_filters('woocommerce_taxonomy_objects_' . $taxonomy_name, array( 'product' )),
                apply_filters(
                    'woocommerce_taxonomy_args_' . $taxonomy_name,
                    array(
                        'labels'       => array(
                            'name' => $raw_name,
                        ),
                        'hierarchical' => false,
                        'show_ui'      => false,
                        'query_var'    => true,
                        'rewrite'      => false,
                    )
                )
            );

            // Set product attributes global.
            $wc_product_attributes = array();

            foreach (wc_get_attribute_taxonomies() as $taxonomy) {
                $wc_product_attributes[ wc_attribute_taxonomy_name($taxonomy->attribute_name) ] = $taxonomy;
            }
        }

        $attribute = wc_get_attribute($attribute_id);

        $return = array(
            'attribute_name'     => $attribute->name,
            'attribute_taxonomy' => $attribute->slug,
            'attribute_id'       => $attribute_id,
            'term_ids'           => array(),
        );

        foreach ($terms as $term) {
            $result = term_exists($term, 'pa_'.$attribute->name);

            if (! $result) {
                $result = wp_insert_term($term, $attribute->slug);
                
                $return['term_ids'][] = (int)$result['term_id'];
            } else {
                $return['term_ids'][] = (int)$result['term_id'];
            }
        }
        
        return $return;
    }
    
    // Tạo sản phẩm có biến thể
    public static function saveVariationProduct($productData,$skuUpdate)
    {

        $nameProduct =  isset($productData->Name) ? $productData->Name : " ";  
        $sellingPrice =  isset($productData->SellingPrice) ? $productData->SellingPrice : 0 ;  
        $description =  isset($productData->Description) ? $productData->Description : " ";  
        $sku =  isset($productData->Code) ? $productData->Code : "";   
        $thumbnailUrl =  isset($productData->Picture) ? $productData->Picture : null;   

        // Nếu sản phẩm đã tồn tại thì cập nhật
        if(wc_get_product_id_by_sku($skuUpdate)){
            $product = new WC_Product_Variable(wc_get_product_id_by_sku($skuUpdate));
        }else{
            $product = new WC_Product_Variable();
        }

        $product->set_props(
            array(
                'name' => $productData->Name,
                'sku'  => $productData->Code,
                'description' => $productData->Description
            )
        );

        $attributes = self::createAttributesToProduct([
            "Color" => explode(",",$productData->Color),
            "Size" => explode(",",$productData->Size),
        ]);

        $product->set_attributes($attributes);

        $idProduct = $product->save();

        // Nếu có ảnh thì lấy ảnh về
        if($thumbnailUrl){
            self::setFeaturedImage($nameProduct,$thumbnailUrl,$idProduct);
        }
        
        // Lặp List Product lưu thông tin theo thuộc tính
        foreach ($productData->ListDetail as $detailProduct) {
            if(wc_get_product_id_by_sku($detailProduct->Code)){
                $variation = new WC_Product_Variation(wc_get_product_id_by_sku($detailProduct->Code));
            }else{
                $variation = new WC_Product_Variation();
            }

            $variation->set_attributes(array(
                'pa_size' => self::getTermSlugByName($detailProduct->Size,'pa_size'),
                'pa_color' => self::getTermSlugByName($detailProduct->Color,'pa_color')
            ));

            $variation->set_props(
                array(
                    'parent_id'     => $product->get_id(),
                    'sku'           =>  $detailProduct->Code,
                    'regular_price' => $detailProduct->SellingPrice,
                )
            );
            
            $variation->save();
        }

        return wc_get_product($product->get_id());
    }

    // Get Slug by name
    public static function getTermSlugByName($termName, $taxonomy)
    {
        $result = term_exists($termName, $taxonomy);
        $term = get_term($result['term_id'], $taxonomy);
        return $term->slug;
    }

    public static function setFeaturedImage($name_image, $image_url, $post_id  ){
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

    // Tạo thuộc tính cho sản phẩm
    public static function createAttributesToProduct($attributes){

        $attributesProduct = array();
        
        foreach($attributes as $attributeName => $attributeValue){
            $attribute_data = self::createAttribute($attributeName, $attributeValue);
            $attribute      = new WC_Product_Attribute();
            $attribute->set_id($attribute_data['attribute_id']);
            $attribute->set_name($attribute_data['attribute_taxonomy']);
            $attribute->set_options($attribute_data['term_ids']);
            $attribute->set_position(1);
            $attribute->set_visible(true);
            $attribute->set_variation(true);
            $attributesProduct[] = $attribute;
        }

        return $attributesProduct;
    }
}
