<?php

// Vippy Shortcode
function vippy_func( $atts ) {
	extract( shortcode_atts( array(
		'id' => null,
		'width' => null,
		'height' => null
	), $atts ) );
	
	$size = '640x360'; //default plugin (not from DB)
	
	//echo $width;
	
	$vippy = new Vippy;
	
	if(isset($width) && isset($height)){
		
		if(is_numeric($width) && is_numeric($height))
		{
			//echo "test";
			$size = $width.'x'.$height;
		}
	}
	
	$opt = array(
		'videoId' => $id, 
		'size' => $size
	);
	$embed = $vippy->get_embedcode($opt);
	//$embed = json_decode($embed);
	if(!isset($embed->error) && isset($embed->vippy))
	{
		if ($id && $embed) :
		
			/*if (function_exists('vippy_is_mobile') && vippy_is_mobile()) :
				$embed = html_entity_decode($embed[0]->html5);
			elseif ( $embed ) :
				$embed = html_entity_decode($embed[0]->flash);
			endif;*/
			
			return '<div class="vippy-content-embed">'.html_entity_decode($embed->vippy).'</div>';
		
		endif;	
	}

	
}
add_shortcode( 'vippy', 'vippy_func' );
