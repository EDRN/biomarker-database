  window.addEvent('domready', function() {
  
    // Activate edit in place for sensitivity,specificity, etc
    new eip($$('.editable'), '../../ajax/ajax_update.php', {action: 'update'});
    new eiplist($$('.editablelist'),'../../ajax/ajax_update.php', {action: 'update'});
    
    // Activate "remove association" links
    $$('.remove').each(function(a){
      a.setProperty('onclick',
        'return confirm("All data relating this biomarker-organ pair and this study will be deleted. Proceed?");');
      });
      
    // Activate Associate Study switch
    $$('.optionswitch').each(function(a){
      // Get the id
      var classes = a.getProperty('class').split(" ");
      for (i=classes.length-1;i>=0;i--) {
        if (classes[i].contains('id:')) {
          var id = classes[i].split(":")[1];
        }
      }
      var idval = (id) ? id : '';
      
      a.addEvent('click',
        function() {
          if($('options_content'+idval).style.display == 'none') {
            // show
            new Fx.Style('options_content'+idval, 'opacity').set(0);
            $('options_content'+idval).setStyle('display','block');
            $('options_content'+idval).effect('opacity',{duration:400, transition:Fx.Transitions.linear}).start(0,1);
          } else {
            // hide
            $('options_content'+idval).effect('opacity',{
              duration:200, 
              transition:Fx.Transitions.linear,onComplete:function(){
                $('options_content'+idval).setStyle('display','none');
              }
            }).start(1,0);
          }
        });
    });
    
    // Activate Associate Resource switches
    $$('.resoptionswitch').each(function(a){
      // Get the id
      var classes = a.getProperty('class').split(" ");
      for (i=classes.length-1;i>=0;i--) {
        if (classes[i].contains('id:')) {
          var id = classes[i].split(":")[1];
        }
      }
      var idval = (id) ? id : '';
      
      a.addEvent('click',
        function() {
          if($('res_options_content'+idval).style.display == 'none') {
            // show
            new Fx.Style('res_options_content'+idval, 'opacity').set(0);
            $('res_options_content'+idval).setStyle('display','block');
            $('res_options_content'+idval).effect('opacity',{duration:400, transition:Fx.Transitions.linear}).start(0,1);
          } else {
            // hide
            $('res_options_content'+idval).effect('opacity',{
              duration:200, 
              transition:Fx.Transitions.linear,onComplete:function(){
                $('res_options_content'+idval).setStyle('display','none');
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

    // Activate all Associate Publication local-lookup boxes
    $$('.pubsearch').each(function(input){
      // Get the id
      var classes = input.getProperty('class').split(" ");
      for (i=classes.length-1;i>=0;i--) {
        if (classes[i].contains('id:')) {
          var id = classes[i].split(":")[1];
        }
      }
      var idval = (id) ? id : '';

      new Autocompleter.Ajax.Xhtml(
        $('publication'+idval+'search'),
        '../../xpress/extensions/ajax_autocomplete.php', {
          'postData':{'object':'Publication','attr':'Title'},
          'postVar': 'needle',
          'target' : 'publication'+idval+'_id',
          'parseChoices': function(el) {
            var value = el.getFirst().innerHTML;
            var id    = el.getFirst().id;
            el.inputValue = value;
            el.inputId    = id;
            this.addChoiceEvents(el).getFirst().setHTML(this.markQueryValue(value));
          }  
        });
    });
    
    // Activate all Associate Publication local-lookup buttons
    $$('.assoclocal').each(function(input){
      // Get the id
      var classes = input.getProperty('class').split(" ");
      for (i=classes.length-1;i>=0;i--) {
        if (classes[i].contains('id:')) {
          var id = classes[i].split(":")[1];
        }
      }
      if (! id) {return;}
      input.addEvent('click',function() {      
        assocPub(id,$('publication'+id+'_id').value,'pubs'+id);
      });
    });
    

  });
  
  
  
  function assocPub(bosdId,pubId,containerId) {
    new Ajax(
      './',{
      method:'post',
      data:'action=study_associate_publication&bosdId='+bosdId+'&pubId='+pubId,
      onSuccess:function(){
        var li = new Element('li').setHTML(this.response.text);
        li.injectInside(containerId);
      }
    }).request();
  }
  
  function dissocPub(bosdId,pubId,containerId) {
    new Ajax(
      'index.php',{
      method:'post',
      data:'action=study_dissociate_publication&bosdId='+bosdId+'&pubId='+pubId,
      onSuccess:function(){
        $(containerId).remove();
      }
    }).request();
  }
  
  function dissocRes(bosdId,resId,containerId) {
    new Ajax(
      './',{
      method:'post',
      data:'action=study_dissociate_resource&bosdId='+bosdId+'&resId='+resId,
      onSuccess:function(){
        $(containerId).remove();
      }
      }).request();
  }
