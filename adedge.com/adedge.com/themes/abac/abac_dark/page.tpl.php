<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php print $language ?>" lang="<?php print $language ?>">
<head>
	<title><?php print $head_title ?></title>
		<?php print $head ?>
		<?php print $styles ?>
		<?php print $scripts ?>
		<style type="text/css" media="print">@import "<?php print base_path() . path_to_theme() ?>/print.css";</style>
</head>
<body<?php print phptemplate_body_class($sidebar_left, $sidebar_right); ?>>
<div class="main">
	<div class="heada">
		<div class="headMenu">
			<?php if (isset($primary_links)) : ?>
			<?php print theme('links', $primary_links, array('class' => 'links primary-links')) ?>
			<?php endif; ?>
		</div>
		<div class="search">
			<?php if ($search_box): ?><?php print $search_box ?><?php endif; ?>
		</div>
	</div>
	<div class="headb">
		<div class="logo"><?php			
			if ($logo) {
				print '<a href="'. check_url($base_path) .'" title="'. $site_title .'">';
				if ($logo) {
				print '<img src="'. check_url($logo) .'" alt="'. $site_title .'" id="logo" />';
				}
				print $site_html .'</a>'; } ?>				
		</div>
		<div class="welcome"><h1><?php
			$site_fields = array();
			if ($site_name) {
				$site_fields[] = check_plain($site_name);
			}
			if ($site_slogan) {
				$site_fields[] = check_plain($site_slogan);
			}
			$site_title = implode(' ', $site_fields);
			$site_fields[0] = '<span>'. $site_fields[0] .'</span>';
			$site_html = implode(' ', $site_fields);
			if ($site_title) {
				print '<a href="'. check_url($base_path) .'" title="'. $site_title .'">';
				print $site_html .'</a>'; } ?></h1>			
			<p><?php print $footer_message ?></p>
		</div>
	</div>
	<div class="cont">
		<div class="leftColumn">
			<?php print $sidebar_left ?>			
		</div>
		<div class="center">			
			<?php if ($mission): print '<div class="mission">'. $mission .'</div>'; endif; ?>
			<?php if ($breadcrumb): print $breadcrumb; endif; ?>
			<?php print $header; ?>			
			<?php if ($title): print '<h1'. ($tabs ? ' class="with-tabs"' : '') .'>'. $title .'</h1>'; endif; ?>				
			<?php if ($tabs): print $tabs; endif; ?>
			<?php if (isset($tabs2)): print $tabs2; endif; ?>
			<?php if ($help): print $help; endif; ?>
			<?php if ($messages): print $messages; endif; ?>
			<?php print $content ?>
		</div>
	</div>
	<div class="bottom">	
		<?php if (isset($secondary_links)) : ?>
        <?php print theme('links', $secondary_links, array('class' => 'links secondary-links')) ?>
        <?php endif; ?>	
		<?php print $feed_icons ?>		
	</div>
	<div class="copy">
		<p>Powered by <a href="http://www.drupal.org/">Drupal</a> - <!-- Please do not remove this command line --> Design by <a href="http://www.artinet.ru/">Artinet</a></p>
	</div>
</div>
<?php print $closure ?>
</body>
</html>
