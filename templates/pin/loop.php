<?php
/**
 * Pin - Default pins loop
 */
?>
<?php

	global $pins, $next, $pin;
	
	if ( ! empty( $pins )) {
	
		foreach  ($pins as $pin) {
			pin_locate_template('loop-single-pin.php');
		}
	
		if (! empty($next)){
			echo '<nav id="page-nav"><a href="' . $next . '">NEXT</a></nav>';
		}
	
	} else {
		echo '<div class="alert alert-block alert-error fade in"><h4 class="alert-heading">Nada por aquí</h4><p>Lo siento pero no hemos encontrado ninguna cosa bella por aquí</p></div>';
	}