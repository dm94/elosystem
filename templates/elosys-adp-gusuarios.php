<?php  
/*
Template Name: EloSys-ADPanel-GestionUsuarios
Creado: Daniel "Dm94Dani" Martin
*/

get_header();
global $wpdb;
?>
<?php
	$accion = isset($_POST['accion']) ? $_POST['accion'] : null;
	$dniusu = isset($_POST['dni']) ? $_POST['dni'] : null;
	$nickusu = isset($_POST['nick']) ? $_POST['nick'] : null;
	$emailusu = isset($_POST['email']) ? $_POST['email'] : null;
	$ganadorpartida = isset($_POST['ganador']) ? $_POST['ganador'] : null;
	$perdedorpartida = isset($_POST['perdedor']) ? $_POST['perdedor'] : null;
	$juegopartida = isset($_POST['juego']) ? $_POST['juego'] : null;
	$idpartida = isset($_POST['idpartida']) ? $_POST['idpartida'] : null;
	$idusuario = isset($_POST['idusuario']) ? $_POST['idusuario'] : null;
	$puntosadd = isset($_POST['puntosadd']) ? $_POST['puntosadd'] : null;

	$error = "";

	if($accion=='crearusuario'){
		$aniadido = false;
		$sel = $wpdb->get_results("select * from elplayers where dni='$dniusu' or nick='$nickusu' or email='$emailusu'");
		
		foreach ($sel as $row){
			$aniadido = true;
		}
		
		if ($aniadido) {
			$error = __('No se puede crear el usuario. Ya se esta usando ese Nick, DNI o Email','elosystem');
		} else {
			$wpdb->query( $wpdb->prepare("insert into elplayers(dni,nick,email) values('%s','%s','%s')",
				$dniusu,
				$nickusu,
				$emailusu
			));
		}
	}
	if($accion=='borrarusu'){
		$wpdb->query( $wpdb->prepare("delete from elplayers where idplayer=%d",
			$idusuario
		));
		$wpdb->query( $wpdb->prepare("delete from elpartidas where ganadorid=%d or perdedorid=%d",
			$idusuario,
			$idusuario
		));
	}
	if($accion=='editarusuario'){
		$wpdb->query( $wpdb->prepare("update elplayers set nick='%s', dni='%s', email='%s' where idplayer=%d",
			$nickusu,
			$dniusu,
			$emailusu,
			$idusuario
		));
	}
	if($accion=='addpuntos'){
		$wpdb->query( $wpdb->prepare("update elplayers set eloextra=%d where idplayer=%d",
			$puntosadd,
			$idusuario
		));
	}
?>
<div id="main-content" class="page-panel">

	<div class="container">
		<div id="content-area" class="clearfix">
			<div id="left-area">

			<?php while ( have_posts() ) : the_post(); ?>
			
				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

				<div class="entry-content row">
					<?php
						the_content();
					?>
					<?php
						if (!is_user_logged_in()){
					?>
					<div class="col-lg-12">
						<div class="row">
							<div class="col-lg-12">
								<div class="panel panel-primary">
									<div class="panel-heading">
										<div class="row">
											<div class="col-xs-9 text-left">
												<div class="huge"><?php _e('Necesitas estar logueado y ser admin para entrar aquí','elosystem'); ?></div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<?php
						}else{
					?>
					<div class="col-lg-12">
						<div class="panel panel-primary">
							<div class="panel-heading">
								<div class="row">
									<div class="col-xs-12 text-left">
										<div class="huge"><?php _e('Gestión de usuarios','elosystem'); ?></div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-lg-12">
						<div class="panel panel-primary">
							<div class="panel-heading">
								<div class="row">
									<div class="col-xs-6 text-left">
										<div class="huge"><?php _e('Usuarios','elosystem'); ?></div>
									</div>
									<div class="col-xs-6">
										<form method='POST' action="<?php echo get_permalink(); ?>">
											<label for="nick"><?php _e('Filtrar:','elosystem'); ?></label>
											<input type="hidden" name="accion" value="filtrar">
											<input type="text" name="nick" placeholder="Nick a buscar">
											<input class="btn btn-default" type="submit" value="<?php _e('Filtrar','elosystem'); ?>">
										</form>
									</div>
								</div>
							</div>
							<div class="panel-footer">
								<table>
									<th><?php _e('ID','elosystem'); ?></th><th><?php _e('Nick','elosystem'); ?></th><th><?php _e('Elo (Extra)','elosystem'); ?></th><th><?php _e('G - P','elosystem'); ?></th><th><?php _e('Rating','elosystem'); ?></th><th colspan="2"><?php _e('EloExtra','elosystem'); ?></th><th colspan="2"><?php _e('Acciones','elosystem'); ?></th>
								<?php
									if($accion=='filtrar'){
										$sel = $wpdb->get_results("SELECT * FROM elplayers where nick like '%$nickusu%'");
									}else{
										$sel = $wpdb->get_results("SELECT * FROM elplayers");
									}
									foreach ($sel as $fila) {
										$usuarioid = $fila -> idplayer;
										$nick = $fila -> nick;
										$elo = $fila -> elo;
										$eloextra = $fila -> eloextra;
										
										$wg =  $wpdb->get_row("SELECT COUNT(*) as contador FROM elpartidas WHERE ganadorid = ".$usuarioid,ARRAY_A);
										
										$wins = $wg['contador'];
										
										$lp = $wpdb->get_row("SELECT COUNT(*) as contador FROM elpartidas WHERE perdedorid = ".$usuarioid,ARRAY_A);
										
										$losses = $lp['contador'];
										
										$winrate = ($wins > 0)?(100*number_format($wins/($wins+$losses), 2).'%'):'0%';
										echo "<tr>";
										echo "<td>".$usuarioid."</td>";
										echo "<td>".$nick."</td>";
										echo "<td>".$elo." (".$eloextra.")</td>";
										echo "<td>".$wins." - ".$losses."</td>";
										echo "<td>".$winrate."</td>";
									?>
										<td colspan="2">
											<form method='POST' action="<?php echo get_permalink(); ?>">
												<input type="hidden" name="accion" value="addpuntos">
												<input type="hidden" name="idusuario" value="<?php echo $usuarioid; ?>">
												<input type="number" name="puntosadd" max="9999" maxlength="4" size="4" value="<?php echo $eloextra; ?>">
												<input class="btn btn-default" type="submit" value="<?php _e('Cambiar','elosystem'); ?>">
											</form>
										</td>
										<td>
											<form method='POST' action="<?php echo get_permalink(); ?>">
												<input type="hidden" name="accion" value="borrarusu">
												<input type="hidden" name="idusuario" value="<?php echo $usuarioid; ?>">
												<p class="tml-submit-wrap"><input class="btn btn-default" type="submit" value="<?php _e('Borrar','elosystem'); ?>"></p>
											</form>
										</td>
										<td>
											<form method='POST' action="<?php echo get_permalink(); ?>">
												<input type="hidden" name="accion" value="activarpaneleditar">
												<input type="hidden" name="idusuario" value="<?php echo $usuarioid; ?>">
												<p class="tml-submit-wrap"><input class="btn btn-default" type="submit" value="<?php _e('Editar','elosystem'); ?>"></p>
											</form>
										</td>
									<?php
										echo "</tr>";
									}
								?>
								</table>
							</div> 
						</div>
					</div>
					<div class="col-lg-6">
						<div class="panel panel-primary">
							<div class="panel-heading">
								<div class="row">
									<div class="col-xs-12 text-left">
										<div class="huge"><?php _e('Crear Usuario','elosystem'); ?></div>
									</div>
								</div>
							</div>
							<div class="panel-footer">
								<form method='POST' action="<?php echo get_permalink(); ?>">
									<input type="hidden" name="accion" value="crearusuario">
									<p><strong><?php _e('DNI','elosystem'); ?></strong></p>
									<p><input type="text" name="dni" placeholder="DNI"></p>
									<strong><?php _e('Nick','elosystem'); ?></strong></p>
									<p><input type="text" name="nick" placeholder="Nick"></p>
									<p><strong><?php _e('Email','elosystem'); ?></strong></p>
									<p><input type="email" name="email" placeholder="Email"></p>
									<p class="tml-submit-wrap"><input class="btn btn-default" type="submit" value="<?php _e('Crear','elosystem'); ?>"></p>
								</form>
							</div> 
						</div> 
					</div>
					<?php 
						if($accion=='activarpaneleditar'){
							$sel = $wpdb->get_row("SELECT * FROM elplayers WHERE idplayer='$idusuario' ",ARRAY_A);
							if ($sel) {
					?>
					<div class="col-lg-3">
						<div class="panel panel-primary">
							<div class="panel-heading">
								<div class="row">
									<div class="col-xs-3 text-left">
										<div class="huge"><?php _e('Editar Usuario','elosystem'); ?></div>
									</div>
								</div>
							</div>
							<div class="panel-footer">
								<form method='POST' action="<?php echo get_permalink(); ?>">
									<input type="hidden" name="accion" value="editarusuario">
									<input type="hidden" name="idusuario" value="<?php echo $idusuario; ?>">
									<p><strong><?php _e('DNI','elosystem'); ?></strong></p>
									<p><input type="text" name="dni" value="<?php echo $sel['dni']; ?>"></p>
									<strong><?php _e('Nick','elosystem'); ?></strong></p>
									<p><input type="text" name="nick" value="<?php echo $sel['nick']; ?>"></p>
									<p><strong><?php _e('Email','elosystem'); ?></strong></p>
									<p><input type="email" name="email" value="<?php echo $sel['email'];?>"></p>
									<p class="tml-submit-wrap"><input class="btn btn-default" type="submit" value="<?php _e('Editar','elosystem'); ?>"></p>
								</form>
							</div>
						</div>
					</div>
					<?php
							}
						}
					?>
					
						<?php
							if(!is_null($error) && !empty($error)){
						?>
							<div class="col-lg-12">
								<div class="row">
									<div class="col-lg-12">
										<div class="panel panel-primary">
											<div class="panel-heading">
												<div class="row">
													<div class="col-xs-12 text-left">
														<div class="huge"><?php _e('ERROR','elosystem'); ?></div>
													</div>
												</div>
											</div>
											<div class="panel-footer">
												<div><?php echo $error; ?></div>
											</div> 
										</div>
									</div>
								</div>
							</div>
					<?php
							}
						}
					?>
					
				</div> <!-- .entry-content -->
				</article> <!-- .et_pb_post -->
			<?php endwhile; ?>
		</div> <!-- #content-area -->
	</div> <!-- .container -->

</div> <!-- #main-content -->

<?php get_footer(); ?>