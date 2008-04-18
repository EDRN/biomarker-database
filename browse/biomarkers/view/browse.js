//Copyright (c) 2008, California Institute of Technology.
//ALL RIGHTS RESERVED. U.S. Government sponsorship acknowledged.
//

window.addEvent('domready',function() {

  // Activate Study "Search" Autocomplete
  new Autocompleter.Ajax.Xhtml(
    $('biomarker-search'),
    '../../xpress/extensions/ajax_autocomplete.php', {
    'postData':{'object':'Biomarker','attr':'Title'},
    'postVar': 'needle',
    'target' : 'biomarker_id',
    'parseChoices': function(el) {
      if (el.getFirst()) {
        $('biomarker-search').setStyle('background-color','#fff');
        var value = el.getFirst().innerHTML;
        var id    = el.getFirst().id;
        el.inputValue = value;
        el.inputId    = id;
        this.addChoiceEvents(el).getFirst().setHTML(this.markQueryValue(value));
      } else {
        var value = '';
        var id    = -1;
        el.inputValue = value;
        el.inputId    = id;
        $(this.options.target).setProperty('value',el.inputId);
        $('biomarker-search').setStyle('background-color','#fcc');
      }
   }
   });
});