<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://comunidadgzone.es/
 * @since      1.0.0
 *
 * @package    EloSystem
 * @subpackage EloSystem/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    EloSystem
 * @subpackage EloSystem/admin
 * @author     Daniel Martin <dm94official@hotmail.es>
 */
class EloSystem_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $Elo_Ranking    The ID of this plugin.
	 */
	private $Elo_Ranking;

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
	 * @param      string    $Elo_Ranking       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $Elo_Ranking, $version ) {

		$this->Elo_Ranking = $Elo_Ranking;
		$this->version = $version;
	}
	
	/**
	 * Register the Settings page.
	 *
	 * @since    1.0.0
	 */
	public function elosystem_admin_menu() {
		add_menu_page(__('EloSystem', $this->Elo_Ranking),__('Gestión de partidas', $this->Elo_Ranking), 'manage_options', 'el_admin_submenu1',array($this, 'display_adp_gpartidas'),'dashicons-admin-generic');
		add_submenu_page ( 'el_admin_submenu1', __('Gestión de reportes', $this->Elo_Ranking), __('Gestión de reportes', $this->Elo_Ranking), 'manage_options', 'el_admin_submenu2', array($this, 'display_adp_greportes'));
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Plugin_Name_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Plugin_Name_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		//wp_enqueue_style( $this->Elo_Ranking, plugin_dir_url( __FILE__ ) . 'css/bootstrap.min.css', array(), $this->version, 'all' );
		wp_enqueue_style( $this->Elo_Ranking, plugin_dir_url( __FILE__ ) . 'css/sb-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Plugin_Name_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Plugin_Name_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->Elo_Ranking, plugin_dir_url( __FILE__ ) . 'js/elosystem-admin.js', array( 'jquery' ), $this->version, false );

	}
	
	public function display_adp_gpartidas() {
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/elosys-adp-gpartidas.php';
	}
	public function display_adp_greportes() {
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/elosys-adp-greportes.php';
	}
}
