  window.addEvent('domready', function() {
    new eip($$('.editable'), '../../xpress/extensions/ajax_update.php', {action: 'update'});
    new eiplist($$('.editablelist'),'../../xpress/extensions/ajax_update.php', {action: 'update'});
  });


