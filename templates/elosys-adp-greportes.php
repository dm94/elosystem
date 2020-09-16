<?php  
/*
Template Name: EloSys-ADPanel-GestionReportes
Creado: Daniel "Dm94Dani" Martin
*/

get_header();
global $wpdb;
?>
<?php
	$accion = isset($_POST['accion']) ? $_POST['accion'] : null;
	$idreportesol = isset($_POST['idreporte']) ? $_POST['idreporte'] : null;
	
	if($accion=='reportearreglado'){
		$wpdb->query( $wpdb->prepare("update elreportes set estado=1 where idreporte=%d",
			$idreportesol
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
										<div class="huge"><?php _e('Gestión de Reportes','elosystem'); ?></div>
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
										<div class="huge"><?php _e('Reportes Pendientes','elosystem'); ?></div>
									</div>
								</div>
							</div>
							<div class="panel-footer">
								<table>
									<th><?php _e('ID Reporte','elosystem'); ?></th><th><?php _e('Jugador 1 (Reportado)','elosystem'); ?></th><th><?php _e('Jugador 2','elosystem'); ?></th><th colspan="2"><?php _e('Motivo','elosystem'); ?></th><th><?php _e('Captura','elosystem'); ?></th><th><?php _e('Acciones','elosystem'); ?></th>
								<?php
									
									$sel = $wpdb->get_results("SELECT * FROM elreportes where estado=0");
									
									foreach ($sel as $fila) {
										$idreporte = $fila -> idreporte;
										$idreportado = $fila -> idreportado;
										$idreportador = $fila -> idreportador;
										$motivo = $fila -> motivo;
										$captura = $fila -> captura;

										echo "<tr>";
										echo "<td>".$idreporte."</td>";
										echo "<td>".$idreportado."</td>";
										echo "<td>".$idreportador."</td>";
										echo "<td colspan='2'>".$motivo."</td>";
										echo "<td>".$captura."</td>";
									?>
										<td>
											<form method='POST' action="<?php echo get_permalink(); ?>">
												<input type="hidden" name="accion" value="reportearreglado">
												<input type="hidden" name="idreporte" value="<?php echo $idreporte; ?>">
												<p class="tml-submit-wrap"><input class="btn btn-default" type="submit" value="<?php _e('Solucionado','elosystem'); ?>"></p>
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
					<div class="col-lg-12">
						<div class="panel panel-primary">
							<div class="panel-heading">
								<div class="row">
									<div class="col-xs-6 text-left">
										<div class="huge"><?php _e('Reportes Solucionados','elosystem'); ?></div>
									</div>
								</div>
							</div>
							<div class="panel-footer">
								<table>
									<th><?php _e('ID Reporte','elosystem'); ?></th><th><?php _e('Jugador 1 (Reportado)','elosystem'); ?></th><th><?php _e('Jugador 2','elosystem'); ?></th><th colspan="2"><?php _e('Motivo','elosystem'); ?></th><th><?php _e('Captura','elosystem'); ?></th>
								<?php
									$sel = $wpdb->get_results("SELECT * FROM elreportes where estado=1");
									
									foreach ($sel as $fila) {
										$idreporte = $fila -> idreporte;
										$idreportado = $fila -> idreportado;
										$idreportador = $fila -> idreportador;
										$motivo = $fila -> motivo;
										$captura = $fila -> captura;

										echo "<tr>";
										echo "<td>".$idreporte."</td>";
										echo "<td>".$idreportado."</td>";
										echo "<td>".$idreportador."</td>";
										echo "<td colspan='2'>".$motivo."</td>";
										echo "<td>".$captura."</td>";
										echo "</tr>";
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