// Formatted Text input field
  function ti(id,name,label,cssWidth){
    document.write('<input type="text" class="cwspTI" '+
      'style="width:'+cssWidth+'px;" '+
      'id="' + id + '" ' + 
      'name="' + name + '" ' +
      'value="' + label + '" ' +
      'onfocus="$(\''+id+'\').addClassName(\'focused\');" ' +
      'onblur="$(\''+id+'\').removeClassName(\'focused\');" ' +
      '/>');
  }
  
// Formatted Text input field with AJAX Autocomplete
  function ajaxti(id,label,cssWidth,handlerURL,objectType,fieldName,waitimgURL,resultName,fieldList){
      ti(id+'_autocomplete','autocomplete_parameter',label,cssWidth);
      document.write('<div id="'+id+'_autocomplete_choices" class="autocomplete">&nbsp;</div>');
      document.write('<input type="hidden" id="'+id+'AutocompleteID" name="'+resultName+'" value=""/>' 
                + '<span id="'+id+'indicator" style="display: none;">'
                + '  <img src="'+waitimgURL+'" alt="Working..." />'
                + '</span>');
      document.write('<script type="text/javascript">'
                + 'function '+id+'afterAutocomplete(field,element){'
                + '  $(\''+id+'AutocompleteID\').value = element.id;'
                + '}');
      document.write('new Ajax.Autocompleter("'+id+'_autocomplete", "'+id+'_autocomplete_choices", "'+handlerURL+'", {indicator: \''+id+'indicator\',parameters: \'objectType='+objectType+'&field='+fieldName+'&fieldList='+fieldList+'\',afterUpdateElement: '+id+'afterAutocomplete});'
                + '</script>');
  }

 
 