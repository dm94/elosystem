<?php
/**
 * Plugin Name: EloSystem
 * Plugin URI: https://comunidadgzone.es/
 * Description: Permite la creación de partidas basadas en un elo global
 * Version: 1.0.0
 * Author: Daniel Martin
 * Author URI: https://comunidadgzone.es/
 * Text Domain: elosystem
 * Domain Path: /languages
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'PLUGIN_NAME_VERSION', '1.0.0' );

function activate_elosystem() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-elosystem-activator.php';
	EloSystem_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-elosystem-deactivator.php
 */
function deactivate_elosystem() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-elosystem-deactivator.php';
	EloSystem_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_elosystem' );
register_deactivation_hook( __FILE__, 'deactivate_elosystem' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-elosystem.php';

require('templatesloader.php');
add_action( 'plugins_loaded', array( 'TemplatesLoader', 'get_instance' ) );

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_elosystem() {

	$plugin = new EloSystem();
	$plugin->run();

}
run_elosystem();

function recalculate_rating(){
	global $wpdb;

	$wpdb->query( $wpdb->prepare("UPDATE elplayers SET elo=1000, experience=0 where 1=1"));

	$matches = $wpdb->get_results("
		SELECT m.*,t1.nick as winner,t2.nick as loser
		FROM elpartidas as m
		INNER JOIN elplayers as t1 ON t1.idplayer=m.ganadorid
		INNER JOIN elplayers as t2 ON t2.idplayer=m.perdedorid
		ORDER BY `partidaid` ASC");

	foreach ($matches as $m)
	{
		$p1 = $wpdb->get_row("SELECT * FROM elplayers WHERE idplayer = ".($m->ganadorid),ARRAY_A);
		$p2 = $wpdb->get_row("SELECT * FROM elplayers WHERE idplayer = ".($m->perdedorid),ARRAY_A);
	
		$p1elo = $p1['elo'];
		$p2elo = $p2['elo'];

		$p1exp = ($p1['partidas'])+1;
		$p2exp = ($p2['partidas'])+1;

		$win = $m -> ganadorid;
		$u1 =  1/((pow(10, ($p2elo-$p1elo)/400))+1);
		$finalp1 = $p1elo+30*(1-$u1);
		$winelo = $p1elo;

		$lose = $m -> perdedorid;
		$u2 = 1/((pow(10, ($p1elo-$p2elo)/400))+1);
		$finalp2 = $p2elo+30*(0-$u2);
		$losselo = $p2elo;

		$points = 30*(1-$u1);
		
		$wpdb->query( $wpdb->prepare("update elplayers set elo=%d, partidas=%d where idplayer=%d",
			$finalp1,
			$p1exp,
			($m -> ganadorid)
		));
		$wpdb->query( $wpdb->prepare("update elplayers set elo=%d, partidas=%d where idplayer=%d",
			$finalp2,
			$p2exp,
			($m -> perdedorid)
		));
		$wpdb->query( $wpdb->prepare("update elpartidas set eloganador=%d, eloperdedor=%d, puntos=%d where partidaid=%d",
			$winelo,
			$losselo,
			round($points),
			($m -> partidaid)
		));
	}
}
?>