<?php
/**
 * Pin - Vía loop
 *
 */

?>

<?php pin_locate_template( 'header.php' ) ?>

<div id="pin-page-wrapper" class="clearfix">
	
	<?php pin_locate_template('nav.php') ?>
	<h1 class="archive-title">Pins extraídos de <?php echo get_query_var('pinid')?></h1>
	<div id="pins-wrapper">
		<?php pin_locate_template('loop.php'); ?>

	</div>

</div>

<?php get_footer('foro'); ?>
		
		