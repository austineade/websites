<?php
// $Id: date.inc,v 1.13.2.15.2.3 2008/04/15 09:20:37 quicksketch Exp $
/**
 * Create a set of form items to be displayed on the form for editing this
 * component. Use care naming the form items, as this correlates directly to the
 * database schema. The component "Name" and "Description" fields are added to
 * every component type and are not necessary to specify here (although they may
 * be overridden if desired).
 * @return
 *   An array of form items to be displayed on the edit component page.
 */
function _webform_edit_date($currfield) {
  $edit_fields = array();
  $edit_fields['value'] = array(
    '#type' => 'textfield',
    '#title' => t("Default value"),
    '#default_value' => $currfield['value'],
    '#description' => t('The default value of the field.') .'<br />'. t('Accepts any date in any <a href="http://www.gnu.org/software/tar/manual/html_node/tar_109.html">GNU Date Input Format</a>. Strings such as today, +2 months, and Dec 9 2004 are all valid.') .'<br />'. webform_help('webform/helptext#variables'),
    '#size' => 60,
    '#maxlength' => 127,
    '#weight' => 0,
  );
  $edit_fields['extra']['timezone'] = array(
    '#type' => 'radios',
    '#title' => t("Timezone"),
    '#default_value' => empty($currfield['extra']['timezone']) ? "site" : $currfield['extra']['timezone'],
    '#description' => t('Adjust the date according to a specific timezone. Website timezone is defined in the <a href="%settings">Site Settings</a> and is the default.', array('%settings' => url('admin/settings'))),
    '#options' => array('site' => 'Website Timezone', 'user' => 'User Timezone', 'gmt' => 'GMT'),
    '#weight' => 0,
  );
  $edit_fields['extra']['check_daylight_savings'] = array(
    '#type' => 'checkbox',
    '#title' => t("Observe Daylight Savings"),
    '#default_value' => $currfield['extra']['check_daylight_savings'],
    '#checked_value' => 1,
    '#description' => t('Automatically adjust the time during daylight savings.'),
    '#weight' => 1,
  );

  return $edit_fields;
}

/**
 * Build a form item array containing all the properties of this component.
 * @param $component
 *   An array of information describing the component, directly correlating to
 *   the webform_component database schema.
 * @return
 *   An array of a form item to be displayed on the client-side webform.
 */
function _webform_render_date($component) {
  if (strlen($component['value']) > 0) {
    // Calculate the timestamp in GMT.
    $timestamp = strtotime(_webform_filter_values($component['value']));

    if ($component['extra']['timezone'] == "user") {
      // Use the users timezone.
      global $user;
      $timestamp += (int)$user->timezone;
    }
    elseif ($component['extra']['timezone'] == "gmt") {
      // Use GMT.
      $timestamp += 0;
    }
    else {
      // Use the Drupal site time.
      $timestamp += variable_get('date_default_timezone', 0);
    }

    // Check for daylight savings time.
    if ($component['extra']['check_daylight_savings'] && date("I")) {
      $timestamp += 3600;
    }

    $year = gmdate('Y', $timestamp);
    $month = gmdate('n', $timestamp);
    $day = gmdate('j', $timestamp);
  }
  $months = array("" => t("month"), 1 => t('January'), t('February'), t('March'), t('April'), t('May'), t('June'), t('July'), t('August'), t('September'), t('October'), t('November'), t('December'));
  $days = array("" => t("day"));
  for ($i = 1; $i <= 31; $i++) $days[$i] = $i;

  $form_item = array(
    '#title' => $component['name'],
    '#weight' => $component['weight'],
    '#theme' => 'webform_date',
    '#description' => _webform_filter_descriptions($component['extra']['description']),
    '#prefix' => '<div class="webform-component-'. $component['type'] .'" id="webform-component-'. $component['form_key'] .'">',
    '#suffix' => '</div>',
    '#required' => $component['mandatory'],
  );

  $form_item['month'] = array(
    '#type' => 'select',
    '#default_value' => $month,
    '#options' => $months,
    '#validate' => array('webform_validate_date' => array('month', $component['name'], $component['form_key'], $component['cid'], $component['mandatory'])),
  );
  $form_item['day'] = array(
    '#type' => 'select',
    '#default_value' => $day,
    '#options' => $days,
    '#validate' => array('webform_validate_date' => array('day', $component['name'], $component['form_key'], $component['cid'], $component['mandatory'])),
  );
  $form_item['year'] = array(
    '#type' => 'textfield',
    '#default_value' => $year,
    '#maxlength' => 4,
    '#size' => 4,
    '#validate' => array('webform_validate_date' => array('year', $component['name'], $component['form_key'], $component['cid'], $component['mandatory'])),
  );

  return $form_item;
}

function webform_validate_date($field, $field_name, $component_name, $form_key, $cid, $mandatory) {
  static $complete_dates = array();

  switch ($field_name) {
    case 'month':
      $complete_dates[$cid]['month'] = $field['#value'];
      break;
    case 'day':
      $complete_dates[$cid]['day'] = $field['#value'];
      break;
    case 'year':
      $complete_dates[$cid]['year'] = $field['#value'];
      break;
  }

  // Check if the user filled the required fields.
  if (!is_numeric($field['#value']) && $mandatory) {
    form_set_error($form_key, t("%field field is required.", array('%field' => $component_name)));
    $complete_dates[$cid] = array();
    return false;
  }

  // Check for a valid date.
  if ((isset($complete_dates[$cid]['month']) &&
        isset($complete_dates[$cid]['day']) &&
        isset($complete_dates[$cid]['year'])) &&
      ($complete_dates[$cid]['month'] !== "" ||
        $complete_dates[$cid]['day']   !== "" ||
        $complete_dates[$cid]['year']  !== "")
    ) {
    if (!checkdate((int)$complete_dates[$cid]['month'], (int)$complete_dates[$cid]['day'], (int)$complete_dates[$cid]['year'])) {
      form_set_error($form_key .']['. $field_name, t("Entered %name is not a valid date.", array('%name' => $component_name)));
      $complete_dates[$cid] = array();
      return false;
    }

    $complete_dates[$cid] = array();
  }
}

/**
 * Display the result of a textfield submission. The output of this function
 * will be displayed under the "results" tab then "submissions".
 * @param $data
 *   An array of information containing the submission result, directly
 *   correlating to the webform_submitted_data database schema.
 * @param $component
 *   An array of information describing the component, directly correlating to
 *   the webform_component database schema.
 * @return
 *   Textual output formatted for human reading.
 */
function _webform_submission_display_date($data, $component, $enabled = false) {
  $form_item = _webform_render_date($component);
  $form_item['month']['#default_value'] = $data['value']['0'];
  $form_item['day']['#default_value']   = $data['value']['1'];
  $form_item['year']['#default_value']  = $data['value']['2'];
  $form_item['month']['#disabled']      = !$enabled;
  $form_item['day']['#disabled']        = !$enabled;
  $form_item['year']['#disabled']       = !$enabled;
  return $form_item;
}

/**
 * Format the output of emailed data for this component
 *
 * @param $data
 *   A string or array of the submitted data
 * @param $component
 *   An array of information describing the component,
 * directly correlating to the webform_component database schema.
 * @return string
 *   Textual output to be included in the email.
 */
function theme_webform_mail_date($data, $component) {
  $output = $component['name'] .":";
  if ($data['month'] && $data['day']) {
    $timestamp = strtotime($data['month'] ."/". $data['day'] ."/". $data['year']);
    /* allow for PHP timezone offset */
    $tz_offset = strtotime(date("M d Y H:i:s")) - strtotime(gmdate("M d Y H:i:s"));
    $timestamp += $tz_offset;
    $output .= " ". format_date($timestamp, "custom", 'F j, Y', NULL);
  }
  return $output;
}

/**
 * Module specific instance of hook_help().
 */
function _webform_help_date($section) {
  switch ($section) {
    case 'admin/settings/webform#date_description':
      $output = t("Presents month, day, and year fields.");
      break;
  }
  return $output;
}

/**
 * Calculate and returns statistics about results for this component from all
 * submission to this webform. The output of this function will be displayed
 * under the "results" tab then "analysis".
 * @param $component
 *   An array of information describing the component, directly correlating to
 *   the webform_component database schema
 * @return
 *   An array of data rows, each containing a statistic for this component's
 *   submissions.
 */
function _webform_analysis_rows_date($component) {
  $query = 'SELECT no,data '.
    ' FROM {webform_submitted_data} '.
    ' WHERE nid = %d '.
    ' AND  cid = %d '.
    ' ORDER BY sid,no ASC ';

  $result = db_query($query, $component['nid'], $component['cid']);
  // build an array of timestamps from entered values.
  $timestamps = array();
  $submissions = 1;
  while ($row = db_fetch_array($result)) {
    if ($row['no'] == '0') {
      $submissions++;
      $month = $row['data'];
      if ($row = db_fetch_array($result)) {
        if ($row['no'] == '1') {
          $day = $row['data'];
          if ($row = db_fetch_array($result)) {
            if ($row['no'] == '2') {
              $year = $row['data'];
              // Build the full timestamp.
              if (strlen($month) > 0  && strlen($day) > 0  && strlen($year) > 0 ) {
                $timestamp = strtotime($month ."/". $day ."/". $year);
                // Add usefull information about this date into an array.
                $timestamps[$timestamp] = array(
                  date("l", $timestamp), // Day of the week (Monday, Tuesday, etc.).
                  date("F", $timestamp), // Full Month name (January, February, etc.).
                  $year, // Year.
                  $day,  // Day of the month (1,2,...,31).
                );
              }
            }
          }
        }
      }
    }
  }

  // Display stats.
  // TODO: display date statistics in javascript tabs.
  $nonblanks = count($timestamps);
  $rows[0] = array( t('Left Blank'), ($submissions - $nonblanks));
  $rows[1] = array( t('User entered value'), $nonblanks);
  return $rows;
}

/**
 * Return the result of this component's submission for display in a table. The
 * output of this function will be displayed under the "results" tab then "table".
 * @param $data
 *   An array of information containing the submission result, directly
 *   correlating to the webform_submitted_data database schema
 * @return
 *   Textual output formatted for human reading.
 */
function _webform_table_data_date($data) {
  if (strlen($data['value']['0']) > 0 && strlen($data['value']['1']) > 0 && strlen($data['value']['2']) > 0) {
    return check_plain($data['value']['0'] ."/". $data['value']['1'] ."/". $data['value']['2']);
  }
  else {
    return "";
  }
}


/**
 * Return the header information for this component to be displayed in a comma
 * seperated value file. The output of this function will be displayed under the
 * "results" tab then "download".
 * @param $component
 *   An array of information describing the component, directly correlating to
 *   the webform_component database schema.
 * @return
 *   An array of data to be displayed in the first three rows of a CSV file, not
 *   including either prefixed or trailing commas.
 */
function _webform_csv_headers_date($component) {
  $header = array();
  $header[0] = '';
  $header[1] = '';
  $header[2] = $component['name'];
  return $header;
}

/**
 * Return the result of a textfield submission. The output of this function will
 * be displayed under the "results" tab then "submissions".
 * @param $data
 *   An array of information containing the submission result, directly
 *   correlating to the webform_submitted_data database schema.
 * @return
 *   Textual output formatted for CSV, not including either prefixed or trailing
 *   commas.
 */
function _webform_csv_data_date($data) {
  if (strlen($data['value']['0']) > 0 && strlen($data['value']['1']) > 0 && strlen($data['value']['2']) > 0) {
    return $data['value']['0'] ."/". $data['value']['1'] ."/". $data['value']['2'];
  }
  else {
    return "";
  }
}

/**
 * Theme a webform date element.
 */
function theme_webform_date($element) {
  $element['#type'] = 'element';

  // Determine the order of day, month, year in the site's chosen date format.
  $format = variable_get('date_format_short', 'm/d/Y - H:i');
  $sort = array();
  $sort['day'] = max(strpos($format, 'd'), strpos($format, 'j'));
  $sort['month'] = max(strpos($format, 'm'), strpos($format, 'M'));
  $sort['year'] = strpos($format, 'Y');
  asort($sort);
  $order = array_keys($sort);

  // Output multi-selector for date.
  $element['#children'] = '';
  foreach ($order as $type) {
    $element['#children'] .= drupal_render($element[$type]);
  }
  $element['#children'] = '<div class="container-inline">'. $element['#children'] .'</div>';

  return theme('form_element', $element, $element['#children']);
}
