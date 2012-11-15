<?php
/**
 * Pin - Categories archive
 */
?>

<?php pin_locate_template( 'header.php' ) ?>

<div id="pin-page-wrapper" class="clearfix">

	<?php pin_locate_template('nav.php') ?>
	
	
	<div id="pins-wrapper">
		<div class="content">
			<?php pin_locate_template('loop-categories.php')?>
		</div>
	</div>

</div>

<?php get_footer('foro'); ?>
