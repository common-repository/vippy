<?php

if(!function_exists('vippy_pre'))
{
	function vippy_pre($arr=array())
	{
		echo '<pre>';
		print_r($arr);
		echo '</pre>';
	}
}

// List Videos
function vippy_wp_list_videos() {
	$vippy = new Vippy;
	$opt = array('statistics' => 1);
	$request = $vippy->get_videos($opt);
	return $request;
}

// Authenticate
function vippy_wp_authenticate() {
	$results = vippy_wp_list_videos();	
	/*echo '<pre>';
	print_r($results);
	echo '</pre>';*/	
	if (!isset($results->error))
		return true;
	else
		return false;
}

// Vippy Upload Page
function do_vippy_wp_upload_page() {

	$width = 640;
	$height = 360;
	$options_correct = true;
	
	$options = get_option('vippy_settings');
	if(isset($options['vippy_default_width']) && isset($options['vippy_default_height']))
	{
		if(is_numeric($options['vippy_default_width']) && is_numeric($options['vippy_default_height']))
		{
			$width = $options['vippy_default_width'];
			$height = $options['vippy_default_height'];		
		}
		else
		{
			$options_correct = false;
		}
	}
	else
	{
		$options_correct = false;
	}
?>
	<form id="vippy-form" class="media-upload-form validate" action="" method="post" enctype="multipart/form-data" name="vippy-form">
<?php	
	if(!$options_correct)
	{
?>
		<div class="vippy-warning">
			Your Vippy settings is incomplete, please set the default width and height attributes by editing the Vippy plugin settings. Currently using plugin defaults "640x360".
		</div>
<?php	
	}
?>
		<table cellspacing="0" class="widefat">
			<thead>
				<tr>
					<th>
						<?php _e('Videos', VIPPYTEXTDOMAIN); ?>
					</th>
					<th>
						<?php _e('', VIPPYTEXTDOMAIN); ?>
					</th>
					<th>
						<?php _e('', VIPPYTEXTDOMAIN); ?>
					</th>
				</tr>
			</thead>
		</table>
		<div class="vippy-options">
			Embed videos using width <input style="width: 50px;" type="text" name="vippy-embed-width" id="vippy-embed-width" value="<?php echo $width;?>" /> 
			and height <input style="width: 50px;" type="text" name="vippy-embed-height" id="vippy-embed-height" value="<?php echo $height;?>" /> 
		</div>
		<div id="media-items">
			
			<?php
			$videos = vippy_wp_list_videos();
			$videos = $videos->vippy;

			if (is_array($videos)) : 
			
			require_once(VIPPYDIR . 'lib/pagination.class.php');
			$pagination = new vippyPagination;

			if (count($videos)) {
			$videoPages = $pagination->generate($videos, 10);
			if (count($videoPages) != 0) {
			echo $pageNumbers = '<div class="vippy-numbers">'.$pagination->links().'</div>';
			
			$vippy = new Vippy;
			
			
			
			foreach($videoPages as $video) : 
				$opt = array(
					'videoId' => $video->videoId, 
					'size' => '640x360'
				);
				$embed = $vippy->get_embedcode($opt);
				//vippy_pre($embed);
				//$embed = json_decode($embed); 
			 ?>
			
			<div class="media-item vippy-item" id="vippy-item-<?php echo $video->videoId; ?>">
				
				<a rel="<?php _e('view-vippy-video-thumb', VIPPYTEXTDOMAIN); ?>" title="<?php echo $video->title; ?>" href="#vippy-<?php echo $video->videoId; ?>" target="_blank" class="vippy-inline">
					<img style="margin: 8px 6px 0;" alt="" src="http://vippy.co/app/image.php?url=<?php echo $video->thumbnail; ?>&amp;w=80&amp;h=60" class="pinkynail"/>
				</a>
				
				<div>
					<input class="vippy-embed-video-id" type="hidden" value="<?php echo $video->videoId; ?>" id="type-of-<?php echo $video->videoId; ?>" /> <a title="<?php echo $video->title; ?>" target="_blank" href="#" class="embed-vippy toggle describe-toggle-on"><?php _e('Embed', VIPPYTEXTDOMAIN); ?></a>
				</div>
				
				<div>
					<a rel="<?php _e('view-vippy-video', VIPPYTEXTDOMAIN); ?>" title="<?php echo $video->title; ?>" href="#vippy-<?php echo $video->videoId; ?>" target="_blank" class="vippy-inline toggle describe-toggle-on"><?php _e('View', VIPPYTEXTDOMAIN); ?></a>
				</div>
				
				<div class="menu_order">
					<?php echo __('<strong>Plays</strong>: ', VIPPYTEXTDOMAIN) . $video->plays; ?>
				</div>
				
				<div class="menu_order">
					<?php echo __('<strong>Created</strong>: ', VIPPYTEXTDOMAIN) . date('n/j/Y', strtotime($video->uploaded)); ?>
				</div>
				
				<div class="filename">
					<span class="title"><?php echo $video->title; ?></span>
				</div>
				
			</div>
			
			<div style="display: none" class="vippy-preview-container">
				<div id="vippy-<?php echo $video->videoId; ?>">
					<?php echo html_entity_decode($embed->vippy); ?>
				</div>
			</div>

			<?php endforeach; ?>
			
			<?php
			}
			}
			?>
			
			<?php endif; ?>
			
		</div>
		
		<?php
			if ($pageNumbers)
				echo $pageNumbers;
		?>
		
	</form>
	<?php
}

// Duration
function sec2hms ($sec, $padHours = false) {

    // start with a blank string
    $hms = "";
    
    // do the hours first: there are 3600 seconds in an hour, so if we divide
    // the total number of seconds by 3600 and throw away the remainder, we're
    // left with the number of hours in those seconds
    $hours = intval(intval($sec) / 3600); 

    // add hours to $hms (with a leading 0 if asked for)
    $hms .= ($padHours) 
          ? str_pad($hours, 2, "0", STR_PAD_LEFT). ":"
          : $hours. ":";
    
    // dividing the total seconds by 60 will give us the number of minutes
    // in total, but we're interested in *minutes past the hour* and to get
    // this, we have to divide by 60 again and then use the remainder
    $minutes = intval(($sec / 60) % 60); 

    // add minutes to $hms (with a leading 0 if needed)
    $hms .= str_pad($minutes, 2, "0", STR_PAD_LEFT). ":";

    // seconds past the minute are found by dividing the total number of seconds
    // by 60 and using the remainder
    $seconds = intval($sec % 60); 

    // add seconds to $hms (with a leading 0 if needed)
    $hms .= str_pad($seconds, 2, "0", STR_PAD_LEFT);

    // done!
    return $hms;    
}

// Size
function file_size($size) {
	$filesizename = array(" Bytes", " KB", " MB", " GB", " TB", " PB", " EB", " ZB", " YB");
  	return $size ? round($size/pow(1024, ($i = floor(log($size, 1024)))), 2) . $filesizename[$i] : '0 Bytes';
}

// Add Vippy Head Script
function vippy_head_script() { ?>
	<script type="text/javascript" language="javascript" src="http://cdn2.vippy.co/files/js/vippy.js"></script>
	<link rel="stylesheet" href="http://cdn2.vippy.co/files/css/default.css" type="text/css" media="screen" />
<?php }
//add_action('wp_head', 'vippy_head_script');

// Upload Image and Set as Thumbnail
function vippy_generate_post_thumb($imageUrl, $post_id, $imageTitle) {
	
    // Get the file name
    $filename = substr($imageUrl, (strrpos($imageUrl, '/'))+1);

    if (!(($uploads = wp_upload_dir(current_time('mysql')) ) && false === $uploads['error'])) {
        return null;
    }

    // Generate unique file name
    $filename = wp_unique_filename( $uploads['path'], $filename );

    // Move the file to the uploads dir
    $new_file = $uploads['path'] . "/$filename";
    
    if (!ini_get('allow_url_fopen')) {
        $file_data = curl_get_file_contents($imageUrl);
    } else {
        $file_data = @file_get_contents($imageUrl);
    }
    
    if (!$file_data) {
        return null;
    }
    
    file_put_contents($new_file, $file_data);

    // Set correct file permissions
    $stat = stat( dirname( $new_file ));
    $perms = $stat['mode'] & 0000666;
    @ chmod( $new_file, $perms );

    // Get the file type. Must to use it as a post thumbnail.
    $wp_filetype = wp_check_filetype( $filename );

    extract( $wp_filetype );

    // No file type! No point to proceed further
    if ( ( !$type || !$ext ) && !current_user_can( 'unfiltered_upload' ) ) {
        return null;
    }

    // Compute the URL
    $url = $uploads['url'] . "/$filename";

    // Construct the attachment array
    $attachment = array(
        'post_mime_type' => $type,
        'guid' => $url,
        'post_parent' => null,
        'post_title' => $imageTitle,
        'post_content' => '',
    );

    $thumb_id = wp_insert_attachment($attachment, false, $post_id);
    if ( !is_wp_error($thumb_id) ) {
        require_once(ABSPATH . '/wp-admin/includes/image.php');
        wp_update_attachment_metadata( $thumb_id, wp_generate_attachment_metadata( $thumb_id, $new_file ) );
    }

	// If we succeed in generating thumb, let's update post meta
    if ($thumb_id) {
        update_post_meta( $post_id, '_thumbnail_id', $thumb_id );
    }

    return null;
}

function vippy_curl_get_file_contents($URL) {
    $c = curl_init();
    curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($c, CURLOPT_URL, $URL);
    $contents = curl_exec($c);
    curl_close($c);

    if ($contents) {
        return $contents;
    }
    
    return FALSE;
}