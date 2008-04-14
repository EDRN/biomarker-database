  window.addEvent('domready', function() {
    
    // Activate "remove association" links
    $$('.remove').each(function(a){
      a.setProperty('onclick',
        'return confirm("All data relating this biomarker and this study will be deleted. Proceed?");');
      });
      
    // Activate Associate Study switch
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
    
    // Activate Show/Hide details links
    $$('.detailswitch').each(function(a){
      // Get the id
      var classes = a.getProperty('class').split(" ");
      for (i=classes.length-1;i>=0;i--) {
        if (classes[i].contains('id:')) {
          var id = classes[i].split(":")[1];
        }
      }
      if (! id) {return;}
      
      a.addEvent('click',function() {
        if ($('details'+id).style.display == "none") {
          $('details'+id).style.display = "block";
          $('summary'+id).style.display = "none";
        } else if ($('details'+id).style.display == "block") {
          $('details'+id).style.display = "none";
          $('summary'+id).style.display = "block";
        }
      });
    
    });
    
    // Activate Study "Search" Autocomplete
    new Autocompleter.Ajax.Xhtml(
      $('study-search'),
      '../../xpress/extensions/ajax_autocomplete.php', {
      'postData':{'object':'Study','attr':'Title'},
      'postVar': 'needle',
      'target' : 'study_id',
      'parseChoices': function(el) {
        var value = el.getFirst().innerHTML;
        var id    = el.getFirst().id;
        el.inputValue = value;
        el.inputId    = id;
        this.addChoiceEvents(el).getFirst().setHTML(this.markQueryValue(value));
      }    
    });
  });