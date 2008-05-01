window.addEvent('domready',function(){

  $('ispanel').addEvent('change',function(){
    if ($('ispanel').getProperty('checked')) {
      $('showpanel').setStyle('display','block');
    } else {
      $('showpanel').setStyle('display','none');
    }
  });

});