FLASHNODE MODULE
----------------

Description
-----------
Flashnode is designed to make it very easy to upload flash content on to a
Drupal based website. When enabled it creates a new content type called
Flash so you can upload movies without writing any code or HTML.

Flashnode uses SWFTools (drupal.org/project/swftools) to do the actual
insertion of flash, so you must download and install that module first.
SWFTools lets you choose the flash insertion method that you would like to use
and includes both direct embedding and a variety of javascript methods.

As of flashnode 5.3 you can also upload flv and mp3 files. This makes use
of the built in SWFTools media players to output audio and video content.
Simply upload your SWF or FLV file to flashnode, and it will be rendered using
the default player.


Using flashnode
---------------
In its simplest form you simply create a flash node, upload a file, write
your body text as normal and hit submit, and the file will display.

You can set other parameters on the input form and these are found under
Basic flash node options and Advanced flash node options. The basic options
let you set whether the movie should appear in the teaser, body or both views,
and you can also adjust the movie size if required.

Advanced options let you change the substitution text that appears if using
a javascript insertion method, and you can also set flashvars and the base
parameter to correctly locate sub-movies.

If the node is using PHP input format then you can generate flashvars via
PHP code to make dynamic movies that receive data from the host website.

Flashnode also allows you to activate an additional input format that lets you
use a simple macro language to re-use flash content on other pages. These
take the form [flashnode|nid=nnn] and can be supplemented with parameters to
size and scale the content or pass different flashvars.

If you have used an earlier version of flashnode or flash module please take
a look at UPDATE.txt for additional information.

; $Id: README.txt,v 1.5 2007/08/16 07:06:02 stuartgreenfield Exp $
