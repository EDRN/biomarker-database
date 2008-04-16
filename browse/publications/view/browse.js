window.addEvent('domready',function() {

  // Activate Study "Search" Autocomplete
  new Autocompleter.Ajax.Xhtml(
    $('publication-search'),
    '../../xpress/extensions/ajax_autocomplete.php', {
    'postData':{'object':'Publication','attr':'Title'},
    'postVar': 'needle',
    'target' : 'publication_id',
    'parseChoices': function(el) {
      if (el.getFirst()) {
        $('publication-search').setStyle('background-color','#fff');
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
        $('publication-search').setStyle('background-color','#fcc');
      }
   }
   });
});