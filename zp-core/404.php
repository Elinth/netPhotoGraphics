<?php
// force UTF-8  Ø
zp_clearCookie('index_page_paged');
list($album, $image) = rewrite_get_album_image('album', 'image');
$folders = explode('/', $album);
if (array_key_exists(0, $folders) && $folders[0] == CACHEFOLDER) {
	// a failed reference to a cached image?
	require_once(SERVERPATH . '/' . ZENFOLDER . '/admin-functions.php');
	require_once(SERVERPATH . '/' . ZENFOLDER . '/' . PLUGIN_FOLDER . '/cacheManager/functions.php');
	unset($folders[0]);
	if ($image) {
		$folders[] = $image;
	}
	list($i, $args) = getImageProcessorURIFromCacheName(implode('/', $folders) . '/', getWatermarks());
	if (file_exists(getAlbumFolder() . $i)) {
		/* Prevent hotlinking to cache from other domains. */
		if (isset($_SERVER['HTTP_REFERER'])) {
			preg_match('|(.*)//([^/]*)|', $_SERVER['HTTP_REFERER'], $matches);
			if (preg_replace('/^www\./', '', strtolower($_SERVER['SERVER_NAME'])) == preg_replace('/^www\./', '', strtolower($matches[2]))) {
				//internal request
				$uri = getImageURI($args, dirname($i), basename($i), NULL);
				header("HTTP/1.0 302 Found");
				header("Status: 302 Found");
				header('Location: ' . $uri);
				exitZP();
			}
		}
	}
}
if (isset($_GET['fromlogout'])) {
	header("HTTP/1.0 302 Found");
	header("Status: 302 Found");
	header('Location: ' . WEBPATH . '/index.php');
	exitZP();
}
if (empty($image) && Gallery::imageObjectClass($album)) {
	$image = basename($album);
	$album = dirname($album);
}
$_404_data = array($album, $image, $obj = @$_zp_gallery_page, @$_index_theme, @$_zp_page);

$_zp_gallery_page = '404.php';
if (isset($_index_theme)) {
	$_zp_script = SERVERPATH . "/" . THEMEFOLDER . '/' . internalToFilesystem($_index_theme) . '/404.php';
} else {
	$_zp_script = NULL;
}
if (class_exists('ipBlocker')) {
	ipBlocker::notFound();
}

header('Content-Type: text/html; charset=' . LOCAL_CHARSET);
header("HTTP/1.0 404 Not Found");
header("Status: 404 Not Found");
zp_apply_filter('theme_headers');
if ($_zp_script && file_exists($_zp_script)) {
	if (isset($custom) && $custom)
		require_once($custom);
	include($_zp_script);
} else {
	?>
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/2002/REC-xhtml1-20020801/DTD/xhtml1-transitional.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml">
		<head>
		</head>
		<body>
			<?php
			zp_apply_filter('theme_body_open');
			print404status();
			?>
			<br />
			<a href="<?php echo html_encode(getGalleryIndexURL()); ?>"
				 title="<?php echo gettext('Index'); ?>"><?php echo sprintf(gettext("Return to %s"), getGalleryTitle()); ?></a>
		</body>
		<?php zp_apply_filter('theme_body_close'); ?>
	</html>
	<?php
}
?>