<?php  
/*
Template Name: EloSys-PerfilPlayer
Creado: Daniel "Dm94Dani" Martin
*/

get_header();
global $wpdb;
?>
<?php
	$nickusu = addslashes($_GET['nick']);
	$accion = isset($_POST['accion']) ? $_POST['accion'] : null;
	$nickperfil = isset($_POST['nick']) ? $_POST['nick'] : null;
	$idusuretado = isset($_POST['idusuretado']) ? $_POST['idusuretado'] : null;
	$idusuretador = isset($_POST['idusuretador']) ? $_POST['idusuretador'] : null;
	$mensaje = isset($_POST['mensaje']) ? $_POST['mensaje'] : null;
	$imagen = isset($_POST['imagen']) ? $_POST['imagen'] : null;
	$idjuego = isset($_POST['idjuego']) ? $_POST['idjuego'] : null;
	$idreto = isset($_POST['idduelo']) ? $_POST['idduelo'] : null;
	
	$error = "";
	$paginapropia = false;
	$playervalido = false;
	$usuarioid = 0;
	$miusuario = 0;

	$points = 0;
	$signedUp = true;
	
	if (is_user_logged_in()){

		if (get_user_meta(get_current_user_id(),"el_points",true) != null) {
			$points = get_user_meta(get_current_user_id(),"el_points",true);
			$signedUp = true;
		}
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
						if ($signedUp) {
							the_content();		
					?>
						<div class="container">
							<div class="row">
								<div class="col">
									<div class="panel-heading">
										<div class="row">
											<div class="columns text-left">
												<div class="huge"><?php _e('Partidas','elosystem'); ?></div>
											</div>
										</div>
									</div>
									<div class="panel-footer">
										<table class="table">
											<th><?php _e('Ganador (Elo)','elosystem'); ?></th><th><?php _e('Perdedor (Elo)','elosystem'); ?></th><th><?php _e('Juego','elosystem'); ?></th><th><?php _e('Puntos','elosystem'); ?></th>
											<?php /* Aqui mostramos el listado de partidas */ ?>
										</table>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col col-5">
									<div class="panel-heading">
										<div class="row">
											<div class="columns text-left">
												<div class="huge"><?php echo(__('Estadisticas de ','elosystem')."Nick usuario"); ?></div>
											</div>
										</div>
									</div>
									<div class="panel-footer">
										<div class="pull-left">
											<p><strong><?php _e('Elo (Puntuación)','elosystem'); ?></strong></p>
											<p><strong><?php _e('Partidas Totales','elosystem'); ?></strong></p>
											<p><strong><?php _e('Partidas Ganadas','elosystem'); ?></strong></p>
											<p><strong><?php _e('Partidas Perdidas','elosystem'); ?></strong></p>
											<p><strong><?php _e('Winrate','elosystem'); ?></strong></p>
										</div>
										<?php
											/* Datos del usuario */
											$elo = 0;
											$eloextra = 0;
											$wins = 0;
											$losses = 0;
											$winrate = 0;
										?>
										<div class="pull-right">
											<p class="text-right"><?php echo ($elo+$eloextra); ?></p>
											<p class="text-right"><?php echo ($wins+$losses); ?></p>
											<p class="text-right"><?php echo $wins; ?></p>
											<p class="text-right"><?php echo $losses; ?></p>
											<p class="text-right"><?php echo $winrate; ?></p>
										</div>
										<div class="clearfix"></div>
									</div>
								</div>
								<div class="col">
									<div class="panel-heading">
										<div class="row">
											<div class="text-left">
												<div class="huge"><?php _e('Peticiones de Duelos','elosystem'); ?></div>
											</div>
										</div>
									</div>
									<div class="panel-footer">
										<table class="table">
											<th><?php _e('Jugador (Elo)','elosystem'); ?></th>
											<th><?php _e('Juego','elosystem'); ?></th>
											<th colspan="2"><?php _e('Mensaje','elosystem'); ?></th>
										<?php
											/* Duelos pendientes */
										?>
											<tr>
												<td>Test</td>
												<td>CS:GO</td>
												<td colspan="2">Este es un mensaje</td>
											</tr>
											<tr>
												<td class="success" colspan="2">
													<form method='POST' action="<?php echo get_permalink(); ?>">
														<input type="hidden" name="accion" value="aceptarreto">
														<input type="hidden" name="idduelo" value="<?php echo $idduelo; ?>">
														<p class="tml-submit-wrap"><input class="btn btn-default" type="submit" value="<?php _e('Aceptar','elosystem'); ?>"></p>
													</form>
												</td>
												<td class="danger" colspan="2">
													<form method='POST' action="<?php echo get_permalink(); ?>">
														<input type="hidden" name="accion" value="rechazarreto">
														<input type="hidden" name="idduelo" value="<?php echo $idduelo; ?>">
														<p class="tml-submit-wrap"><input class="btn btn-default" type="submit" value="<?php _e('Rechazar','elosystem'); ?>"></p>
													</form>
												</td>
											</tr>
										</table>
									</div>
								</div>
							</div>
  							<div class="row">
								<div class="col">
									<div class="panel-heading">
										<div class="row">
											<div class="text-left">
												<div class="huge"><?php _e('Duelos aceptados','elosystem'); ?></div>
											</div>
										</div>
									</div>
									<div class="panel-footer">
										<table class="table table-bordered">
											<th><?php _e('Jugador (Elo)','elosystem'); ?></th>
											<th><?php _e('Juego','elosystem'); ?></th>
											<th colspan="2"><?php _e('Mensaje','elosystem'); ?></th>
											<td colspan="2"><?php _e('Captura del resultado','elosystem'); ?></td>
										<?php
											
											/* Duelos aceptados y que todavia no han realizado la partida */
										?>
											<tr class="warning">
												<td><?php _e('Jugador (Elo)','elosystem'); ?></td>
												<td><?php _e('Juego','elosystem'); ?></td>
												<td colspan="2">Este es el mensaje</td>
												<td colspan="2">
													<form id='duelosaceptados' method='POST' action="<?php echo get_permalink(); ?>">
														<input type="hidden" name="idduelo" value="idduelo">
														<input class="form-control" type="text" name="imagen" placeholder="<?php _e('Pon la direccion de la imagen','elosystem'); ?>">
													</form>
												</td>
											</tr>
											<tr>
												<td colspan="3" class="success">
													<center><button form="duelosaceptados" class="btn btn-default" type="submit" name="accion" value="dueloganado"><?php _e('He ganado','elosystem'); ?></button></center>
												</td>
												<td colspan="3" class="danger">
													<center><button form="duelosaceptados" class="btn btn-default" type="submit" name="accion" value="dueloperdido"><?php _e('He perdido','elosystem'); ?></button></center>
												</td>
											</tr>
										</table>
									</div>
								</div>
								<div class="col">
									<div class="panel-heading">
										<div class="row">
											<div class="text-left">
												<div class="huge">Perfiles de Juegos</div>
											</div>
										</div>
									</div>
									<div class="panel-footer clearfix">

										<div class="pull-left">
										</div>
										<div class="pull-right">
										</div>
										<div class="pull-center">
										</div>
									</div>
									<div class="panel-footer clearfix">
										<form method='POST' action="<?php echo get_permalink(); ?>">
											<input type="hidden" name="accion" value="crearperfil"/>
											<div class="pull-left">
												<p><label for="juego"><strong>Juego</strong></label></p>
												<p><label for="nick"><strong>Nick</strong></label></p>
											</div>
											<div class="pull-right">
												<p><select name="idjuego" class="select">
												</select></p>
												<p><input type="text" name="nick" class="form-control"/></p>
											</div>
											<div class="clearfix"></div>
											<div class="pull-center">
												<p class="text-center"><button class="btn btn-default" type="submit">Crear</button></p>
											</div>
										</form>
									</div>
								</div>
							</div>
  							<div class="row">
								<div class="col">
									<div class="panel-heading">
										<div class="row">
											<div class="columns text-left">
												<div class="huge">Retar a <?php echo $nickusu; ?></div>
											</div>
										</div>
									</div>
									<div class="panel-footer">
										<form method='POST' action="<?php echo get_permalink().'?nick='.$nickusu; ?>">
											<input type="hidden" name="accion" value="retaraplayer">
											<input type="hidden" name="idusuretado" value="<?php echo $usuarioid; ?>">
											<input type="hidden" name="idusuretador" value="<?php echo $miusuario; ?>">
											<div class="form-group">
												<label for="idjuego">Elige un juego:</label>
												<select name="idjuego" class="form-control">
												</select>
											</div>
											<div class="form-group">
												<label for="mensaje">Mensaje (En el puedes poner tu usuario en ese juego):</label>
												<textarea class="form-control" rows="3" rows="3" cols="65" name="mensaje" maxlength="240"></textarea>
											</div>
											<button class="btn btn-default" type="submit">Retar</button>
										</form>
									</div>
								</div>
								<div class="col">
									<div class="panel-heading">
										<div class="row">
											<div class="columns text-left">
												<div class="huge">Reportar a <?php echo $nickusu; ?></div>
											</div>
										</div>
									</div>
									<div class="panel-footer">
										<form method='POST' action="<?php echo get_permalink().'?nick='.$nickusu; ?>">
											<input type="hidden" name="accion" value="reportar">
											<input type="hidden" name="idusuretado" value="<?php echo $usuarioid; ?>">
											<input type="hidden" name="idusuretador" value="<?php echo $miusuario; ?>">
											<div class="form-group">
												<label for="imagen">Captura (Para facilitar las tareas de administracion)</label>
												<input class="form-control" type="text" name="imagen" placeholder="Pon la direccion de la imagen">
											</div>
											<div class="form-group">
												<label for="mensaje">Motivo (Explica que ha pasado)</label>
												<textarea class="form-control" rows="3" rows="3" cols="65" name="mensaje" maxlength="240" placeholder="No pongas tonterias o el sancionado serás tu"></textarea>
											</div>
											<button class="btn btn-default" type="submit">Reportar</button>
										</form>
									</div>
								</div>
								<div class="col">
									<div class="panel-heading">
										<div class="row">
											<div class="text-left">
												<div class="huge">Perfiles de Juegos</div>
											</div>
										</div>
									</div>
									<div class="panel-footer">
										<div class="pull-left">
										</div>
										<div class="pull-right">
										</div>
										<div class="clearfix"></div>
									</div> 
								</div>
							</div>
						</div>
					<?php						
						}
					?>
					
				</div> <!-- .entry-content -->
				</article> <!-- .et_pb_post -->
			<?php endwhile; ?>
			</div> <!-- #left-area -->
		</div> <!-- #content-area -->
	</div> <!-- .container -->


</div> <!-- #main-content -->

<?php get_footer(); ?>