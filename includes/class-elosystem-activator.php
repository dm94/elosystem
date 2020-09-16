<?php

/**
 * Fired during plugin activation
 *
 * @link       https://comunidadgzone.es/
 * @since      1.0.0
 *
 * @package    EloSystem
 * @subpackage EloSystem/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    EloSystem
 * @subpackage EloSystem/includes
 * @author     Daniel Martin <dm94official@hotmail.es>
 */
class EloSystem_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		makeTableELDuelos();
		makeTableELJuegos();
		makeTableELPartidas();
		makeTableELPlayers();
		makeTableELReportes();
	}

}
function makeTableELDuelos() {
		global $wpdb;
		$table_name = $wpdb->prefix."elduelos"; 
		$charset_collate = $wpdb->get_charset_collate();
		$sql = "CREATE TABLE $table_name (
			idduelo int(11) NOT NULL,
			idplayer1 int(10) NOT NULL,
			idplayer2 int(10) NOT NULL,
			mensaje varchar(255) DEFAULT '',
			estado int(1) NOT NULL DEFAULT '0',
			ganadorelep1 int(10) DEFAULT NULL,
			ganadorelep2 int(10) DEFAULT NULL,
			pruebaplayer1 varchar(255) DEFAULT '',
			pruebaplayer2 varchar(255) DEFAULT '',
			idjuego int(11) NOT NULL
			) $charset_collate;";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );
	}
	
	function makeTableELJuegos() {
		global $wpdb;
		$table_name = $wpdb->prefix."eljuegos"; 
		$charset_collate = $wpdb->get_charset_collate();
		$sql = "CREATE TABLE $table_name (
			juegoid int(5) NOT NULL,
			nombre varchar(255) NOT NULL,
			imagen varchar(255) DEFAULT NULL
			) $charset_collate;";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );
	}
	
	function makeTableELPartidas() {
		global $wpdb;
		$table_name = $wpdb->prefix."elpartidas"; 
		$charset_collate = $wpdb->get_charset_collate();
		$sql = "CREATE TABLE $table_name (
			partidaid int(10) NOT NULL,
			ganadorid int(10) NOT NULL,
			perdedorid int(10) NOT NULL,
			juegoid int(5) NOT NULL,
			eloganador int(50) NOT NULL,
			eloperdedor int(50) NOT NULL,
			puntos int(50) NOT NULL
			) $charset_collate;";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );
	}
	
	function makeTableELPlayers() {
		global $wpdb;
		$table_name = $wpdb->prefix."elplayers"; 
		$charset_collate = $wpdb->get_charset_collate();
		$sql = "CREATE TABLE $table_name (
			idplayer int(10) NOT NULL,
			dni varchar(20) DEFAULT NULL,
			nick varchar(255) DEFAULT NULL,
			email varchar(255) DEFAULT NULL,
			elo int(50) NOT NULL DEFAULT '1000',
			partidas int(4) NOT NULL DEFAULT '0',
			eloextra int(50) NOT NULL DEFAULT '0'
			) $charset_collate;";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );
	}
	
	function makeTableELReportes() {
		global $wpdb;
		$table_name = $wpdb->prefix."elreportes"; 
		$charset_collate = $wpdb->get_charset_collate();
		$sql = "CREATE TABLE $table_name (
			idreporte int(11) NOT NULL,
			idreportado int(10) NOT NULL,
			idreportador int(10) NOT NULL,
			motivo varchar(255) DEFAULT NULL,
			captura varchar(1000) DEFAULT NULL,
			estado int(11) DEFAULT '0'
			) $charset_collate;";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );
	}
