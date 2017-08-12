<?php
// $Id: template.php,v 1.2.2.3 2007/09/23 07:04:57 johnalbin Exp $

/**
 * @file
 * An example template.php file for your theme.
 *
 * INITIALIZE THEME SETTINGS
 *
 * Since the theme settings variables aren't set until we submit the
 * admin/build/themes/settings/mytheme form, we need to check whether they are
 * set or not. If the variables aren't set, we need to set them to the default
 * values.
 *
 * We accomplish that by retrieving one of the variables and seeing if it is
 * null. If it is null, we save the defaults using variable_set() and then force
 * the refresh of the settings in Drupal's internals using
 * theme_get_setting('', TRUE).
 *
 * RETRIEVE THEME SETTINGS
 *
 * You can retrieve a specific theme setting using theme_get_setting(). To set
 * the theme settings variables in your theme files (like page.tpl.php and
 * node.tpl.php), call theme_get_setting() inside _phptemplate_variables().
 */


/*
 * Initialize theme settings
 */
if (is_null(theme_get_setting('garland_happy'))) {
  global $theme_key;
  // Save default theme settings
  $defaults = array(
    'garland_happy' => 1,
    'garland_shoes' => 0,
  );
  variable_set(
    str_replace('/', '_', 'theme_'. $theme_key .'_settings'),
    array_merge(theme_get_settings($theme_key), $defaults)
  );
  // Force refresh of Drupal internals
  theme_get_setting('', TRUE);
}

/**
 * Override or insert variables into the templates.
 *
 * In this function, a hook refers to the name of a tpl.php file. Thus, case
 * 'page' affects page.tpl.php. 'node' affects node.tpl.php, and case 'block'
 * would affect block.tpl.php.
 *
 * @param $hook
 *   string The name of the tpl.php file.
 * @param $original_vars
 *   array A copy of the array containing the variables for the hook.
 * @return
 *   array The array containing additional variables to merge with $original_vars.
 */
function _phptemplate_variables($hook, $original_vars) {
  $additional_vars = array();
  switch ($hook) {
    case 'page':
      $additional_vars['garland_happy'] = theme_get_setting('garland_happy');
      break;
    case 'node':
      $additional_vars['garland_shoes'] = theme_get_setting('garland_shoes');
      break;
  }
  return $additional_vars;
}
