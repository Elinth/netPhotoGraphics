<?php

/*
 * creates a rewrite rule for "page/gallery" and modifies the link to same to
 * follow the rule.
 *
 * @author Stephen Billard (sbillard)
 * @package plugins/galleryToken
 * @pluginCategory example
 */

$plugin_is_filter = 5 | FEATURE_PLUGIN;
$plugin_description = gettext('Rewrite rule for the <em>gallery</em> custom page.');
$plugin_disable = (MOD_REWRITE) ? '' : gettext('<em>galleryToken</em> requires the <code>mod_rewrite</code> option be enabled.');


$_zp_conf_vars['special_pages']['gallery'] = array('define' => '_GALLERY_PAGE_', 'rewrite' => getOption('galleryToken_link'),
		'option' => 'galleryToken_link', 'default' => '_PAGE_/gallery');
$_zp_conf_vars['special_pages'][] = array('definition' => '%GALLERY_PAGE%', 'rewrite' => '_GALLERY_PAGE_');
$_zp_conf_vars['special_pages'][] = array('define' => false, 'rewrite' => '%GALLERY_PAGE%/([0-9]+)', 'rule' => '^%REWRITE%/*$		index.php?p=gallery&page=$1' . ' [L,QSA]');
$_zp_conf_vars['special_pages'][] = array('define' => false, 'rewrite' => '%GALLERY_PAGE%', 'rule' => '^%REWRITE%/*$		index.php?p=gallery [L,QSA]');
