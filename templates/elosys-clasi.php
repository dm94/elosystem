<?php

/*
Template Name: EloSys-Clasificacion
Creado: Daniel "Dm94Dani" Martin
*/

get_header();

$haymas = false;
$haymasde50 = false;
global $wpdb;
?>
<?php

	$urlperfil = 'http://comunidadgzone.es/maraton/perfil/';

	$sel = $wpdb->get_results("select nick, (elo+eloextra) as total from elplayers order by total DESC;");
	$col=0;
							
	foreach ($sel as $row) {
		$client_data[$col] = array(
			'nick'		=>	$row[0],
			'elo'	=>	$row[1],
			'link'	=>	$urlperfil."?nick=".$row[0]
		);
		$col++;
	}

?>
<div id="main-content">
	<div class="container">
		<div id="content-area" class="clearfix">
			<div id="left-area">

			<?php while ( have_posts() ) : the_post(); ?>
			
				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					<div class="entry-content row">
					<?php
						the_content();
					?>	
						<div class="col-lg-12">
							<div class="panel panel-primary">
								<div class="panel-heading">
									<div class="row">
										<div class="col-xs-6 text-left">
											<div class="huge"><?php _e('Clasificación Global','elosystem'); ?></div>
										</div>
									</div>
								</div>
								<div class="panel-footer">
									<table>
										<tr>
											<th><?php _e('Posición','elosystem'); ?></th>
											<th><?php _e('Nick','elosystem'); ?></th>
											<th><?php _e('Elo','elosystem'); ?></th>
											<th><?php _e('Acción','elosystem'); ?></th>
										</tr>
										<?php
											$numero = 1;
											foreach ($client_data as $players){
												echo '<tr>';
													echo '<td>'.$numero.'</td>';
													echo "<td><a href='".$players['link']."'>".$players['nick']."</a></td>";
													echo '<td>'.$players['elo'].'</td>';
													echo "<td><a href='".$players['link']."'>Ver Perfil</a></td>";
												echo '</tr>';
												$numero++;
											}
										?>	
									</table>
								</div>
							</div>
						</div>
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