<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:og="http://ogp.me/ns#" xmlns:fb="https://www.facebook.com/2008/fbml" <?php language_attributes(); ?> itemscope itemtype="http://schema.org/Article">  

<head profile="http://gmpg.org/xfn/11">

	<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />	
	<title><?php wp_title(''); ?></title>	
	<?php do_action( 'bp_head' ) ?>

	<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />
	<link rel="apple-touch-icon" href="<?php bloginfo('template_directory'); ?>/images/cuidadoinfantil_57.png" />
	<link rel="apple-touch-icon" sizes="72x72" href="<?php bloginfo('template_directory'); ?>/images/cuidadoinfantil_72.png" />
	<link rel="apple-touch-icon" sizes="114x114" href="<?php bloginfo('template_directory'); ?>/images/cuidadoinfantil_114.png" />
	<meta name="verify-v1" content="j2fWry/iCtAzljUjMp3f5SkqnUdc2T+dt2ILaWmpPdM=" />	
	<meta name="google-site-verification" content="ThNuvFdVDn42BuXwPGP6Ejscd7YJ2eKb9Jkt4RuH_zw" />

	<meta name="google-site-verification" content="ThNuvFdVDn42BuXwPGP6Ejscd7YJ2eKb9Jkt4RuH_zw" />

	<link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?> RSS Feed" href="<?php bloginfo('rss2_url'); ?>" />
	
	<link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?> <?php _e( 'Blog Posts RSS Feed', 'buddypress' ) ?>" href="<?php bloginfo('rss2_url'); ?>" />
	<link rel="alternate" type="application/atom+xml" title="<?php bloginfo('name'); ?> <?php _e( 'Blog Posts Atom Feed', 'buddypress' ) ?>" href="<?php bloginfo('atom_url'); ?>" />
	<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
			
	<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/scripts/setSwf.js"></script>

	<?php wp_head(); ?>
</head>


<body <?php body_class("comunidad blog-1 pin-plugin") ?>>



<div id="web" class="clearfix" style="background: none; ">

<div id="top-bar">
	<span class="bar"></span>
	<a href="http://cuidadoinfantil.net" title="<?php bloginfo('name'); ?>" title="<?php bloginfo('name'); ?>" id="top-menu-logo">
		<img src="<?php echo TEMPLATE_URL?>/images/logo-foros-small.png"  title="<?php bloginfo('name'); ?>"/>
	</a>
    <div class="user-nav navbar">
        <?php if (!is_user_logged_in()) login_fb_button(''); else bp_navigation(); ?>
    </div>
</div>




