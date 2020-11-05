<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://comunidadgzone.es/
 * @since      1.0.0
 *
 * @package    EloSystem
 * @subpackage EloSystem/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    EloSystem
 * @subpackage EloSystem/public
 * @author     Daniel Martin <dm94official@hotmail.es>
 */
class EloSystem_Public {

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
	 * @param      string    $Elo_Ranking       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $Elo_Ranking, $version ) {

		$this->Elo_Ranking = $Elo_Ranking;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
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
		wp_enqueue_style( $this->Elo_Ranking, plugin_dir_url( __FILE__ ) . 'css/bootstrap.min.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
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

		wp_enqueue_script( $this->Elo_Ranking, plugin_dir_url( __FILE__ ) . 'js/elosystem-public.js', array( 'jquery' ), $this->version, false );

	}
}

	/**
	 * Custom Post Type: Matches
	 */

	add_action( 'init', 'make_ctp_matches' );
	function make_ctp_matches() {
		$args = array(
			'labels' => array(
				'name' => __( 'Matches' ),
				'singular_name' => __( 'Match' )
			),
			'rewrite' => array( 'slug' => 'matches' ),
			'public' => true,
			'show_in_rest' => true,
			'supports' => array( 'thumbnail','title','custom-fields','comments'),
		);
		register_post_type( 'matches', $args );
	}

	function load_customs_template_el( $template ) {
		global $post;
		if ( 'matches' == $post->post_type && locate_template( array( 'single-matches.php' ) ) != $template ) {
			return plugin_dir_path( __FILE__ ) . 'single-matches.php';
		}
		return $template;
	}
	
	add_filter( 'single_template', 'load_customs_template_el' );
