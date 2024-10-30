<html>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
<link rel="stylesheet" type="text/css" href="../andrewj.css">
<title>Comments</title>
</head>

<body background="../images/notepaper.gif" bgproperties="fixed">
<p></p>
<h2>Comments</h2>

<?php
// Include main site wordpress
define('WP_USE_THEMES', false);
require('../wordpress/wp-load.php');

// Get post defined by slug value
$Slug = @$_GET["slug"];
query_posts('name='.$Slug);
?>

<?php //The Loop
while ( have_posts() ) : the_post(); ?>
	<?php if ( get_comments_number() > 0 ) : ?>
		<h3 id="comments-title"><?php
		printf( _n( 'One Response to %2$s', '%1$s Responses to %2$s', get_comments_number(), 'twentyten' ),
		number_format_i18n( get_comments_number() ), '<em>' . get_the_title() . '</em>' );
		?></h3>

		<?php global $post; ?>
		<?php foreach( get_comments(array('post_id' => $post->ID, 'order' => ASC)) as $comment ) : ?>
			<?php if ( $comment->comment_approved != '0' ) : ?>
	
			<h3><?php echo get_comment_author(); ?> on <?php printf( __( '%1$s at %2$s', 'twentyten' ), get_comment_date(),  get_comment_time() ); ?></h3>
			<?php comment_text(); ?>

			<?php endif; ?>
		<?php endforeach; ?>
		<hr>
	<?php endif; // end have_comments() ?>
	<p>If you'd like to comment on this article, with ideas, examples, or just to 
	praise it to the skies then I'd love to hear from you.</p>
	<h3><a href="<?php the_permalink(); ?>" target="_parent">Comment on this article</a></h3>
<?php endwhile; ?>

</body>
</html>
