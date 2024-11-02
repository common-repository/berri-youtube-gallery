<?php
/*
Plugin Name: Berri YouTube Gallery (Widget)
Plugin URI: http://www.berriart.com/berri-youtube-gallery/
Description: This Widget will display the YouTube videos that you want and the only data needed are the video URLs. There is no need to register or to get YouTube API. Easy to customize and you can insert an image in the video thumbnail.
Author: Alberto Varela
Version: Beta
Author URI: http://www.berriart.com

	My Widget is released under the GNU General Public License (GPL)
	http://www.gnu.org/licenses/gpl.txt
*/

/******************************************************************************

Copyright 2007  Alberto Varela  (email : alberto@berriart.com)

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
The license is also available at http://www.gnu.org/copyleft/gpl.html

*********************************************************************************/

// The plugin large function
function widget_berrivg_init() {

	// Check to see required Widget API functions are defined...
	if ( !function_exists('register_sidebar_widget') || !function_exists('register_widget_control') )
		return; // ...and if not, exit from the script.

	// This function prints the sidebar widget
	function widget_berrivg($args) {

		// $args is an array of strings which help your widget
		// conform to the active theme: before_widget, before_title,
		// after_widget, and after_title are the array keys.
		extract($args);

		// Collect our widget's options, or define their defaults.
		$options = get_option('berrivg');

		$title = empty($options['title']) ? 'Berri YouTube Gallery' : $options['title'];

		if (empty($options['videos']))
		{
			$videos = 'No videos!';
		}
		else
		{
			$template = $options['template'];
			$tag_array = array("%%title%%", "%%url%%", "%%img%%");
			foreach ($options['video_data'] as $video_data)
			{	
				$tag_replace = array($video_data['title'], $video_data['url'], $video_data['img']);
				$new_video = str_replace($tag_array, $tag_replace, $template);
				$temp_videos = $temp_videos . $new_video;
			}	

			$videos = $temp_videos;

		}

 		// It's important to use the $before_widget, $before_title,
 		// $after_title and $after_widget variables in your output.
		echo $before_widget;
		echo $before_title . $title . $after_title;
		echo $videos;
		echo $after_widget;
	}

	// This is the function that outputs the form to let users edit the widget
	function widget_berrivg_control() {

		// Collect our widget's options.
		$options = get_option('berrivg');

		// This is for handing the control form submission.
		if ( $_POST['berrivg-submit'] == "ok") {
			// Clean up control form submission options
			$newoptions['title'] = strip_tags(stripslashes($_POST['berrivg-title']));
			$newoptions['videos'] = strip_tags(stripslashes($_POST['berrivg-videos']));

			$temp_videos = preg_replace('/\n|\r|\n\r/','<////>',$_POST['berrivg-videos']);
			$video_array = Array();
			$video_array = split('<////>',$temp_videos);
			$temp_videos = '';
			$video_options = array();
			foreach ($video_array as $video_url)
			{	
				if (preg_match('%youtube%', $video_url))
					{
						$urldata = open_external_url($video_url);
						if ($urldata != "nodata") {
							preg_match("/\<title>YouTube - (.*)\<\/title\>/i", $urldata, $r);
							$video_title = $r[1];
						}				
						$location = get_bloginfo('wpurl');	
						$video_code = substr($video_url,strpos($video_url,'=')+1);
						$video_img = $location .'/wp-content/plugins/berri-youtube-gallery/images/' . $video_code . '.jpg';
						$video_data = array('title'=>$video_title, 'img'=>$video_img, 'url'=>$video_url);
						array_push($video_options, $video_data);
					}
			}
			$newoptions['video_data'] = $video_options;
		

			if ($_POST['berrivg-watermarkChk']) {
				$newoptions['watermarkChk'] = 'checked';
			}
			else {
				$newoptions['watermarkChk'] = 'no-checked';				
			}
			if ($_POST['berrivg-playImgChk']) {
				$newoptions['playImgChk'] = 'checked';
			}
			else {
				$newoptions['playImgChk'] = 'no-checked';				
			}
			$newoptions['playImg'] = strip_tags(stripslashes($_POST['berrivg-playImg']));
			$newoptions['template'] = stripslashes($_POST['berrivg-template']);

			// If widget options do not match control form submission options, update them.
			if ( $options != $newoptions ) {
				$options = $newoptions;
				update_option('berrivg', $options);
			}		
		}

		// Format options as valid HTML. 
		$title = htmlspecialchars($options['title'], ENT_QUOTES);
		$videos = htmlspecialchars($options['videos'], ENT_QUOTES);
		$template = empty($options['template']) ? '<div id="video_img" style="margin-bottom:10px;margin-right:10px;text-align:center;"><a href="%%url%%" title="%%title%%">%%title%%</a><br /><a href="%%url%%" title="%%title%%"><img src="%%img%%" border="0" alt="%%title%%" /></a></div>' : htmlspecialchars($options['template'], ENT_QUOTES);
		//$template = htmlspecialchars($options['template'], ENT_QUOTES);
		$watermarkChk = empty($options['watermarkChk']) ? 'checked' : $options['watermarkChk'];
		$playImgChk = empty($options['playImgChk']) ? 'checked' : $options['playImgChk'];
		$playImg = htmlspecialchars($options['playImg'], ENT_QUOTES);
// The HTML below is the control form for editing options.
?>	
		<div>		
		<h3 style="margin:0px;">Settings</h3>
		<div style="float:left;width:380px;text-align=:left;">
		<p style="text-align:left;"><label for="berrivg-title">Title: <input style="width: 370px;" type="text" id="berrivg-title" name="berrivg-title" value="<?php echo $title; ?>" /></label></p>
		<p style="text-align:left;"><label for="berrivg-playImg">Play Image URL: <input style="width: 370px;" type="text" id="berrivg-playImg" name="berrivg-playImg" value="<?php echo $playImg; ?>" /></label></p>
		</div>
		<div style="float:right;width:220px;text-align=:left;">
		<p style="text-align:left;"><label for="berrivg-watermarkChk"><br />Show YouTube watermark?: <input  type="checkbox" id="berrivg-watermarkChk" name="berrivg-watermarkChk" <?php if($watermarkChk == 'checked') echo 'checked="checked"'; ?>" /></label></p>
		<p style="text-align:left;"><label for="berrivg-playImgChk"><br />Show play image?: <input  type="checkbox" id="berrivg-playImgChk" name="berrivg-playImgChk" <?php if($playImgChk == 'checked') echo 'checked="checked"'; ?>" /></label></p>
		</div>	
		<div><p style="text-align:left; clear:both;">Play image must be PNG. If is not or if you leave the text-field empty the default play image will be displayed</p></div>
		<div style="float:left;width:300px;text-align=:left;">
		<h3 style="margin:0px;">Videos</h3>
		<p style="text-align:left;"><label for="berrivg-videos">Video URLs (one URL for each line): <textarea style="width: 280px;" id="berrivg-videos" name="berrivg-videos" rows="7"><?php echo $videos; ?></textarea></label> </p>
		</div>
		<div style="float:right;width:300px;text-align=:left;">
		<h3 style="margin:0px;">Template Syntax</h3>
		<p style="text-align:left;"><label for="berrivg-template">Display template: <textarea style="width: 280px;" id="berrivg-template" name="berrivg-template" rows="7"><?php echo $template; ?></textarea></label></p>
		</div>
		<div><p style="text-align:center; font-size:80%; clear:both;">For questions or comments: <a href="http://www.berriart.com/berri-youtube-gallery/" title="berriart.com">www.berriart.com</a></p></div>
		<input type="hidden" name="berrivg-submit" id="berrivg-submit" value="ok" />
		</div>
	<?php
	// end of widget_berrivg_control()
	}

	// This registers the widget. About time.
	register_sidebar_widget('YouTube Gallery', 'widget_berrivg');

	// This registers the (optional!) widget control form.
	register_widget_control('YouTube Gallery', 'widget_berrivg_control', 600, 440);
}

//Gets the YouTube thumbnail
function getYouTubeImg($video_url)
{
	$video_url = trim($video_url);
	$video_code = substr($video_url,strpos($video_url,'=')+1);

	$youtube_thumb = 'http://img.youtube.com/vi/' . $video_code . '/1.jpg';

	return $youtube_thumb;
}

//Create the new image
function createNewImage($img_url,$name)
{
	$location = get_bloginfo('wpurl');
	$options = get_option('berrivg');

	if (!$im2 = imagecreatefromjpeg($img_url)) {
		echo "Error opening $img_url!"; exit;
	}

	if ($options['watermarkChk'] == 'checked')
	{	
		$watermark = "../wp-content/plugins/berri-youtube-gallery/youtube.png";
		$im = imagecreatefrompng($watermark);
		imagecopy($im2, $im, imagesx($im2)-(imagesx($im)+5), imagesy($im2)-(imagesy($im)+5), 0, 0, imagesx($im), imagesy($im));
	}
	if ($options['playImgChk'] == 'checked')
	{
		$play = "../wp-content/plugins/berri-youtube-gallery/play.png";
		if (!$im3 = imagecreatefrompng($options['playImg'])) 
			$im3 = imagecreatefrompng($play);
		imagecopy($im2, $im3, (imagesx($im2)/2)-(imagesx($im3)/2)-15, (imagesy($im2)/2)-(imagesy($im3)/2), 0, 0, imagesx($im3), imagesy($im3));
	}

	header("Content-Type: image/jpeg");
	imagejpeg($im2,'../wp-content/plugins/berri-youtube-gallery/images/'.$name.'.jpg',100);
}

//Create news images foreach youtube video
function createAllTheImages() {
			
			$options = get_option('berrivg');
			$temp_videos = preg_replace('/\n|\r|\n\r/','<////>',$options['videos']);

			$video_array = Array();
			$video_array = split('<////>',$temp_videos);

			$temp_videos = '';
			foreach ($video_array as $video_url)
			{	
				if (preg_match('%youtube%', $video_url))
					{	
						$video_code = substr($video_url,strpos($video_url,'=')+1);
						$video_img = getYouTubeImg($video_url);
						createNewImage($video_img,$video_code);
					}
			}

}

//Open the video url
function open_external_url($url)
{
	$data = '';
	$ch = curl_init($url);
	ob_start();
	curl_exec($ch);
	curl_close($ch);
	$data = ob_get_contents();
	ob_end_clean();

	if(eregi('301 Moved Permanently',$data)) {
		return "nodata"; 
	}
	else return $data;
}

add_action('update_option_berrivg', 'createAllTheImages');

add_action('plugins_loaded', 'widget_berrivg_init');
?>
