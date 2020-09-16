<?php

/*
Template Name: EloSys-Ranking
Creado: Daniel "Dm94Dani" Martin
*/

get_header();

$haymas=false;
$haymasde50=false;
global $wpdb;
?>
<?php
	
	$urlperfil='http://comunidadgzone.es/maraton/perfil/';

	$col=0;
	
	$sel = $wpdb->get_results("select nick, (elo+eloextra) as total from elplayers order by total DESC;");
	foreach ($sel as $row) {
		if($col<10){
			$client_data[$col] = array(
				'nick'		=>	$row[0],
				'elo'	=>	$row[1],
				'link'	=>	$urlperfil."?nick=".$row[0]
			);
		}else if($col>10 && $col<50){
			$otros[$col] = array(
				'nick'		=>	$row[0],
				'elo'	=>	$row[1],
				'link'	=>	$urlperfil."?nick=".$row[0]
			);
			$haymas=true;
		}else{
			$mas50[$col] = array(
				'nick'		=>	$row[0],
				'elo'	=>	$row[1],
				'link'	=>	$urlperfil."?nick=".$row[0]
			);
			$haymasde50=true;
		}
		$col++;
	}
?>
<div id="main-content">

	<div class="container">
		<div id="content-area" class="clearfix">
			<div id="left-area">

			<?php while ( have_posts() ) : the_post(); ?>
			
				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

					<div class="entry-content">
					<?php
						the_content();
					?>	
						<div id="page-wrapper">
							<div class="container-fluid">
													<div class="row">
															<div class="col-lg-6 col-lg-offset-3">
																	<div class="panel panel-primary">
																			<div class="panel-heading">
																					<div class="row">
																							<div class="col-xs-3">
																									<p class="text-center"><i>#1º</i></p>
																									<p class="text-center"><i class="fa fa-trophy fa-5x"></i></p>
																							</div>
																							<div class="col-xs-9 text-right">
																									<div>&nbsp;</div>
																									<div class="tophuge"><a href="<?PHP echo $client_data[0]['link']; ?>"><span style="color:white" title=<?PHP echo $client_data[0]['nick']; ?>>
																									<?PHP echo $client_data[0]['nick']; ?></span></a></div>
																									<div><?PHP echo sprintf("Elo: ".$client_data[0]['elo']); ?></div>
																							</div>
																					</div>
																			</div>
																	</div>
															</div>
													</div>
													<div class="row">
															<div class="col-lg-4 col-lg-offset-2">
																	<div class="panel panel-green">
																			<div class="panel-heading">
																					<div class="row">
																							<div class="col-xs-3">
																									<p class="text-center"><i>#2º</i></p>
																									<p class="text-center"><i class="fa fa-trophy fa-5x"></i></p>
																							</div>
																							<div class="col-xs-9 text-right">
																								  <div>&nbsp;</div>
																								  <div class="tophuge"><a href="<?PHP echo $client_data[1]['link']; ?>"><span style="color:white" title=<?PHP echo $client_data[1]['nick']; ?>>
																									<?PHP echo $client_data[1]['nick']; ?></span></a></div>
																									<div><?PHP echo sprintf("Elo: ".$client_data[1]['elo']); ?></div>
																							</div>
																					</div>
																			</div>
																	</div>
															</div>
															<div class="col-lg-4">
																	<div class="panel panel-yellow">
																			<div class="panel-heading">
																					<div class="row">
																							<div class="col-xs-3">
																									<p class="text-center"><i>#3º</i></p>
																									<p class="text-center"><i class="fa fa-trophy fa-5x"></i></p>
																							</div>
																							<div class="col-xs-9 text-right">
																									<div>&nbsp;</div>
																									<div class="tophuge"><a href="<?PHP echo $client_data[2]['link']; ?>"><span style="color:white" title=<?PHP echo $client_data[2]['nick']; ?>>
																									<?PHP echo $client_data[2]['nick']; ?></span></a></div>
																									<div><?PHP echo sprintf("Elo: ".$client_data[2]['elo']); ?></div>
																							</div>
																					</div>
																			</div>
																	</div>
															</div>
													</div>
													<div class="row">
															<div class="col-lg-4">
																	<div class="panel panel-red">
																			<div class="panel-heading">
																					<div class="row">
																							<div class="col-xs-3">
																									<i class="fa-2x">#4º</i>
																							</div>
																							<div class="col-xs-9 text-right">
																								 <div class="tophuge"><a href="<?PHP echo $client_data[3]['link']; ?>"><span style="color:white" title=<?PHP echo $client_data[3]['nick']; ?>>
																									<?PHP echo $client_data[3]['nick']; ?></span></a></div>
																									<div><?PHP echo sprintf("Elo: ".$client_data[3]['elo']); ?></div>
																							</div>
																					</div>
																			</div>
																	</div>
															</div>
															<div class="col-lg-4">
																	<div class="panel panel-red">
																			<div class="panel-heading">
																					<div class="row">
																							<div class="col-xs-3">
																									<i class="fa-2x">#5º</i>
																							</div>
																							<div class="col-xs-9 text-right">
																									<div class="tophuge"><a href="<?PHP echo $client_data[4]['link']; ?>"><span style="color:white" title=<?PHP echo $client_data[4]['nick']; ?>>
																									<?PHP echo $client_data[4]['nick']; ?></span></a></div>
																									<div><?PHP echo sprintf("Elo: ".$client_data[4]['elo']); ?></div>
																							</div>
																					</div>
																			</div>
																	</div>
															</div>
															<div class="col-lg-4">
																	<div class="panel panel-red">
																			<div class="panel-heading">
																					<div class="row">
																							<div class="col-xs-3">
																									<i class="fa-2x">#6º</i>
																							</div>
																							<div class="col-xs-9 text-right">
																								  <div class="tophuge"><a href="<?PHP echo $client_data[6]['link']; ?>"><span style="color:white" title=<?PHP echo $client_data[5]['nick']; ?>>
																									<?PHP echo $client_data[5]['nick']; ?></span></a></div>
																									<div><?PHP echo sprintf("Elo: ".$client_data[5]['elo']); ?></div>
																							</div>
																					</div>
																			</div>
																	</div>
															</div>
													</div>
													<div class="row">
															<div class="col-lg-3">
																	<div class="panel panel-red">
																			<div class="panel-heading">
																					<div class="row">
																							<div class="col-xs-3">
																									<div style="line-height:90%;">
																											<br>
																									</div>
																									<i class="fa-2x">#7º</i>
																							</div>
																							<div class="col-xs-9 text-right">
																								   <div class="tophuge"><a href="<?PHP echo $client_data[6]['link']; ?>"><span style="color:white" title=<?PHP echo $client_data[6]['nick']; ?>>
																									<?PHP echo $client_data[6]['nick']; ?></span></a></div>
																									<div><?PHP echo sprintf("Elo: ".$client_data[6]['elo']); ?></div>
																							</div>
																					</div>
																			</div>
																	</div>
															</div>
															<div class="col-lg-3">
																	<div class="panel panel-red">
																			<div class="panel-heading">
																					<div class="row">
																							<div class="col-xs-3">
																									<div style="line-height:90%;">
																											<br>
																									</div>
																									<i class="fa-2x">#8º</i>
																							</div>
																							<div class="col-xs-9 text-right">
																								  <div class="tophuge"><a href="<?PHP echo $client_data[7]['link']; ?>"><span style="color:white" title=<?PHP echo $client_data[7]['nick']; ?>>
																									<?PHP echo $client_data[7]['nick']; ?></span></a></div>
																									<div><?PHP echo sprintf("Elo: ".$client_data[7]['elo']); ?></div>
																							</div>
																					</div>
																			</div>
																	</div>
															</div>
															<div class="col-lg-3">
																	<div class="panel panel-red">
																			<div class="panel-heading">
																					<div class="row">
																							<div class="col-xs-3">
																									<div style="line-height:90%;">
																											<br>
																									</div>
																									<i class="fa-2x">#9º</i>
																							</div>
																							<div class="col-xs-9 text-right">
																								  <div class="tophuge"><a href="<?PHP echo $client_data[8]['link']; ?>"><span style="color:white" title=<?PHP echo $client_data[8]['nick']; ?>>
																									<?PHP echo $client_data[8]['nick']; ?></span></a></div>
																									<div><?PHP echo sprintf("Elo: ".$client_data[8]['elo']); ?></div>
																							</div>
																					</div>
																			</div>
																	</div>
															</div>
															<div class="col-lg-3">
																	<div class="panel panel-red">
																			<div class="panel-heading">
																					<div class="row">
																							<div class="col-xs-3">
																									<div style="line-height:90%;">
																											<br>
																									</div>
																									<i class="fa-2x">#10º</i>
																							</div>
																							<div class="col-xs-9 text-right">
																									<div class="tophuge"><a href="<?PHP echo $client_data[9]['link']; ?>"><span style="color:white" title=<?PHP echo $client_data[9]['nick']; ?>>
																									<?PHP echo $client_data[9]['nick']; ?></span></a></div>
																									<div><?PHP echo sprintf("Elo: ".$client_data[9]['elo']); ?></div>
																							</div>
																					</div>
																			</div>
																	</div>
															</div>
													</div>
													<?php
													if($haymas){
													?>	
													<div class="col-lg-6">
														<div class="panel panel-primary">
																	<div class="panel-heading">
																			<div class="row">
																					<div class="col-xs-6 text-left">
																							<div class="huge">
																									<small>TOP del 11 al <?php echo (10+count($otros))?></small>
																							</div>
																					</div>
																			</div>
																	</div>
																	<div class="panel-footer">
																		<?php
																			$mitad =round(count($otros)/2);
																			$numero=0;
																			echo '<div class="pull-left">';
																			foreach ($otros as $players){
																				if($mitad==$numero){
																					echo '</div>';
																					echo '<div class="pull-right">';
																				}
																				if($mitad<=$numero){
																					echo "<p><a href='".$players['link']."'>".$players['nick'] ." - Elo: ".$players['elo']."</a></p>";
																				}else{
																					echo "<p class='text-right'><a href='".$players['link']."'>".$players['nick'] ." - Elo: ".$players['elo']."</a></p>";
																				}
																				$numero++;
																			}
																			echo '</div>';
																		?>
																		<div class="clearfix"></div>
																	</div>
														</div>
													</div>	
													<?php
													}
													if($haymasde50){
													?>	
													<div class="col-lg-6">
														<div class="panel panel-primary">
																	<div class="panel-heading">
																			<div class="row">
																					<div class="col-xs-6 text-left">
																							<div class="huge">
																									<small>TOP del 50 al <?php echo (50+count($otros))?></small>
																							</div>
																					</div>
																			</div>
																	</div>
																	<div class="panel-footer">
																		<?php
																			$mitad =round(count($mas50)/2);
																			$numero=0;
																			echo '<div class="pull-left">';
																			foreach ($mas50 as $players){
																				if($mitad==$numero){
																					echo '</div>';
																					echo '<div class="pull-right">';
																				}
																				if($mitad<=$numero){
																					echo "<p><a href='".$players['link']."'>".$players['nick'] ." - Elo: ".$players['elo']."</a></p>";
																				}else{
																					echo "<p class='text-right'><a href='".$players['link']."'>".$players['nick'] ." - Elo: ".$players['elo']."</a></p>";
																				}
																				$numero++;
																			}
																			echo '</div>';
																		?>
																		<div class="clearfix"></div>
																	</div>
														</div>
													</div>
													<?php
													}
													?>
							</div> <!-- container-fluid -->
						</div> <!-- page-wrapper -->
					</div> <!-- .entry-content -->
				</article> <!-- .et_pb_post -->
			<?php endwhile; ?>
			</div> <!-- #left-area -->
		</div> <!-- #content-area -->
	</div> <!-- .container -->
</div> <!-- main-content -->
<script>
		Morris.Donut({
		  element: 'top10vs_donut1',
		  data: [
				{label: <?PHP echo '"',$lang['sttw0011'],'"'; ?>, value: <?PHP echo $top10_sum ?>},
				{label: <?PHP echo '"'.sprintf($lang['sttw0012'].'"', $sumentries); ?>, value: <?PHP echo $others_sum ?>},
		  ]
		});
		Morris.Donut({
		  element: 'top10vs_donut2',
		  data: [
				{label: <?PHP echo '"',$lang['sttw0011'],'"'; ?>, value: <?PHP echo $top10_sum - $top10_idle_sum ?>},
				{label: <?PHP echo '"'.sprintf($lang['sttw0012'].'"', $sumentries); ?>, value: <?PHP echo $others_sum - $others_idle_sum ?>},
		  ],
				colors: [
				'#5cb85c',
				'#80ce80'
		]
		});
		Morris.Donut({
		  element: 'top10vs_donut3',
		  data: [
				{label: <?PHP echo '"',$lang['sttw0011'],'"'; ?>, value: <?PHP echo $top10_idle_sum ?>},
				{label: <?PHP echo '"'.sprintf($lang['sttw0012'].'"', $sumentries); ?>, value: <?PHP echo $others_idle_sum ?>},
		  ],
		  colors: [
				'#f0ad4e',
				'#ffc675'
		]
		});
</script>
<?php get_footer(); ?>