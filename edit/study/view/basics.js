//Copyright (c) 2008, California Institute of Technology.
//ALL RIGHTS RESERVED. U.S. Government sponsorship acknowledged.
//
window.addEvent('domready', function() {
    new eip($$('.editable'), '../../ajax/ajax_update.php', {action: 'update'});
    new eiplist($$('.editablelist'),'../../ajax/ajax_update.php', {action: 'update'});
  });