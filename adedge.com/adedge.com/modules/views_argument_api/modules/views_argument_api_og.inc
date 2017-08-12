<?php
// $Id: views_argument_api_og.inc,v 1.1.2.1 2007/07/12 00:28:43 morrissinger Exp $

/**
 * @file
 *	This include file implements views_argument_api functionality on
 * behalf of og.module
 */
 
/*********************************************************************
 * Hooks: Views Argument API
 */
 
/**
 * Hook to define ways to handle user argument form elements.
 */
function og_views_argument_api_argument_elements() {
	$qs = db_query("SELECT n.nid, n.title FROM {node} n INNER JOIN {og} og ON n.nid = og.nid");
	while($obj = db_fetch_object($qs)) {
		$group_options[$obj->nid] = $obj->title;
	}

	$elements = array();
	
	// Gid
	$elements['gid'] = array();
	
	$elements['gid']['gid_select'] = array(
		'name' => 'uid_select',
  	'label' => t('Groups: Select a pre-defined group'),
  	'handler' => 'views_argument_api_handler_default_form_value',
  	'element' => array(
			'#options' => $group_options,
			'#type' => 'select',
			'#title' => t('Group'),
			'#description' => t('Choose a group from which to display nodes.'),
		),
	);
	
	$elements['gid']['gid_node'] = array(
		'name' => 'gid_current',
  	'label' => t('Groups: Based on current group'),
  	'handler' => 'og_views_argument_api_handler_current',
  	'element' => array(
			'#type' => 'checkbox',
			'#default_value' => TRUE,
			'#title' => t('Use the current group to filter for nodes.'),
			'#disabled' => TRUE,
			'#description' => t('Pass the current group\'s ID to the view.'),
		),
	);
	
	return $elements;
}

/*********************************************************************
 * Handlers
 */

/**
 * Passes the current group's gid to the view.
 */
function og_views_argument_api_handler_gid_current($value) {
		$nid = explode('node/', substr(urldecode(drupal_get_destination()), 12));
	if(count($nid) < 2) {
		return NULL;
	}
	
	$nid = $nid[1];
	$nid = explode('/', $nid);
	$nid = array_shift($nid);
	
	if(is_numeric($nid)) {
		$node = node_load($nid);
		$gid = $node->gid; // Todo: verify that this is the right place to look.
	} else {
		$gid = NULL;
	}
	
	return $gid;
}
