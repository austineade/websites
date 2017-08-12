<?php
	require_once './includes/bootstrap.inc';        // Instanciate Drupal
	drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);
   
   /* General Rules:
    *
    * Node ID ($_GET['nid'])  - Can be specified if you would like to filter the XML within a node.
    * File ID ($_GET['fids']) - Can be given as a single file, or a sequence like "29-38-89-22", where each File ID is separated by a "-".  
    *             
    * If a File ID sequence is given, the playlist will play in the order of the sequence.
    *
    */
   
   if(isset($_GET['nid']) && is_numeric($_GET['nid'])) {
      $node = node_load($_GET['nid']);                                              // Load the current node.
   }
   else {
   	$node->nid = 0;
   }
      
   $contents = '';                                                                  // To store the contents of the XML lists.
      
   if(isset($_GET['fids'])) {                                                        // If they pass only one File ID, then this means they only want to play one file
      $fids = explode("-", $_GET['fids']);                                           // A sequence can also be given : example "23-53" plays fid 23 and fid 53 sequencially.
   }
      
   // TO-DO:  Commercial manager plugs in here... future enhancments
      
   // If they specified a node, we can include the intro video in the playlist
   if(isset($node) && $node->nid) {
   	$intro_video = variable_get('flashvideo_' . $node->type .'_player_intro', ''); 
   	if($intro_video != '') {
   		$intro_ext = substr($intro_video, strrpos($intro_video, '.') + 1); 
        if($intro_ext == 'flv') {
        	   $output_dir = '/' . file_create_path(variable_get('flashvideo_' . $node->type .'_output_dir', '')) . '/';    // The output directory
            $intro_path = $base_url . $output_dir . $intro_video;
            $contents .= '<track><title>Intro</title><location>' . $intro_path . '</location><album>commercial</album></track>';
        }
      }
    }
      
    if(isset($node) && $node->nid) {
      $result = db_query("SELECT * FROM {files} WHERE nid=%d AND filemime='%s'", $node->nid, 'flv-application/octet-stream');
    }
    else {
      $result = db_query("SELECT * FROM {files} WHERE filemime='%s'", 'flv-application/octet-stream');
    }
    
    $all_files = array();
    while($file = db_fetch_object($result)) {                                                 		// Walk through all the files
      $all_files[] = $file;
    }  
    
    // We need to construct a files list based off of the order of the $fids array.
    $files = array();
    if(isset($fids) && (count($fids) > 0)) {
      foreach($fids as $fid) {
         foreach($all_files as $file) {
            if(trim($fid) == $file->fid) {
               $files[] = $file;
               continue 2;                   // Continue with the $fids iteration (That's what the 2 does...)
            }  
         }  
      }
    }
    else {      
      $files = $all_files;                   // The didn't provide a $_GET['fids'].
    }  
    
    if(count($files) > 0) {
      foreach($files as $file) {  
         $contents .= '<track>';
         $contents .= '<title>' . $file->filename . '</title>';
         $contents .= '<location>' . $base_url . '/' . $file->filepath . '</location>';
         $contents .= '</track>';
      }
    }
    
    if($contents != '') {
      $xml = '<playlist version="1" xmlns="http://xspf.org/ns/0/"><trackList>';        // Start off the XML file contents
      $xml .= $contents;                                                               // Fill in all the rest.
      $xml .= '</trackList></playlist>';                                               // Finish off the XML file contents
      echo $xml;
    }
?>