//Copyright (c) 2008, California Institute of Technology.
//ALL RIGHTS RESERVED. U.S. Government sponsorship acknowledged.
//
  window.addEvent('domready', function() {
    // Activate 'remove association' links
    $$('.remove').each(function(a){
      a.setProperty('onclick',
        'return confirm("This publication will no longer be related to this biomarker-organ pair. Proceed?");');
      });
    
    // Activate optionswitch  
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
    
    // Activate publication mini-menu
    $$('.minimenu').each(function(ul){
      var local_link  = ul.getChildren()[0].getFirst();
      var pubmed_link = ul.getChildren()[1].getFirst();
      var other_link  = ul.getChildren()[2].getFirst();
      local_link.addEvent('click',function() {
        $('div_pubmed').setStyle('display','none');
        $('div_other').setStyle('display','none');
        $('div_local').setStyle('display','block');
        $('li_pubmed').removeClass('activelink');
        $('li_other').removeClass('activelink');
        $('li_local').addClass('activelink');
      });
      pubmed_link.addEvent('click',function() {
        $('div_local').setStyle('display','none');
        $('div_other').setStyle('display','none');
        $('div_pubmed').setStyle('display','block');
        $('li_local').removeClass('activelink');
        $('li_other').removeClass('activelink');
        $('li_pubmed').addClass('activelink');
      });
      other_link.addEvent('click',function() {
        $('div_pubmed').setStyle('display','none');
        $('div_local').setStyle('display','none');
        $('div_other').setStyle('display','block');
        $('li_pubmed').removeClass('activelink');
        $('li_local').removeClass('activelink');
        $('li_other').addClass('activelink');
      });
    
    });

    // Activate Publication "Local Import" Autocomplete
    new Autocompleter.Ajax.Xhtml(
      $('publication-search'),
      '../../xpress/extensions/ajax_autocomplete.php', {
      'postData':{'object':'Publication','attr':'Title'},
      'postVar': 'needle',
      'target' : 'publication_id',
      'parseChoices': function(el) {
        var value = el.getFirst().innerHTML;
        var id    = el.getFirst().id;
        el.inputValue = value;
        el.inputId    = id;
        this.addChoiceEvents(el).getFirst().setHTML(this.markQueryValue(value));
      }    
    });
    
    // Activate PubMed search boxes
    $$('.pubmed_search').each(function(button){
      button.addEvent('click',function(){
        var form = this.getParent();
        form.send({
          'update':'pubmedresult',
          'onComplete':activateImportButton
        });
      });
    });
  });
  
  function activateImportButton() {
    // Activate all PubMed "Import Publication" buttons
    $$('.importPubMed').each(function(input){
      
      // Get the updateid
      var classes = input.getProperty('class').split(" ");
      for (i=classes.length-1;i>=0;i--) {
        if (classes[i].contains('updateid:')) {
          var updateid = classes[i].split(":")[1];
        }
      }

      var whichone = (updateid)? updateid : '';
      
      input.addEvent('click',function() {
        var tform = this.getParent();
        var udiv = $('pubmedresult'+whichone); 
        tform.send({
          update: 'pubmedresult'+whichone,
        });
      });
    });
  }