<?php  
/*
Template Name: EloSys-ADPanel-GestionPartidas
Creado: Daniel "Dm94Dani" Martin
*/

get_header();
global $wpdb;
?>
<?php
	
	global $wpdb;
	$accion=isset($_POST['accion']) ? $_POST['accion'] : null;
	$dniusu=isset($_POST['dni']) ? $_POST['dni'] : null;
	$nickusu=isset($_POST['nick']) ? $_POST['nick'] : null;
	$emailusu=isset($_POST['email']) ? $_POST['email'] : null;
	$ganadorpartida=isset($_POST['ganador']) ? $_POST['ganador'] : null;
	$perdedorpartida=isset($_POST['perdedor']) ? $_POST['perdedor'] : null;
	$juegopartida=isset($_POST['juego']) ? $_POST['juego'] : null;
	$idpartida=isset($_POST['idpartida']) ? $_POST['idpartida'] : null;
	$idusuario=isset($_POST['idusuario']) ? $_POST['idusuario'] : null;
?>

<?php
	if($accion=='crearpartida'){
		
		if($ganadorpartida != $perdedorpartida){
			$wpdb->query( $wpdb->prepare("insert into elpartidas(ganadorid,perdedorid,juegoid) values(%d,%d,%d)",
				$ganadorpartida,
				$perdedorpartida,
				$juegopartida
			));
		}
	}
	
	if($accion=='borrarpartida'){
		$wpdb->query( $wpdb->prepare("delete from elpartidas where partidaid=%d",
			$idpartida
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
										<div class="huge"><?php _e('Gestión de Partidas','elosystem'); ?></div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-lg-6">
						<div class="panel panel-primary">
							<div class="panel-heading">
								<div class="row">
									<div class="col-xs-3 text-left">
										<div class="huge"><?php _e('Crear Partida','elosystem'); ?></div>
									</div>
								</div>
							</div>
							<div class="panel-footer">
								<form method='POST' action="<?php echo get_permalink(); ?>">
									<input type="hidden" name="accion" value="crearpartida">
									<p><strong><?php _e('Ganador','elosystem'); ?></strong></p>
									<p>
										<select name="ganador" class="select">
											<?php 
												$wpdb->get_results("SELECT * FROM elplayers ORDER BY nick ASC");
												foreach ($users as $row)
												{
													echo '<option value="'.$row -> idplayer.'">'.$row -> nick.'('.$row -> elo.')</option>';
												}
											?>
										</select>
									</p>
									<strong><?php _e('Perdedor','elosystem'); ?></strong></p>
									<p>
										<select name="perdedor" class="select">
											<?php 
												foreach ($users as $row)
												{
													echo '<option value="'.$row -> idplayer.'">'.$row -> nick.'('.$row -> elo.')</option>';
												}
											?>
										</select>
									</p>
									<p><strong><?php _e('Juego','elosystem'); ?></strong></p>
									<p>
										<select name="juego" class="select">
											<?php 
												$users = $wpdb->get_results("SELECT * FROM eljuegos ORDER BY nombre ASC");
												foreach ($users as $row)
												{
													echo '<option value="'.$row -> juegoid.'">'.$row -> nombre.'</option>';
												}
											?>
										</select>
									</p>
									<p><button class="btn btn-default" type="submit">Crear</button></p>
								</form>
							</div> 
						</div> 
					</div>
					<div class="col-lg-12">
						<div class="panel panel-primary">
							<div class="panel-heading">
								<div class="row">
									<div class="col-xs-4 text-left">
										<div class="huge"><?php _e('Partidas','elosystem'); ?></div>
									</div>
									<div class="col-xs-5">
										<form method='POST' action="<?php echo get_permalink(); ?>">
												<label for="nick"><?php _e('Filtrar:','elosystem'); ?></label>
												<input type="hidden" name="accion" value="filtrar">
												<input type="text" name="nick" placeholder="<?php _e('Nick a buscar','elosystem'); ?>">
												<input class="btn btn-default" type="submit" value="<?php _e('Filtrar','elosystem'); ?>">
										</form>
									</div>
								</div>
							</div>
							<div class="panel-footer">
								<table>
									<th>ID</th><th><?php _e('Ganador (Elo)','elosystem'); ?></th><th><?php _e('Perdedor (Elo)','elosystem'); ?></th><th><?php _e('Juego','elosystem'); ?></th><th><?php _e('Puntos','elosystem'); ?></th><th><?php _e('Acción','elosystem'); ?></th>
								<?php
									if($accion=='filtrar'){
										$sel = $wpdb->get_results("SELECT m.*,t1.nick as winner,t2.nick as loser
											FROM elpartidas as m
											INNER JOIN elplayers as t1 ON t1.idplayer=m.ganadorid
											INNER JOIN elplayers as t2 ON t2.idplayer=m.perdedorid
											and m.partidaid in (select partidaid from elpartidas as m, elplayers as t1 where t1.nick like '$nickusu' and (m.ganadorid=t1.idplayer or m.perdedorid=t1.idplayer))
											ORDER BY `partidaid` ASC");
										
										foreach ($sel as $fila) {
											$idpartida = $fila -> partidaid;
											$nickganador = $fila -> winner;
											$nickperdedor = $fila -> loser;
											$idjuego = $fila -> juegoid;
											
											$wg = $wpdb->get_row("SELECT nombre,imagen FROM eljuegos WHERE juegoid = ".$idjuego,ARRAY_A);
											$juego = $wg['nombre'];
											$logo = $wg['imagen'];
											
											$eloganador = $fila -> eloganador;
											$eloperdedor = $fila -> eloperdedor;
											$puntos = $fila -> puntos;
											
											echo "<tr>";
											echo "<td>".$idpartida."</td>";
											echo "<td>".$nickganador." (".$eloganador.")</td>";
											echo "<td>".$nickperdedor." (".$eloperdedor.")</td>";
											echo "<td><span title='".$juego."'><img src='".$logo."' height='25px' width='25px'/></span></td>";
											echo "<td>".$puntos."</td>";
											echo "<td>";
									?>
											<form method='POST' action="<?php echo get_permalink(); ?>">
												<input type="hidden" name="accion" value="borrarpartida">
												<input type="hidden" name="idpartida" value="<?php echo $idpartida; ?>">
												<p class="tml-submit-wrap"><input class="btn btn-default" type="submit" value="<?php _e('Borrar','elosystem'); ?>"></p></p>
											</form>
									<?php
											echo "</td>";
											echo "</tr>";
										}
									}else{
										$sel = $wpdb->get_results("SELECT m.*,t1.nick as winner,t2.nick as loser, t3.imagen as logo, t3.nombre as juego
										FROM elpartidas as m
										INNER JOIN elplayers as t1 ON t1.idplayer=m.ganadorid
										INNER JOIN elplayers as t2 ON t2.idplayer=m.perdedorid
										INNER JOIN eljuegos as t3 ON t3.juegoid=m.juegoid
										ORDER BY `partidaid` ASC");
										foreach ($sel as $fila) {
											$idpartida = $fila -> partidaid;
											$nickganador = $fila -> winner;
											$nickperdedor = $fila -> loser;
											$juego = $fila -> juego;
											$logo = $fila -> logo;
											$eloganador = $fila -> eloganador;
											$eloperdedor = $fila -> eloperdedor;
											$puntos = $fila -> puntos;
											
											echo "<tr>";
											echo "<td>".$idpartida."</td>";
											echo "<td>".$nickganador." (".$eloganador.")</td>";
											echo "<td>".$nickperdedor." (".$eloperdedor.")</td>";
											echo "<td><span title='".$juego."'><img src='".$logo."' height='25px' width='25px'/></span></td>";
											echo "<td>".$puntos."</td>";
											echo "<td>";
										?>
											<form method='POST' action="<?php echo get_permalink(); ?>">
												<input type="hidden" name="accion" value="borrarpartida">
												<input type="hidden" name="idpartida" value="<?php echo $idpartida; ?>">
												<p class="tml-submit-wrap"><input class="btn btn-default" type="submit" value="<?php _e('Borrar','elosystem'); ?>"></p></p>
											</form>
									
									<?php
										echo "</td>";
										echo "</tr>";
										}
									}
								?>
								</table>
							</div> 
						</div>
					</div>
						<?php
						}
					?>
					
				</div> <!-- .entry-content -->
				</article> <!-- .et_pb_post -->
			<?php endwhile; ?>
		</div> <!-- #content-area -->
	</div> <!-- .container -->
</div> <!-- #main-content -->

<?php get_footer(); ?>