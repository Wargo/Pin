<?php pin_locate_template( 'header.php' ) ?>

<div id="pin-page-wrapper" class="clearfix">
	
	<?php pin_locate_template('nav.php') ?>
	<?php pin_locate_template('user-nav.php'); ?>		
	<div id="pins-wrapper">
		
		<?php 
			switch ( get_query_var('pinsubsection') ){
				case PIN_BOARDS_SLUG:
					pin_locate_template('loop-boards.php');		
					break;
				case 'pins':
					pin_locate_template('loop.php');
					break;
				case PIN_FOLLOWERS_SLUG:
					pin_locate_template('loop-users.php');
					break;
				case PIN_FOLLOWING_SLUG:
					pin_locate_template('loop-boards.php');		
					break;
				default:
					pin_locate_template('loop.php');		
			}
		
		 ?>
	</div>

</div>

<?php 
	if ( pin_is_my_profile())
		pin_new_board_modal();
?>
<?php get_footer('foro'); ?>
		