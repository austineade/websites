//$Id: README.txt,v 1.1 2007/06/26 18:10:04 mfredrickson Exp $

Node Cloud Readme 
-----------------

Node Cloud reuses the popular "tag cloud" idea, but allows one to use nodes
as the items in the cloud, instead of terms or tags. Node Cloud is a view
plugin that presents the data returned from the view as a cloud of text, with
the importance of each node indicated by the size of the text.

Node Cloud is fairly easy to use. To create a node cloud, go to your views
administration pages, choose a view, and select "Node Cloud" in the drop down
list of views plugins for either the page or block view. Choose two ordering
criteria. Node cloud will do the rest.

Node Cloud tries to use some sane defaults to provide good out of the box
support. Getting to know the default behavior will help you better understand
what kinds of clouds you can create:

The order of the nodes in the cloud is controlled by the first sort order in
the view. For example, you may wish to sort your node alphabetically or by
creation date.

The size of the individual items in the cloud are controlled by the second
sort order in the view. This sort should always be numerical. For example,
you might size your nodes on how many votes each has received.

If you supply any fields, they will be displayed in the cloud. If you don't
select any fields, a title link for each node will be used.

The minimum and maximum text size can be controlled at
admin/settings/nodecloud. Text sizes are always in EM measurements.

Good luck and enjoy Node Cloud.