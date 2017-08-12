<?php
// $Id: views_argument_api_taxonomy.inc,v 1.1.2.1 2007/07/11 22:53:31 morrissinger Exp $

/**
 * @file
 *	This include file implements views_argument_api functionality on
 * behalf of taxonomy.module
 */
 
/*********************************************************************
 * Hooks: Views Argument API
 */
 
/**
 * Hook to define ways to handle taxonomy argument form elements.
 */
function taxonomy_views_argument_api_argument_elements() {

	$elements = array();
	
	// Taxid
	$elements['taxid'] = array();
	
	$elements['taxid']['taxid_select'] = array(
  	'name' => 'taxid_select',
  	'label' => t('Taxonomy: Select a pre-defined term'),
		'handler' => 'views_argument_api_handler_default_form_value',
		'element' => array(
			'#options' => taxonomy_form_all(),
			'#type' => 'select',
			'#title' => t('Term'),
			'#description' => t('Choose a term from which to display nodes.'),
		),
	);
	
	foreach(taxonomy_get_vocabularies() as $vocabulary) {
		$elements['taxid']['taxid_node_' . $vocabulary->vid] = array(
			'name' => 'taxid_node_' . $vocabulary->vid,
			'label' => t('Taxonomy: Use node\'s term for !vocabulary', array('!vocabulary' => $vocabulary->name)),
			'handler' => 'taxonomy_views_argument_api_handler_node',
			'callback arguments' => array(
				'vid' => $vocabulary->vid,
			),
			'element' => array(
				'#type' => 'checkbox',
				'#default_value' => TRUE,
				'#title' => t('Use node\'s term for !vocabulary.', array('!vocabulary' => $vocabulary->name)),
				'#disabled' => TRUE,
				'#description' => t('Pass the node\'s taxonomy for !vocabulary to the view.', array('!vocabulary' => $vocabulary->name)),
			),
		);
	}
	
	// Taxletter
	$elements['taxletter'] = array();
	$elements['taxletter']['taxletter_textfield'] = array(
		'name' => 'taxletter_textfield',
		'label' => t('Taxonomy: Enter a pre-defined term'),
		'handler' => 'views_argument_api_handler_default_form_value',
		'element' => array(
			'#type' => 'textfield',
			'#title' => t('Term'),
			'#description' => t('Enter a term (by name) from which to display nodes.'),
		),
	);
	
	// Vocid
	foreach(taxonomy_get_vocabularies() as $vocabulary) {
		$vocabularies[$vocabulary->vid] = $vocabulary->name;
	}
	
	$elements['vocid'] = array();
	$elements['vocid']['vocid_select'] = array(
		'name' => 'taxletter_select',
		'label' => t('Taxonomy: Select a pre-defined vocabulary'),
		'handler' => 'views_argument_api_handler_default_form_value',
		'element' => array(
			'#options' => $vocabularies,
			'#type' => 'select',
			'#title' => t('Vocabulary'),
			'#description' => t('Choose a vocabulary from which to display nodes.'),
		),
	);
	
	return $elements;
}

/*********************************************************************
 * Handlers
 */
 
/**
 * Returns a tid from a particular vid for a loaded node.
 */
function taxonomy_views_argument_api_handler_node($value, $vid) {
	$nid = explode('node/', substr(urldecode(drupal_get_destination()), 12));
	if(count($nid) < 2) {
		return NULL;
	}
	
	$nid = $nid[1];
	$nid = explode('/', $nid);
	$nid = array_shift($nid);
	
	if(is_numeric($nid)) {
		$terms = taxonomy_node_get_terms_by_vocabulary($nid, $vid);
		$term = array_shift($terms);
		$tid = $term->tid;
	} else {
		$tid = NULL;
	}
	
	return $tid;
}
