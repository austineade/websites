<?php
// $Id: views_argument_api_user.inc,v 1.1 2007/06/11 20:43:19 morrissinger Exp $

/**
 * @file
 *	This include file implements views_argument_api functionality on
 * behalf of user.module
 */
 
/*********************************************************************
 * Hooks: Views Argument API
 */
 
/**
 * Hook to define ways to handle user argument form elements.
 */
function user_views_argument_api_argument_elements() {
	$qs = db_query("SELECT u.uid, u.name FROM {users} u");
	while($obj = db_fetch_object($qs)) {
		if(empty($obj->name)) {
			$name = t('Anonymous');
		} else {
			$name = $obj->name;
		}
		$user_options[$obj->uid] = $name;
	}
	
	$elements = array();
	
	// Uid
	$elements['uid'] = array();
	
	$elements['uid']['uid_select'] = array(
		'name' => 'uid_select',
  	'label' => t('User: Select a pre-defined user'),
  	'handler' => 'views_argument_api_handler_default_form_value',
  	'element' => array(
			'#options' => $user_options,
			'#type' => 'select',
			'#title' => t('User'),
			'#description' => t('Choose a user from which to display nodes.'),
		),
	);
	
	$elements['uid']['uid_current'] = array(
		'name' => 'uid_current',
  	'label' => t('User: Based on current user'),
  	'handler' => 'user_views_argument_api_handler_current',
  	'element' => array(
			'#type' => 'checkbox',
			'#default_value' => TRUE,
			'#title' => t('Use the current user to filter nodes.'),
			'#disabled' => TRUE,
			'#description' => t('Pass the current user\'s ID to the view.'),
		),
	);
	
	$elements['uid']['uid_node'] = array(
		'name' => 'uid_node',
  	'label' => t('User: Based on current node\'s author'),
  	'handler' => 'user_views_argument_api_handler_node',
  	'element' => array(
			'#type' => 'checkbox',
			'#default_value' => TRUE,
			'#title' => t('Use the current node\'s author to filter nodes.'),
			'#disabled' => TRUE,
			'#description' => t('Pass the current node\'s author to the view.'),
		),
	);
	
	// Uidtouch
	$elements['uidtouch'] = array();
	$elements['uidtouch']['uidtouch_select'] = array(
		'name' => 'uidtouch_select',
  	'label' => t('User: Select a pre-defined user'),
  	'handler' => 'views_argument_api_handler_default_form_value',
  	'element' => array(
			'#options' => $user_options,
			'#type' => 'select',
			'#title' => t('User'),
			'#description' => t('Choose a user from which to display touched nodes.'),
		),
	);
	
	$elements['uidtouch']['uidtouch_current'] = array(
		'name' => 'uidtouch_current',
  	'label' => t('User: Based on current user'),
  	'handler' => 'user_views_argument_api_handler_current',
  	'element' => array(
			'#type' => 'checkbox',
			'#default_value' => TRUE,
			'#title' => t('Use the current user to filter for touched nodes.'),
			'#disabled' => TRUE,
			'#description' => t('Pass the current user\'s ID to the view to filter for touched nodes.'),
		),
	);
	
	$elements['uidtouch']['uidtouch_node'] = array(
		'name' => 'uidtouch_node',
  	'label' => t('User: Based on current node\'s author'),
  	'handler' => 'user_views_argument_api_handler_node',
  	'element' => array(
			'#type' => 'checkbox',
			'#default_value' => TRUE,
			'#title' => t('Use the current node\'s author to filter for touched nodes.'),
			'#disabled' => TRUE,
			'#description' => t('Pass the current node\'s author to the view to filter for touched nodes.'),
		),
	);

	//Username
	$elements['username'] = array();
	$elements['username']['username_textfield'] = array(
		'name' => 'username_textfield',
		'label' => t('User: Enter a pre-defined username'),
		'handler' => 'views_argument_api_handler_default_form_value',
		'element' => array(
			'#type' => 'textfield',
			'#title' => t('Username'),
			'#description' => t('Enter a username from which to display nodes.'),
		),
	);
	
	return $elements;
}

/*********************************************************************
 * Handlers
 */
 
/**
 * Pass the current user's uid to the view.
 */
function user_views_argument_api_handler_current($value) {
	global $user;
	return $user->uid;
}

/**
 * Pass the current node's author's uid to the view.
 */
function user_views_argument_api_handler_node($value) {
	$nid = explode('node/', substr(urldecode(drupal_get_destination()), 12));
	if(count($nid) < 2) {
		return NULL;
	}
	
	$nid = $nid[1];
	$nid = explode('/', $nid);
	$nid = array_shift($nid);
	
	if(is_numeric($nid)) {
		$node = node_load($nid);
		$uid = $node->uid;
	} else {
		$uid = NULL;
	}
	
	return $uid;
}