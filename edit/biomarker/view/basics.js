  window.addEvent('domready', function() {
    new eip($$('.editable'), '../../ajax/ajax_update.php', {action: 'update'});
    new eiplist($$('.editablelist'),'../../ajax/ajax_update.php', {action: 'update'});
  });


