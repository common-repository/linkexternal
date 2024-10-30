=== LinkExternal ===
Contributors: Andrew Johnston
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=FJCUQVE8YJDDJ
Tags: linkexternal, external, Jalbum
Requires at least: 2.9
Tested up to: 3.0
Stable tag: 1.4

LinkExternal provides the ability to link from WordPress to external pages and Jalbum slides.

== Description ==

LinkExternal provides the ability to link from WordPress to external pages and Jalbum slides, and link back to 
WordPress to manage comments on those items.
The WordPress model is to hold all content in the database. However, many older or existing web sites will have 
significant content in other pages. It may be undesirable to move this content into WordPress - some pages may 
be too long long for a blog post, and you may not want to break external links to existing pages. 

Also if you manage your online photo galleries using Jalbum, you may want to integrating single images from 
your albums, complete with things like metadata and watermarks, into your blog without duplicating that 
functionality in WordPress.

This plugin provides features to manage your external content and seamlessly integrate it with WordPress, 
as follows:
1. WordPress publishes abstracts, commentaries and announcements, separate to the main page, as normal 
   blog posts. WordPress also handles feed generation and so on.
2. Default links from WordPress (for example, from the post title) can go to the full external article, 
   not the blog post. The plugin supports separate links to the Permalink and so on. This is all automated 
   driven by the addition of one item of metadata to the post, a link to the external page.
3. WordPress handles the collection and approval of comments and trackbacks. These can be made visible 
   appended to the external page, with a link back to the WordPress post for further commenting. 
   This is capable of integration with any web page, by the addition of a single line of HTML code.
4. For image posts, WordPress automatically links to the the image and dynamically brings it, and optionally 
   its metadata, from the relevant Jalbum slide page. This is a "live link" - you don't merge your album 
   into WordPress.
5. WordPress manages comments on your album. It is possible to comment on any image in your album without 
   having a blog post for every image.

== Installation ==

Upload the LinkExternal plugin to your blog, activate it, then follow the instructions at www.andrewj.com/thoughts/linkexternal.asp.

== How to Use ==

See http://www.andrewj.com/thoughts/linkexternal.asp.

== Changelog ==

= 1.4 =

* No code changes from 1.1, version control point only

= 1.1 =

* Fixed various minor bugs, including support for non-standard HTTP ports
* Fixed major memory leak with image page parsing
* Added ability to centre featured image
* Added support for blog images in feeds

= 1.0 =

* First release

== Plugin Support ==

[Link External Content Support](http://www.andrewj.com/thoughts/linkexternal.asp "WordPress Plugins and Support Services")