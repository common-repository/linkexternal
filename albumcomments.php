<?php session_start(); ?>
<html>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
<link rel="stylesheet" type="text/css" href="../andrewj.css">
<title>Comments</title>
<style type="text/css">
.auto-style1 {
	vertical-align: baseline;
}
</style>
</head>

<body background="../images/notepaper.gif" bgproperties="fixed">
<p align="center"><em>All images on these pages are copyright Andrew K. Johnston</em></p>

<?php
// Include main site wordpress
define('WP_USE_THEMES', false);
require('../wordpress/wp-load.php');

// Get image URL, and convert to imageID
$image = @$_GET["image"];
if ($image == "") { // If not provided as query string, try inferring from parent page URL
	$parts = parse_url(getenv("HTTP_REFERER"));
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
<p>If you'd like to comment on this image, or just let me know you've been looking at my work, then I'd love to hear from you.</p>
<h3><a href="<?php echo "/blog/index.php/imagecomments?image=".$image; ?>" target="_parent">Comment on this image</a></h3>
<p>&nbsp;</p>
<p>To keep up with updates to my portfolio, please
<a href="http://feeds.feedburner.com/ImagesOfTheWorld">subscribe to my 
photography blog</a>
			<a href="http://feeds.feedburner.com/ImagesOfTheWorld" title="Subscribe to my feed">
	<img src="http://www.feedburner.com/fb/images/pub/flchklt.gif" style="border:0" align="top" class="auto-style1"/></a></p>

<p><font color="#00F529" face="Lydian BT" size="5"><b>
Thank you for visiting Andrew's Photography Pages. 
</b></font></p>
</body>
</html>
