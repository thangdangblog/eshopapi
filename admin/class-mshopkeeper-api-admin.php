<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link        https://thangdangblog.com/
 * @since      1.0.0
 *
 * @package    Mshopkeeper_Api
 * @subpackage Mshopkeeper_Api/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Mshopkeeper_Api
 * @subpackage Mshopkeeper_Api/admin
 * @author     Đặng Quốc Thắng <thangdangblog@gmail.com>
 */
class Mshopkeeper_Api_Admin
{

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $plugin_name    The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string    $plugin_name       The name of this plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct($plugin_name, $version)
    {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }

    /**
     * Register the stylesheets for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_styles()
    {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Mshopkeeper_Api_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Mshopkeeper_Api_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/mshopkeeper-api-admin.css', array(), $this->version, 'all');
    }

    /**
     * Register the JavaScript for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts($hook_suffix)
    {


        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Mshopkeeper_Api_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Mshopkeeper_Api_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        if ($hook_suffix == "toplevel_page_mshopkeeper-api-setting") {
					wp_enqueue_script('jquery-new', plugin_dir_url(__FILE__) . 'js/jquery-3.5.1.min.js', array(), $this->version, false);
	        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/mshopkeeper-api-admin.js', array( 'jquery-new' ), $this->version, false);
  	      wp_localize_script($this->plugin_name, 'misa_ajax_object', array( 'ajax_url' => admin_url('admin-ajax.php') ));
        }

        
    }

    public function addPluginLink($links)
    {
        $settings_link = '<a href="'.admin_url().'?page=mshopkeeper-api-setting">Settings</a>';
        array_unshift($links, $settings_link);
        return $links;
    }
}
