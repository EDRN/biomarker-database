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
            if ($('optbox'+idval)) {$('optbox'+idval).setStyle('z-index','10000');}
            $('options_content'+idval).effect('opacity',{duration:400, transition:Fx.Transitions.linear}).start(0,1);
          } else {
            // hide
            $('options_content'+idval).effect('opacity',{
              duration:200, 
              transition:Fx.Transitions.linear,onComplete:function(){
                $('options_content'+idval).setStyle('display','none');
                if($('optbox'+idval)) {$('optbox'+idval).setStyle('z-index','0');}
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
            $('resoptbox'+idval).setStyle('z-index','10000');
            $('res_options_content'+idval).effect('opacity',{duration:400, transition:Fx.Transitions.linear}).start(0,1);
          } else {
            // hide
            $('res_options_content'+idval).effect('opacity',{
              duration:200, 
              transition:Fx.Transitions.linear,onComplete:function(){
                $('res_options_content'+idval).setStyle('display','none');
                $('resoptbox'+idval).setStyle('z-index','0');
              }
            }).start(1,0);
          }
        });
    });
    
    // Activate publication mini-menus
    $$('.minimenu').each(function(ul){
    
      // Get the id
      var classes = ul.getProperty('class').split(" ");
      for (i=classes.length-1;i>=0;i--) {
        if (classes[i].contains('id:')) {
          var id = classes[i].split(":")[1];
        }
      }
      if (! id) {return;}
    
    
      var local_link  = ul.getChildren()[0].getFirst();
      var pubmed_link = ul.getChildren()[1].getFirst();
      var other_link  = ul.getChildren()[2].getFirst();
      local_link.addEvent('click',function() {
        $('div_pubmed'+id).setStyle('display','none');
        $('div_other'+id).setStyle('display','none');
        $('div_local'+id).setStyle('display','block');
        $('li_pubmed'+id).removeClass('activelink');
        $('li_other'+id).removeClass('activelink');
        $('li_local'+id).addClass('activelink');
      });
      pubmed_link.addEvent('click',function() {
        $('div_local'+id).setStyle('display','none');
        $('div_other'+id).setStyle('display','none');
        $('div_pubmed'+id).setStyle('display','block');
        $('li_local'+id).removeClass('activelink');
        $('li_other'+id).removeClass('activelink');
        $('li_pubmed'+id).addClass('activelink');
      });
      other_link.addEvent('click',function() {
        $('div_pubmed'+id).setStyle('display','none');
        $('div_local'+id).setStyle('display','none');
        $('div_other'+id).setStyle('display','block');
        $('li_pubmed'+id).removeClass('activelink');
        $('li_local'+id).removeClass('activelink');
        $('li_other'+id).addClass('activelink');
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
  
  
  
  function assocPub(bsdId,pubId,containerId) {
    new Ajax(
      './',{
      method:'post',
      data:'action=study_associate_publication&bsdId='+bsdId+'&pubId='+pubId,
      onSuccess:function(){
        var li = new Element('li').setHTML(this.response.text);
        li.injectInside(containerId);
      }
    }).request();
  }
  
  function dissocPub(bsdId,pubId,containerId) {
    new Ajax(
      'index.php',{
      method:'post',
      data:'action=study_dissociate_publication&bsdId='+bsdId+'&pubId='+pubId,
      onSuccess:function(){
        $(containerId).remove();
      }
    }).request();
  }
  
  function dissocRes(bsdId,resId,containerId) {
    new Ajax(
      './',{
      method:'post',
      data:'action=study_dissociate_resource&bsdId='+bsdId+'&resId='+resId,
      onSuccess:function(){
        $(containerId).remove();
      }
      }).request();
  }
