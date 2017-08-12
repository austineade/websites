// $id$

xspf_playlist module generates a XSPF playlist from files that are attached to a node. 
It was designed for Jeroen Wijering's flash player, though it should work with other 
flash players. The admin interface allows the administrator to select what node types 
it works on, choose a default thumbnail file to use, and forthcoming support for CCK 
fields. It also supports multiple file types (audio, video, flash) that are supported 
by the flash player.

You can generate a xspf playlist for a node by using the url:
http//mysite.com/xspf/node/NID 
where NID is the node id of the node that you want the playlist for.

Advanced usage
------------------------------------

There is a xspf_playlist hook which allows you to add pre and post files to a playlist
This is helpful if you want to deliver "bumper" content or if you wanted to deliver content 
what is being viewed. Here's an example I wrote quickly to integrate the media mover
s3 module (storing files on amazon's s3 service) that addes these files to the playlist:

/**
 * implements the hook_xspf_playlist_add to modify 
 * the output of xspf files. This is helpful for building 
 * playlists for flashplayers
 */
function mm_s3_xspf_playlist_add($action, $node){
  switch ($action){
    case 'pre':
      foreach ($node->media_mover as $file) {
        if ($file['storage_module'] == 'mm_s3') {
          $items[] = xspf_playlist_build_file_item($node, $file['url']);               
          return $items;
        }        
      }
    break;   
   
    case 'post':
    break;
  }
}