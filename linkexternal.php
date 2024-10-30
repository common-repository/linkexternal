<?php
/*
Plugin Name: LinkExternal
Plugin URI: http://www.andrewj.com/thoughts/linkingexternal.asp
Description: Link External Content into Wordpress
Version: 1.4
Author: Andrew K Johnston
Author URI: http://www.andrewj.com
License: A "Slug" license name e.g. GPL2
*/
/*  Copyright 2010 Andrew K. Johnston (www.andrewj.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

// Use simple_html_dom.php to retrieve HTML contents of photo page
require_once('simple_html_dom.php');

// Declare globals
global $fullURL;
global $linkURL;
global $slideURL;
global $imageID;
global $imagePath;

function GetLinks($post_ID) {
//-----------------------------------------------------------------
// Get details of external links:
// full: Link to "full article" on same site
// link: Link to external article on another site
// image: Link to Jalbum slide page

	global $fullURL, $linkURL, $slideURL, $imagePath;
	// Get "full article" or image links if they exist
	// This can be passed as metadata, or via query string 
	$fullkey="full"; $fullURL=get_post_meta($post_ID->ID, $fullkey, true);
	if (@$_GET["full"]) { $fullURL = @$_GET["full"]; }
	$linkkey="link"; $linkURL=get_post_meta($post_ID->ID, $linkkey, true);  
	if (@$_GET["link"]) { $linkURL = @$_GET["link"]; }
	$slidekey="image"; $slideURL=get_post_meta($post_ID->ID, $slidekey, true);  
	if (@$_GET["image"]) { $slideURL = @$_GET["image"]; }
	// For images, we can also check the session variables
	if ($slideURL == "") {
		$slideURL = $_SESSION["image"];
		}
	// echo "<p>".$slideURL."</p>";
	$imagePath = "";
}

// Run GetLinks() when we process each post
add_action('the_post', GetLinks, 1); 

function GetFullURL() {
//-----------------------------------------------------------------
	global $fullURL;
	return $fullURL;
}

function SaveImageID($comment_ID) {
//-----------------------------------------------------------------
// Save the Image ID as an extra field against a comment

global $slideURL;

$comment = get_comment($comment_ID);
$post_ID = $comment->comment_post_ID;
$type    = get_post_type($post_ID);  
if ($type != "page") { // we're on a specific blog post, so we should already have the image ID on the post
	$imagekey="imageID"; 
	$imageID = get_post_meta($post_ID, $imagekey, true);
	}
else { // If type is page, then we just pick up the imageID from a session variable
	session_start(); 			// This connects to the existing session 
	$imageID = $_SESSION["imageID"];
	// add_comment_meta($comment_ID, 'oldimageID', $imageID);
	}
// add_comment_meta($comment_ID, 'oldimageID', $imageID);

if ($imageID != "") { // Don't process any further unless we have a slide URL, or general page
	update_comment_meta($comment_ID, 'imageID', $imageID);
	}
}

// Run SaveImageID() when we save any comment
add_action('comment_post', SaveImageID, 1); 
// Could also use comment_save_pre


function UpdateImageID($post_ID) {
//-----------------------------------------------------------------
// Save the Image ID as an extra field against a post
$slidekey="image"; 
$slideURL=get_post_meta($post_ID, $slidekey, true);  

// add_post_meta($post_ID, 'debug', $slideURL);
if ($slideURL != "") { // Don't process any further unless we have a slide URL
	$imageID = basename($slideURL, ".html");
	$imagekey="imageID"; 
	$imageIDVal =get_post_meta($post_ID, $imagekey, true);  
	if ($imageIDVal != $imageID) {
		update_post_meta($post_ID, 'imageID', $imageID, $imageIDVal);
		}
	}
}

// Run SaveImageID() when we save any comment
add_action('pre_post_update', UpdateImageID, 1); 


function ShowPhoto($ShowMeta, $ShowCentred = false) {
//-----------------------------------------------------------------
// Display an image and (optionally) associated metadata from Jalbum Slide Page
// $slideURL is slide .html filename 
global $slideURL;

//	echo "<p>".$slideURL."</p>";
if ($slideURL != "") {
	// Get the full slide filename
	$File="http://".$_SERVER['HTTP_HOST'].$slideURL;
	$Dir = dirname($File);
	$File = str_replace(" ", "%20", $File);
	// echo "<p>".$File."</p>";
	
	// get DOM from URL or file
	// $html = file_get_html($slideURL);
	$html = file_get_html($File);
	
	// Output the image centred
	if ($ShowCentred) {
		echo "<div align=\"center\">";
		}
	// echo "<td style=\"text-align:center; width:716px;\">";
	
	// Get the image tag from the HTML
	foreach($html->find('img[id=slide]') as $e) {
	    $e->src = $Dir."/".$e->src; // Append image path
	    // $e->style = "text-align:center";
	    echo $e->outertext;
	    }
	
	if ($ShowMeta == true) {
		// Find table with image metadata and output it
		foreach($html->find('table[id=metadata]') as $e)
			echo $e->outertext;
		}

	if ($ShowCentred) {
		echo "</div>";
		}
	$html->clear(); 
	unset($html); // Clear memory used by HTML parsing
	}
}

function GetImageComments($imageID) {
//-----------------------------------------------------------------
// Get all comment IDs with matching imageID

global $wpdb;

$comments = $wpdb->get_col("SELECT comment_ID FROM $wpdb->commentmeta WHERE meta_key = 'imageID' AND meta_value = '".$imageID."' ORDER BY comment_ID");
return $comments;
}

function ShowPhotoComments() {
//-----------------------------------------------------------------
// Display comments for the given image

global $slideURL;
global $imageID;

$imageID = basename($slideURL, ".html");
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
			echo "<h3>".get_comment_author($comment->comment_ID)." on ";
			printf( __( '%1$s', 'twentyten' ), comment_date(get_option('date_format')." ".get_option('time_format'),$commentID));
			echo "</h3>";
			echo get_comment_text($commentID);
			}
		}
	echo "<hr>";
	return true; // We had comments to display
	}
else {
	return false; // No image-specific comments
	}
}


function GetImagePath() {
//-----------------------------------------------------------------
// Gets image URL to include in RSS feeds
// $slideURL is slide .html filename 
global $imagePath, $slideURL;

if ($slideURL != "") {
	if (! $imagePath) {
	
		// Get the full slide filename
		$File="http://".$_SERVER['SERVER_NAME'].$slideURL;
		$Dir = dirname($File);
		$ThumbDir = str_replace("/slides", "/thumbs", $Dir);
		$File = str_replace(" ", "%20", $File);
		
		// get DOM from URL or file
		$html = file_get_html($File);
		
		// Get the image tag from the HTML and output in appropriate format for feed
		foreach($html->find('img[id=slide]') as $e) {
			$imagePath = $ThumbDir."/".$e->src;
		    }
		$html->clear(); 
		unset($html); // Clear memory used by HTML parsing
		}
	return $imagePath;
	}
}


function GetImageForFeed() {
//-----------------------------------------------------------------
// Gets image tag to include in RSS feeds
// $slideURL is slide .html filename 
global $slideURL, $imagePath;

if ($slideURL != "") {
	// Get the full slide filename
	$File="http://".$_SERVER['SERVER_NAME'].$slideURL;
	$Dir = dirname($File);
	$ThumbDir = str_replace("/slides", "/thumbs", $Dir);
	$File = str_replace(" ", "%20", $File);
	if (! $imagePath) {
		GetImagePath();
		}
	echo '<a href="'.$File.'"><img src="'.$imagePath.'"/></a>';
	}
}


function GetThumbForFeed() {
//-----------------------------------------------------------------
// Gets image tag to include in RSS feeds
// $slideURL is slide .html filename 
global $slideURL, $imagePath;

if ($slideURL != "") {
	// Get the full slide filename
	$File="http://".$_SERVER['SERVER_NAME'].$slideURL;
	$Dir = dirname($File);
	$ThumbDir = str_replace("/slides", "/thumbs", $Dir);
	$File = str_replace(" ", "%20", $File);
	if (! $imagePath) {
		GetImagePath();
		}
	$FullImagePath = str_replace("/thumbs/", "/slides/", $imagePath);
	echo '<media:thumbnail height="120" url="'.$imagePath.'" width="79"/>';
	echo '<media:content height="550" url="'.$FullImagePath.'" width="366"/>';
	}
}


// The following includes the "user editiable" functions which may need to be personalised or localised
include("linkexternaleditable.php");
?>