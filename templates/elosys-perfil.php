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
	
	function comprobarretos($miusuario){
		$con = new mysqli ($db['host'],$db['user'],$db['pass'],$db['dbname']);
		
		$sel = $wpdb->get_row("SELECT * FROM elduelos WHERE (idplayer1=$miusuario or idplayer2=$miusuario) and ganadorelep1 is not null and ganadorelep2 is not null and estado=1",ARRAY_A);
		
		if ($sel) {
			$jugadornum1 = $sel['idplayer1'];
			$jugadornum2 = $sel['idplayer2'];
			$ganadorele1 = $sel['ganadorelep1'];
			$ganadorele2 = $sel['ganadorelep2'];
			$iddeljuego = $sel['idjuego'];
			$idreto = $sel['idduelo'];
			$capuraj1 = $sel['pruebaplayer1'];
			$capuraj2 = $sel['pruebaplayer2'];
						
			if ($ganadorele1 == $ganadorele2) {
				if ($ganadorele1 == $jugadornum1) {
					$wpdb->query( $wpdb->prepare("insert into elpartidas(ganadorid,perdedorid,juegoid) values(%d,%d,%d)",
						$jugadornum1,
						$jugadornum2,
						$iddeljuego
					));
				} else {
					$wpdb->query( $wpdb->prepare("insert into elpartidas(ganadorid,perdedorid,juegoid) values(%d,%d,%d)",
						$jugadornum2,
						$jugadornum1,
						$iddeljuego
					));
				}
			}else{
				$mensajerepor="Duelo con diferentes resultados - ID Duelo: $idreto";
				$capturas="CaptudaP1: ".$capuraj1." - CaptudaP2: ".$capuraj2."";
				$wpdb->query( $wpdb->prepare("insert into elreportes(idreportado,idreportador,motivo,captura,estado) values(%d,%d,'%s','%s',0)",
					$jugadornum1,
					$jugadornum2,
					$mensajerepor,
					$capturas
				));
			}
			$wpdb->query( $wpdb->prepare("update elduelos set estado=3 where idduelo=%d",
				$idreto
			));
		}
	}
	
	if (is_user_logged_in()){
		$cu = wp_get_current_user();
		$email= $cu->user_email;
		
		if(is_null($nickusu) || empty($nickusu)){
			$fila = $wpdb->get_row("SELECT * FROM elplayers WHERE email like '$email'",ARRAY_A);
			if ($fila) {
				$paginapropia = true;
				$nickusu = $fila['nick'];
				$playervalido = true;
				$usuarioid = $fila['idplayer'];
				$miusuario = $usuarioid;
			} else {
				$error = "No estas registrado en el maraton";
			}
		} else {
			$sel = $wpdb->get_row("SELECT * FROM elplayers WHERE nick like '$nickusu'",ARRAY_A);
			if ($sel) {
				$playervalido = true;
				$usuarioid = $sel['idplayer'];
				
				$fila = $wpdb->get_row("SELECT * FROM elplayers WHERE email like '$email'",ARRAY_A);
				if ($fila) {
					$miusuario = $fila['idplayer'];
					if ($usuarioid == $miusuario) {
						$paginapropia = true;
					}
				} else {
					$error = "No estas registrado en el maraton";
				}
			} else {
				$error = "Ese jugador no esta apuntado al maraton";
			}
		}
	}
	if(!is_null($nickusu) || !empty($nickusu)){
		$sel = $wpdb->get_row("SELECT * FROM elplayers WHERE nick like '$nickusu'",ARRAY_A);
		if ($sel) {
			$usuarioid = $sel['idplayer'];
			$playervalido = true;
		} else {
			$error = "Ese jugador no esta apuntado al maraton";
		}
	} else {
		$error = "Ese jugador no esta apuntado al maraton";
	}
?>
<?php
	if ($accion=='retaraplayer') {
		$retocompleto = false;
		$existereto = false;
		
		$sel = $wpdb->get_row("SELECT * FROM elduelos WHERE idplayer1=$idusuretador and idplayer2=$idusuretado and estado=0",ARRAY_A);
		if ($sel) {
			$existereto = true;
			$error = "No puedes retar a este jugador porque tienes un reto con el en espera";
		}
		
		$sel = $wpdb->get_results("select juegoid, count(juegoid) as veces from partidas where (ganadorid=$idusuretador or perdedorid=$idusuretador) and (ganadorid=$idusuretado or perdedorid=$idusuretado) group by juegoid;");
		foreach ($sel as $fila) {
			$veces = $fila -> veces;
			$juegoelegido = $fila -> juegoid;
			if ($veces >= 3 && $juegoelegido == $idjuego) {
				$existereto = true;
				$error = "No puedes retar más veces a este jugador al mismo juego. Maximo 3 duelos por juego y jugador";
			}
		}
		
		if (!$existereto) {
			$wpdb->query( $wpdb->prepare("insert into elduelos(idplayer1,idplayer2,estado,mensaje,idjuego) values(%d,%d,0,'%s',%d)",
				$idusuretador,
				$idusuretado,
				$mensaje,
				$idjuego
			));
			$retocompleto = true;
		}
		
		if ($retocompleto) {
?>		
		<script type="text/javascript">
			alert("El jugador ha sido retado");
		</script>
<?php
		}
	}
	
	if($accion=='reportar'){
		$reportecompleto = false;
		$existereporte = false;
		
		$sel = $wpdb->get_row("SELECT * FROM elreportes WHERE idreportado=$idusuretado and idreportador=$idusuretador",ARRAY_A);
		if ($sel) {
			$existereporte = true;
			$error = "Ya has reportado a este usuario";
		}
		
		if(!$existereporte){
			$wpdb->query( $wpdb->prepare("insert into elreportes(idreportador,idreportado,estado,motivo,captura) values(%d,%d,0,'%s','%s')",
				$idusuretador,
				$idusuretado,
				$mensaje,
				$imagen
			));
			$retocompleto = true;
		}
		
		if($retocompleto){
?>		
		<script type="text/javascript">
			alert("El jugador ha sido reportado");
		</script>
<?php
		}
	}
	
	if($accion=='aceptarreto'){
		$wpdb->query( $wpdb->prepare("update elduelos set estado=1 where idduelo=%d",
			$idreto
		));
	}	
	if($accion=='rechazarreto'){
		$wpdb->query( $wpdb->prepare("update elduelos set estado=2 where idduelo=%d",
			$idreto
		));
	}
	if($accion=='dueloganado'){
		$sel = $wpdb->get_row("SELECT * FROM elduelos WHERE idplayer1='$miusuario' and idduelo=$idreto",ARRAY_A);
		if ($sel) {
			$wpdb->query( $wpdb->prepare("update elduelos set ganadorelep1=%d, pruebaplayer1='%s' where idduelo=%s",
				$miusuario,
				$imagen,
				$idreto
			));
		}else{
			$wpdb->query( $wpdb->prepare("update elduelos set ganadorelep2=%d, pruebaplayer2='%s' where idduelo=%s",
				$miusuario,
				$imagen,
				$idreto
			));
		}
		comprobarretos($miusuario);
	}
	if($accion=='dueloperdido'){
		$sel = $wpdb->get_row("SELECT * FROM elduelos WHERE idplayer1='$miusuario' and idduelo=$idreto",ARRAY_A);
		if ($sel) {
			$idotro = $sel['idplayer2'];
			$wpdb->query( $wpdb->prepare("update elduelos set ganadorelep1=%d, pruebaplayer1='%s' where idduelo=%d",
				$idotro,
				$imagen,
				$idreto
			));
		}else{
			$sel2 = $wpdb->get_row("SELECT * FROM elduelos WHERE idplayer2='$miusuario' and idduelo=$idreto",ARRAY_A);
			if ($sel2) {
				$idotro = $sel2['idplayer1'];
				$wpdb->query( $wpdb->prepare("update elduelos set ganadorelep2=%d, pruebaplayer2='%s' where idduelo=%d",
					$idotro,
					$imagen,
					$idreto
				));
			}
		}
		comprobarretos($miusuario);
	}
	if ($accion=='crearperfil') {
		/*$wpdb->query( $wpdb->prepare("insert into elcuentasjuego values (%s,%d,%s)",
			$miusuario,
			$idjuego,
			$nickinse
		));*/
	}
	if ($accion=='borrarperfil') {
		/*$wpdb->query( $wpdb->prepare("delete from elcuentasjuego where idplayer=%d and juegoid=%d",
			$miusuario,
			$idjuego
		));*/
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
							if($playervalido){
								
							
					?>
							<div class="col-xs-6">
								<div class="panel panel-primary">
									<div class="panel-heading">
										<div class="row">
											<div class="col-xs-6 text-left">
												<div class="huge"><?php _e('Partidas','elosystem'); ?></div>
											</div>
										</div>
									</div>
									<div class="panel-footer">
										<table class="table">
											<th><?php _e('Ganador (Elo)','elosystem'); ?></th><th><?php _e('Perdedor (Elo)','elosystem'); ?></th><th><?php _e('Juego','elosystem'); ?></th><th><?php _e('Puntos','elosystem'); ?></th>
										<?php
											$sel = $wpdb->get_results("SELECT m.*,t1.nick as winner,t2.nick as loser
												FROM partidas as m
												INNER JOIN elplayers as t1 ON t1.idplayer=m.ganadorid
												INNER JOIN elplayers as t2 ON t2.idplayer=m.perdedorid
												and m.partidaid in (select partidaid from partidas as m, elplayers as t1 where m.ganadorid=$usuarioid or m.perdedorid=$usuarioid)
												ORDER BY `partidaid` ASC");
											$haypartidas = false;
											
											foreach ($sel as $fila) {
												$nickganador = $fila -> winner;
												$nickperdedor = $fila -> loser;
												$idjuego = $fila -> juegoid;
												
												$wg = $wpdb->get_row("SELECT nombre,imagen FROM eljuegos WHERE juegoid = ".$idjuego,ARRAY_A);
												
												$juego = $wg['nombre'];
												$logo = $wg['imagen'];
												$eloganador = $fila -> eloganador;
												$eloperdedor = $fila -> eloperdedor;
												$puntos = $fila -> puntos;
												$haypartidas = true;
												if ($nickganador == $nickusu) {
													echo '<tr class="success">';
												} else {
													echo '<tr class="danger">';
												}
												echo '<td>'.$nickganador.' ('.$eloganador.')</td>';
												echo "<td>".$nickperdedor." (".$eloperdedor.")</td>";
												echo "<td><span title='".$juego."'><img src='".$logo."' height='25px' width='25px'/></span></td>";
												echo "<td>".$puntos."</td>";
												echo "</tr>";
											}
											if (!$haypartidas) {
												echo "<tr><td colspan='4'><center>No hay partidas</center></td></tr>";
											}
										?>
										</table>
									</div> 
								</div>
							</div>
							<div class="col-xs-6">
								<div class="panel panel-primary">
									<div class="panel-heading">
										<div class="row">
											<div class="col-xs-6 text-left">
												<div class="huge"><?php echo(__('Estadisticas de ','elosystem').$nickusu); ?></div>
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
											$fila = $wpdb->get_row("SELECT * FROM elplayers where nick like '$nickusu'",ARRAY_A);
											$elo = 0;
											$eloextra = 0;
											$wins = 0;
											$losses = 0;
											$winrate = 0;
											if($fila) {
												$elo = $fila['elo'];
												$eloextra = $fila['eloextra'];
												
												$wg = $wpdb->get_row("SELECT COUNT(*) as contador FROM partidas WHERE ganadorid = ".$usuarioid,ARRAY_A);
												$wins = $wg['contador'];
												
												$lp = $wpdb->get_row("SELECT COUNT(*) as contador FROM partidas WHERE perdedorid = ".$usuarioid,ARRAY_A);
												$losses = $lp['contador'];
												$winrate = ($wins > 0)?(100*number_format($wins/($wins+$losses), 2).'%'):'0%';
											}											
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
							</div>
						<?php
								if($paginapropia){
								
							
						?>
						
								<div class="col-xs-6">
									<div class="panel panel-primary">
										<div class="panel-heading">
											<div class="row">
												<div class="col-xs-6 text-left">
													<div class="huge"><?php _e('Peticiones de Duelos','elosystem'); ?></div>
												</div>
											</div>
										</div>
										<div class="panel-footer">
											<table class="table">
												<th><?php _e('Jugador (Elo)','elosystem'); ?></th><th><?php _e('Juego','elosystem'); ?></th><th colspan="2"><?php _e('Mensaje','elosystem'); ?></th>
											<?php
												  
												$sel = $wpdb->get_results("SELECT m.idduelo as dueloid,m.idjuego as juegoid, m.mensaje as mensajeduelo,t1.nick as j1duelo, t1.elo as eloj1
												  FROM elplayers as t1, elduelos as m
												  where m.idplayer2=$miusuario and estado=0
												  group by 'idduelo'
												  ORDER BY `idduelo` ASC");
												$hayretos = false;
												
												foreach ($sel as $fila) {
													$idduelo = $fila -> dueloid;
													$nickj1 = $fila -> j1duelo;
													$idjuego = $fila -> juegoid;
													$eloj1 = $fila -> eloj1;
													$mensaje = $fila -> mensajeduelo;
													
													$wg = $wpdb->get_row("SELECT nombre,imagen FROM eljuegos WHERE juegoid = ".$idjuego,ARRAY_A);
													
													$juego = $wg['nombre'];
													$logo = $wg['imagen'];
													$hayretos = true;
													echo '<tr class="warning">';
													echo '<td>'.$nickj1.' ('.$eloj1.')</td>';
													echo "<td><span title='".$juego."'><img src='".$logo."' height='25px' width='25px'/></span></td>";
													echo '<td colspan="2" rowspan="2">'.$mensaje.'</td>';
													echo "</tr>";
												
											?>
												<tr><td class="success">
												<form method='POST' action="<?php echo get_permalink(); ?>">
													<input type="hidden" name="accion" value="aceptarreto">
													<input type="hidden" name="idduelo" value="<?php echo $idduelo; ?>">
													<p class="tml-submit-wrap"><input class="btn btn-default" type="submit" value="<?php _e('Aceptar','elosystem'); ?>"></p>
												</form>
												</td>
												<td class="danger">
												<form method='POST' action="<?php echo get_permalink(); ?>">
													<input type="hidden" name="accion" value="rechazarreto">
													<input type="hidden" name="idduelo" value="<?php echo $idduelo; ?>">
													<p class="tml-submit-wrap"><input class="btn btn-default" type="submit" value="<?php _e('Rechazar','elosystem'); ?>"></p>
												</form>
												</td></tr>
												<?php
											
												}
												if(!$hayretos){
													echo "<tr><td colspan='4'><center>No hay duelos pendientes</center></td></tr>";
												}
											?>
											</table>
										</div>
									</div>
								</div>
								<div class="col-xs-6">
									<div class="panel panel-primary">
										<div class="panel-heading">
											<div class="row">
												<div class="col-xs-6 text-left">
													<div class="huge"><?php _e('Duelos aceptados','elosystem'); ?></div>
												</div>
											</div>
										</div>
										<div class="panel-footer">
											<table class="table table-bordered">
												<th><?php _e('Jugador (Elo)','elosystem'); ?></th><th><?php _e('Juego','elosystem'); ?></th><th colspan="2"><?php _e('Mensaje','elosystem'); ?></th>
											<?php
												$hayretos = false;
												
												$sel = $wpdb->get_results("SELECT m.idduelo as dueloid,m.idjuego as juegoid, m.mensaje as mensajeduelo,t1.nick as j1duelo, t1.elo as eloj1
												  FROM elplayers as t1, elduelos as m
												  where m.idplayer2=$miusuario and estado=1 and ganadorelep2 is NULL
												  group by 'idduelo'
												  ORDER BY `idduelo` ASC");
												
												foreach ($sel as $fila) {
													$idduelo = $fila -> dueloid;
													$nickj1 = $fila -> j1duelo;
													$idjuego = $fila -> juegoid;
													$eloj1 = $fila -> eloj1;
													$mensaje = $fila -> mensajeduelo;
													
													$wg = $wpdb->get_row("SELECT nombre,imagen FROM eljuegos WHERE juegoid = ".$idjuego,ARRAY_A);
													
													$juego = $wg['nombre'];
													$logo = $wg['imagen'];
													$hayretos = true;
													echo '<tr class="warning">';
													echo '<td>'.$nickj1.' ('.$eloj1.')</td>';
													echo "<td><span title='".$juego."'><img src='".$logo."' height='25px' width='25px'/></span></td>";
													echo '<td colspan="2" rowspan="2">'.$mensaje.'</td>';
													echo "</tr>";
												
											?>
												<tr class="warning">
													<td colspan="2">
														<p><strong><?php _e('Captura del resultado','elosystem'); ?></strong></p>
													</td>
												</tr>
												<tr class="warning">
													<td colspan="4">
														<form id='duelosaceptados' method='POST' action="<?php echo get_permalink(); ?>">
															<input type="hidden" name="idduelo" value="<?php echo $idduelo; ?>">
															<input class="form-control" type="text" name="imagen" placeholder="<?php _e('Pon la direccion de la imagen','elosystem'); ?>">
														</form>
													</td>
												</tr>
												<tr><td colspan="2" class="success">
													<center><button form="duelosaceptados" class="btn btn-default" type="submit" name="accion" value="dueloganado"><?php _e('He ganado','elosystem'); ?></button></center>
												</td>
												<td colspan="2" class="danger">
													<center><button form="duelosaceptados" class="btn btn-default" type="submit" name="accion" value="dueloperdido"><?php _e('He perdido','elosystem'); ?></button></center>
												</td></tr>
												<?php
											
												}
												
												$sel = $con -> query ("SELECT m.idduelo as dueloid,m.idjuego as juegoid, m.mensaje as mensajeduelo,t1.nick as j1duelo, t1.elo as eloj1
												  FROM elplayers as t1, elduelos as m
												  where m.idplayer1=$miusuario and estado=1 and ganadorelep1 is NULL
												  group by 'idduelo'
												  ORDER BY `idduelo` ASC");
												
												while ($fila = $sel -> fetch_assoc()) {
													$idduelo=$fila['dueloid'];
													$nickj1=$fila['j1duelo'];
													$idjuego=$fila['juegoid'];
													$eloj1=$fila['eloj1'];
													$mensaje=$fila['mensajeduelo'];
													$qg = $con->query("SELECT nombre,imagen FROM eljuegos WHERE juegoid = ".$idjuego);
													$wg = $qg -> fetch_assoc();
													
													$juego = $wg['nombre'];
													$logo=$wg['imagen'];
													$hayretos=true;
													echo '<tr class="warning">';
													echo '<td>'.$nickj1.' ('.$eloj1.')</td>';
													echo "<td><span title='".$juego."'><img src='".$logo."' height='25px' width='25px'/></span></td>";
													echo '<td colspan="2" rowspan="2">'.$mensaje.'</td>';
													echo "</tr>";
												
											?>
												<tr class="warning">
													<td colspan="2">
														<p><strong>Captura del resultado</strong></p>
													</td>
												</tr>
												<tr class="warning">
													<td colspan="4">
														<form id='duelosaceptados' method='POST' action="<?php echo get_permalink(); ?>">
															<input type="hidden" name="idduelo" value="<?php echo $idduelo; ?>">
															<input class="form-control" type="text" name="imagen" placeholder="Pon la direccion de la imagen">
														</form>
													</td>
												</tr>
												<tr><td colspan="2" class="success">
													<center><button form="duelosaceptados" class="btn btn-default" type="submit" name="accion" value="dueloganado">He ganado</button></center>
												</td>
												<td colspan="2" class="danger">
													<center><button form="duelosaceptados" class="btn btn-default" type="submit" name="accion" value="dueloperdido">He perdido</button></center>
												</td></tr>
												<?php
											
												}
												if(!$hayretos){
													echo "<tr><td colspan='4'><center>No hay duelos pendientes de resultados</center></td></tr>";
												}
												mysqli_close($con);
											?>
											</table>
										</div>
									</div>
								</div>
								<div class="col-xs-6">
									<div class="panel panel-primary">
										<div class="panel-heading">
											<div class="row">
												<div class="col-xs-6 text-left">
													<div class="huge">Perfiles de Juegos</div>
												</div>
											</div>
										</div>
										<div class="panel-footer clearfix">
											<?php
												$con = new mysqli ($db['host'],$db['user'],$db['pass'],$db['dbname']);
												$sql="select nombre,nick,cuentasjuego.juegoid,cuentasjuego.idplayer from cuentasjuego, eljuegos where cuentasjuego.juegoid=eljuegos.juegoid and cuentasjuego.idplayer=$miusuario;";
												$col=0;
												if(!$res = mysqli_query($con, $sql)) die(); //si la conexión cancelar programa
													while ($row = mysqli_fetch_array($res)){
														$client_data[$col] = array(
															'nombre' =>	$row[0],
															'nick'	=>	$row[1],
															'idjuego'	=>	$row[2],
															'idplayer'	=>	$row[3]
														);
														$col++;
													} 
												mysqli_free_result($res);
												mysqli_close($con);
											?>
											<div class="pull-left">
											<?php
												foreach ($client_data as $juego){
													echo '<p><strong>';
														echo $juego['nombre'];
													echo '</strong></p>';
												}
											?>	
											</div>
											<div class="pull-right">
											<?php
												foreach ($client_data as $juego){
											?>
												<form method='POST' action="<?php echo get_permalink(); ?>">
													<input type="hidden" name="accion" value="borrarperfil"/>
													<input type="hidden" name="idjuego" value="<?php echo $juego['idjuego']; ?>"/>
													<p class="tml-submit-wrap"><input class="btn btn-default" type="submit" value="Borrar"></p>
												</form>
											<?php
												}
											?>
											</div>
											<div class="pull-center">
											<?php
												foreach ($client_data as $juego){
													echo '<p class="text-center">';
														echo $juego['nick'];
													echo '</p>';
												}
											?>
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
													<?php
														$con = new mysqli ($db['host'],$db['user'],$db['pass'],$db['dbname']);
														$sql="select nombre,juegoid from eljuegos where juegoid not in (select juegoid from cuentasjuego where idplayer=$miusuario);";
														if(!$res = mysqli_query($con, $sql)) die(); //si la conexión cancelar programa
															foreach ($res as $row)
															{
																$idjuego=$row['juegoid'];
																$nombre=$row['nombre'];
																echo '<option value="'.$idjuego.'">'.$nombre.'</option>';
															}
														mysqli_free_result($res);
														mysqli_close($con);
													?>
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
						<?php
								}else{
						?>		
								<div class="col-xs-6">
									<div class="panel panel-primary">
										<div class="panel-heading">
											<div class="row">
												<div class="col-xs-6 text-left">
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
														<?php 
															$con = new mysqli ($db['host'],$db['user'],$db['pass'],$db['dbname']);
															$users = $con->query("SELECT * FROM eljuegos ORDER BY nombre ASC");
															foreach ($users as $row)
															{
																$idjuego=$row['juegoid'];
																$nombre=$row['nombre'];
																echo '<option value="'.$idjuego.'">'.$nombre.'</option>';
															}
															mysqli_close($con);
														?>
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
								</div>
								<div class="col-xs-6">
									<div class="panel panel-primary">
										<div class="panel-heading">
											<div class="row">
												<div class="col-xs-6 text-left">
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
								</div>
								<div class="col-xs-6">
									<div class="panel panel-primary">
										<div class="panel-heading">
											<div class="row">
												<div class="col-xs-6 text-left">
													<div class="huge">Perfiles de Juegos</div>
												</div>
											</div>
										</div>
										<div class="panel-footer">
											<?php
												$con = new mysqli ($db['host'],$db['user'],$db['pass'],$db['dbname']);
												$sql="select nombre,nick from cuentasjuego, eljuegos where cuentasjuego.juegoid=eljuegos.juegoid and cuentasjuego.idplayer=$usuarioid;";
												$col=0;
												if(!$res = mysqli_query($con, $sql)) die(); //si la conexión cancelar programa
													while ($row = mysqli_fetch_array($res)){
														$client_data[$col] = array(
															'nombre' =>	$row[0],
															'nick'	=>	$row[1]
														);
														$col++;
													} 
												mysqli_free_result($res);
												mysqli_close($con);
											?>
											<div class="pull-left">
											<?php
												foreach ($client_data as $juego){
													echo '<p><strong>';
														echo $juego['nombre'];
													echo '</strong></p>';
												}
											?>	
											</div>
											<div class="pull-right">
											<?php
												foreach ($client_data as $juego){
													echo '<p class="text-right">';
														echo $juego['nick'];
													echo '</p>';
												}
											?>
											</div>
											<div class="clearfix"></div>
										</div> 
									</div>
								</div>
						<?php						
								}
							}
							if(!is_null($error) && !empty($error)){
						?>
							<div class="col-xs-12">
								<div class="row">
									<div class="col-xs-12">
										<div class="panel panel-primary">
											<div class="panel-heading">
												<div class="row">
													<div class="col-xs-12 text-left">
														<div class="huge">ERROR</div>
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
					?>
					
				</div> <!-- .entry-content -->
				</article> <!-- .et_pb_post -->
			<?php endwhile; ?>
			</div> <!-- #left-area -->
		</div> <!-- #content-area -->
	</div> <!-- .container -->


</div> <!-- #main-content -->

<?php get_footer(); ?>