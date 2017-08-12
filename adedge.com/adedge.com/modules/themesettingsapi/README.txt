THEME SETTINGS API

  This module simply extends an old, undocumented API that is already in Drupal
  (introduced in Drupal 4.2):

    If a theme is its own theme engine (a "Plain PHP theme"), it can add its own
    settings to the /admin/build/themes/settings/mytheme page by adding a
    mytheme_settings() function to its mytheme.theme file.

  If you are writing your own theme, you can add custom theme settings by simply
  creating a theme-settings.php file in your theme directory and adding a
  THEME_settings() or ENGINE_settings() function. The ENGINE_settings() form is
  preferred since it allows others to more easily create derivative themes based
  on your theme. Example: for the Garland theme, a garland_settings() or
  phptemplate_settings() function would be placed in the theme's
  theme-settings.php file.

  If you are writing your own theme engine, you can add custom theme engine
  settings by using a ENGINE_engine_settings() function. Those settings would
  then be available to any theme using that engine. Example: for the PHPTal
  theme engine, a phptal_engine_settings() function would be placed in the
  engine's phptal.engine file.

  The Theme Settings API functions make use of the Forms API. For implementation
  details and best practices, see the included sample files, theme-settings.php and
  template.php.

  To retrieve the settings in your .tpl.php theme files, simply use
  theme_get_setting('varname'). See the Drupal API for details:

    http://api.drupal.org/api/5/function/theme_get_setting


ABOUT THE PROJECT

  It is currently impossible for themes (like PHPtemplate-based ones) to add
  settings to the theme settings page without coding a module. There needs to be
  an API to facilitate that. No one has successfully implemented this (See
  issues 54990, 56713 and 57676.)

  The Theme Settings API project created a fully functioning implementation of
  a custom theme settings API for Drupal. This API should reside in core and
  this project successfully championed the addition of an updated API into
  Drupal 6 (see issue 57676 at http://drupal.org/node/57676.)
