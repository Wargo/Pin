<?php
/**
 * Pin - Single category pins archive
 */
?>

<?php 
	global $pins, $pin_categories;
?>

<?php pin_locate_template( 'header.php' ) ?>

<div id="pin-page-wrapper" class="clearfix">
	
	<?php pin_locate_template('nav.php') ?>
	<?php //pin_nav_categories( get_query_var( 'pinid') );  ?>
	<h1 class="archive-title">Pins en la categoría <?php echo $pin_categories[get_query_var('pinid')]->name ?></h1>
	<div id="pins-wrapper">
	
		<?php pin_locate_template('loop.php'); ?>

	</div>

</div>

<?php get_footer('foro'); ?>
		