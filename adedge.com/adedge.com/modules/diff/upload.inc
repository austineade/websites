<?php
// $Id: upload.inc,v 1.1 2007/01/31 17:45:25 dww Exp $

/**
 * Implementation of hook_diff() for file attachments.
 */
function upload_diff(&$old_node, &$new_node) {
  $result = array();
  $old_files = array();
  if (isset($old_node->files)) {
    foreach($old_node->files as $file) {
      $old_files[] = $file->filename;
    }
  }
  $new_files = array();
  if (isset($new_node->files)) {
    foreach($new_node->files as $file) {
      $new_files[] = $file->filename;
    }
  }
  $result[] = array(
    'name' => t('Attachements'),
    'old' => $old_files,
    'new' => $new_files,
    'format' => array(
      'show_header' => false,
    )
  );
  return $result; 
}
