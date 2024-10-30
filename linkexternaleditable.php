<?php
/* User-editable functions for the Link External Plugin */
/*------------------------------------------------------*/

function LinkExternal($post_ID) {
//-----------------------------------------------------------------
// Output a link to the full article, external article or album page
	global $fullURL, $linkURL, $slideURL;
	if ($fullURL) { // We have a link to a full article
		echo "<a href=\"".$fullURL."\" rel=\"bookmark\" title=\"Full article: ".$post_ID->title."\">Read the full article...</a><p></p>";
		}
	if ($linkURL) { // We have a link to an attached item
		echo "<a target=\"_blank\" href=\"".$linkURL."\">".$linkURL."</a><p></p>";
		}
	if ($slideURL) { // We have a link to an attached item
		echo "<a href=\"".$slideURL."\">View Image in Album...</a><p></p>";
		}
}
?>