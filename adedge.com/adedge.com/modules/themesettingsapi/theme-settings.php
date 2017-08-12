<?php
// $Id: theme-settings.php,v 1.1.2.3 2007/09/23 07:04:57 johnalbin Exp $

/**
 * Implementation of THEMEHOOK_settings() function.
 *
 * @param $saved_settings
 *   array An array of saved settings for this theme.
 * @return
 *   array A form array.
 */
function phptemplate_settings($saved_settings) {
  // The default values for the theme variables
  $defaults = array(
    'garland_happy' => 1,
    'garland_shoes' => 0,
  );

  // Merge the saved variables and their default values
  $settings = array_merge($defaults, $saved_settings);

  // Create the form widgets using Forms API
  $form['garland_happy'] = array(
    '#type' => 'checkbox',
    '#title' => t('Get happy'),
    '#default_value' => $settings['garland_happy'],
  );
  $form['garland_shoes'] = array(
    '#type' => 'checkbox',
    '#title' => t('Use ruby red slippers'),
    '#default_value' => $settings['garland_shoes'],
  );

  // Return the additional form widgets
  return $form;
}
