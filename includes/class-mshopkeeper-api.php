<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link        https://thangdangblog.com/
 * @since      1.0.0
 *
 * @package    Mshopkeeper_Api
 * @subpackage Mshopkeeper_Api/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Mshopkeeper_Api
 * @subpackage Mshopkeeper_Api/includes
 * @author     Đặng Quốc Thắng <thangdangblog@gmail.com>
 */
class Mshopkeeper_Api
{

    /**
     * The loader that's responsible for maintaining and registering all hooks that power
     * the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      Mshopkeeper_Api_Loader    $loader    Maintains and registers all hooks for the plugin.
     */
    protected $loader;

    /**
     * The unique identifier of this plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string    $plugin_name    The string used to uniquely identify this plugin.
     */
    protected $plugin_name;

    /**
     * The current version of the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string    $version    The current version of the plugin.
     */
    protected $version;

    /**
     * Define the core functionality of the plugin.
     *
     * Set the plugin name and the plugin version that can be used throughout the plugin.
     * Load the dependencies, define the locale, and set the hooks for the admin area and
     * the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function __construct()
    {
        if (defined('MSHOPKEEPER_API_VERSION')) {
            $this->version = MSHOPKEEPER_API_VERSION;
        } else {
            $this->version = '1.0.0';
        }
        $this->plugin_name = 'mshopkeeper-api';

        $this->load_dependencies();
        $this->set_locale();
        $this->runSettingPage(); 
        $this->runCustomCheckOut(); 
        $this->runAjax(); 
        $this->define_admin_hooks();
        $this->define_public_filter();
        $this->define_public_hooks();
    }

    /**
     * Load the required dependencies for this plugin.
     *
     * Include the following files that make up the plugin:
     *
     * - Mshopkeeper_Api_Loader. Orchestrates the hooks of the plugin.
     * - Mshopkeeper_Api_i18n. Defines internationalization functionality.
     * - Mshopkeeper_Api_Admin. Defines all hooks for the admin area.
     * - Mshopkeeper_Api_Public. Defines all hooks for the public side of the site.
     *
     * Create an instance of the loader which will be used to register the hooks
     * with WordPress.
     *
     * @since    1.0.0
     * @access   private
     */
    private function load_dependencies()
    {

        /**
         * The class responsible for orchestrating the actions and filters of the
         * core plugin.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-mshopkeeper-api-loader.php';

        /**
         * The class responsible for defining internationalization functionality
         * of the plugin.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-mshopkeeper-api-i18n.php';

        /**
         * The class responsible for defining all actions that occur in the admin area.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-mshopkeeper-api-admin.php';

        /**
         * Class setup for setting page
         */
        require_once MSHOPKEEPER_API_PATH_PLUGIN . 'includes/class-mshopkeeper-api-setting-page.php';

        /**
         * Class setting auth connection to sever
         */
        require_once MSHOPKEEPER_API_PATH_PLUGIN . 'includes/class-mshopkeeper-api-connection.php';
        
        /**
         * Class xử lý dữ liệu public
         */
        require_once MSHOPKEEPER_API_PATH_PLUGIN . 'public/class-mshopkeeper-api-public.php';

        /**
         * Class xử lý dữ liệu lưu db
         */
        require_once MSHOPKEEPER_API_PATH_PLUGIN . 'includes/class-mshopkeeper-api-data.php';
                
        /**
         * Xử lý gọi API
         */
        require_once MSHOPKEEPER_API_PATH_PLUGIN . 'includes/class-mshopkeeper-api-endpoint.php';

        /**
         * Xử lý gọi Ajax
         */
        require_once MSHOPKEEPER_API_PATH_PLUGIN . 'includes/class-mshopkeeper-api-ajax.php';

        /**
         * Xử lý gọi API
         */
        require_once MSHOPKEEPER_API_PATH_PLUGIN . 'admin/functions/functions.php';
        
        /**
         * WooCommerce Custom
         */
        require_once MSHOPKEEPER_API_PATH_PLUGIN . 'includes/class-woocommerce-helper.php';
        
        /**
         * Custom Checkout WooCommerce
         */
        require_once MSHOPKEEPER_API_PATH_PLUGIN . 'includes/class-mshopkeeper-checkout.php';


        $this->loader = new Mshopkeeper_Api_Loader();
    }

    /**
     * Define the locale for this plugin for internationalization.
     *
     * Uses the Mshopkeeper_Api_i18n class in order to set the domain and to register the hook
     * with WordPress.
     *
     * @since    1.0.0
     * @access   private
     */
    private function set_locale()
    {
        $plugin_i18n = new Mshopkeeper_Api_i18n();

        $this->loader->add_action('plugins_loaded', $plugin_i18n, 'load_plugin_textdomain');
    }

    /**
     * Chạy thiết lập cài đặt setting page
     *
     */
    private function runSettingPage()
    {
        $settingPage = new MshopkeeperApiSettingPage();
    }

    private function runAjax(){
        $ajax = new MshopkeeperApiAjax();
    }

    private function runCustomCheckOut(){
        $checkout = new MshopkeeperApiCheckout();
    }

    /**
     * Register all of the hooks related to the admin area functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_admin_hooks()
    {
        $plugin_admin = new Mshopkeeper_Api_Admin($this->get_plugin_name(), $this->get_version());

        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_styles');
        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts');
    }

    /**
     * Register all of the hooks related to the public-facing functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_public_hooks()
    {
        $plugin_public = new Mshopkeeper_Api_Public($this->get_plugin_name(), $this->get_version());

        $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_styles');
        $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_scripts');
    }

    private function define_public_filter(){
        $plugin_admin = new Mshopkeeper_Api_Admin($this->get_plugin_name(), $this->get_version());

        $this->loader->add_filter('plugin_action_links_'.MSHOPKEEPER_BASENAME, $plugin_admin, 'addPluginLink');
        
    }

    /**
     * Run the loader to execute all of the hooks with WordPress.
     *
     * @since    1.0.0
     */
    public function run()
    {
        $this->loader->run();
    }

    /**
     * The name of the plugin used to uniquely identify it within the context of
     * WordPress and to define internationalization functionality.
     *
     * @since     1.0.0
     * @return    string    The name of the plugin.
     */
    public function get_plugin_name()
    {
        return $this->plugin_name;
    }

    /**
     * The reference to the class that orchestrates the hooks with the plugin.
     *
     * @since     1.0.0
     * @return    Mshopkeeper_Api_Loader    Orchestrates the hooks of the plugin.
     */
    public function get_loader()
    {
        return $this->loader;
    }

    /**
     * Retrieve the version number of the plugin.
     *
     * @since     1.0.0
     * @return    string    The version number of the plugin.
     */
    public function get_version()
    {
        return $this->version;
    }
}
