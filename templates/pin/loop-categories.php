<?php
/**
 * Pin - Categories loop
 */
?>

<?php

	global $pin_categories;
	
	
	
	foreach ($pin_categories as $cat) { 

		if ( empty( $cat->pins ))
			continue;
	?>
	
		<div class="pin pin-category">
        
        	<h3 class="segoe"><a href="<?php echo pin_url( 'category', $cat->slug )?>"><?php echo $cat->name ?></a></h3>
        	

        	<div class="board">
            
    	        <a class="link" href="<?php echo pin_url( 'category', $cat->slug )?>">&nbsp;</a>
    	        
            	<div class="holder">

                    <span class="cover">
                        <img src="<?php echo thumbGen($cat->pins[0], 222, 150, "background=#FFFFFF") ?>" width="222" height="150" alt="<?php echo $cat->name ?> cover">
                    </span>
                    
                    <span class="thumbs">
                    	<?php array_shift($cat->pins ); if (! empty( $cat->pins )): foreach ( $cat->pins as $img_src) : ?>
                    		<img src="<?php echo thumbGen($img_src, 55, 55, "background=transparent") ?>" width="75" height="75" alt="<?php echo $cat->name ?> thumb">
                        <?php endforeach;; endif;?>
                    </span>
            </div>
        </div>
    </div>
		
	<?php } ?>
	