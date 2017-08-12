Module: Views PHP Filter
Author: George Nassar (george.nassar@gmail.com)


Description:
------------
Per a request in http://drupal.org/node/115947, Views PHP Filter effectively creates a Views Filter on criteria defined by a snippet of PHP.  This allows for much more powerful custom filtering on dynamically generated criteria.


Installation:
-------------
1. Copy the module directory into your 'modules' directory

2. Enable the module at 'administer >> modules'; it will appear under the Views category.


Usage:
------
A new filter, labeled "Node: Node ID", will appear as addablein your Views filter lists.  If you add that filter, you'll note the "Options" dropdown will have two choices: "PHP code" and "ID list".  "PHP code" will take a snippet of PHP as its value, which should return an array of integers representing node IDs.  The filter will evaluate the PHP, and filter on the numbers in the return array.  "ID list" will simply take a list of numbers, separated by commas, and filter those node IDs.  This is currently used mainly for testing, but may survive to release if someone finds it useful.


Last updated: $Id: README.txt,v 1.1.2.1 2007/06/10 21:58:36 gnassar Exp $