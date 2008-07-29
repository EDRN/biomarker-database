//Copyright (c) 2008, California Institute of Technology.
//ALL RIGHTS RESERVED. U.S. Government sponsorship acknowledged.
//
  window.addEvent('domready', function() {
    $$('.removeorgan').each(function(a){
      a.setProperty('onclick',
        'return confirm("All data relating this biomarker and this organ will be deleted. Proceed?");');
      });
      
    $$('.optionswitch').each(function(a){
      a.addEvent('click',
        function() {
          if($('options_content').style.display == 'none') {
            // show
            new Fx.Style('options_content', 'opacity').set(0);
            $('options_content').setStyle('display','block');
            $('options_content').effect('opacity',{duration:400, transition:Fx.Transitions.linear}).start(0,1);
          } else {
            // hide
            $('options_content').effect('opacity',{
              duration:200, 
              transition:Fx.Transitions.linear,onComplete:function(){
                $('options_content').setStyle('display','none');
              }
            }).start(1,0);
          }
        });
    });
  });
