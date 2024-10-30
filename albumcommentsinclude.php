<?php session_start(); ?>
<p align="center"><em>All images on these pages are copyright Andrew K. Johnston</em></p>
<div align="left">
<?php
// Include main site wordpress
define('WP_USE_THEMES', false);
require('../wordpress/wp-load.php');

// Get image URL, and convert to imageID
$image = @$_GET["image"];
if ($image == "") { // If not provided as query string, try inferring from parent page URL
	// $parts = parse_url(getenv("HTTP_REFERER")); // iframe version
	$parts = parse_url($_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"]); //include version
	$image = $parts["path"]; 
	}
$imageID = basename($image, ".html");
// echo "<p> Image ".$imageID."</p>";
// Now save the image ID in a session variable, required if posting comments from the general page
$_SESSION["image"] = $image;
$_SESSION["imageID"] = $imageID;

// Get all comment IDs with matching imageID
$comments = GetImageComments($imageID);
if ( $comments ) {
	echo "<h3>";
	printf( _n( 'One Comment on Image %2$s', '%1$s Comments on Image %2$s', count($comments), 'twentyten' ),
	number_format_i18n( count($comments)), $imageID);
	echo "</h3>";

	foreach( $comments as $commentID ) {
		$comment = get_comment($commentID);
		if ( $comment->comment_approved != '0' ) {
			echo "<h3>".get_comment_author()." on ";
			printf( __( '%1$s at %2$s', 'twentyten' ), get_comment_date(),  get_comment_time() );
			echo "</h3>";
			comment_text();
			}
		}
	echo "<hr>";
	}
?>
<p>If you'd like to 
<a href="<?php echo "/blog/index.php/imagecomments?image=".$image; ?>"><strong>comment on this image</strong></a>, or just let me know you've been looking at my work, then I'd love to hear from you.</p>
<p>&nbsp;</p>
	<p>My images are available as prints or for licenced use in print, online 
	media and other stock uses. If you are interested in using any of my images 
	in this way, or for any commercial purpose, then contact me, either by <a href="<?php echo "/blog/index.php/imagecomments?image=".$image; ?>" target="_parent">
	<strong>leaving a comment</strong></a>
	or by <a href="/index.asp#ContactMe"><strong>other means</strong></a>. </p>
	<p>You are welcome to copy or refer to the small watermarked images here for 
	personal use or for purposes such as criticism and comment or if you want to 
	promote my site and work. This is subject to the following restrictions: the 
	image must not be altered in any way whatsoever, the watermark must be left 
	intact and legible, and you must credit me as the source. I&#39;d also 
	appreciate it if you <a href="/index.asp#ContactMe"><strong>contact me</strong></a> or&nbsp; 
	by <a href="<?php echo "/blog/index.php/imagecomments?image=".$image; ?>" target="_parent">
	<strong>leave a comment</strong></a>
	to let me know. Any other reproduction, except 
	with prior permission, is expressly prohibited.</p>
<p>To keep up with updates to my portfolio, please
<a href="http://feeds.feedburner.com/ImagesOfTheWorld"><strong>subscribe to my 
photography blog</strong></a>
			<a href="http://feeds.feedburner.com/ImagesOfTheWorld" title="Subscribe to my feed">
	<img src="http://www.feedburner.com/fb/images/pub/flchklt.gif" style="border:0" align="top" class="auto-style1"/></a></p>

<p><font color="#00F529" face="Lydian BT" size="5"><b>
Thank you for visiting Andrew's Photography Pages. 
</b></font></p>
</div>