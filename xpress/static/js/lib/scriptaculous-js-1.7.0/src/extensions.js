/* Javascript extensions to the script.aculo.us toolkit */

/* 
 * Added on 2007/04/19 
 * Code to force InPlaceCollectionEditors to recognize callback 
 * functions
 *
**/
Ajax.InPlaceCollectionEditor.prototype.__createEditField = Ajax.InPlaceCollectionEditor.prototype.createEditField;
Ajax.InPlaceCollectionEditor.prototype = Object.extend(Ajax.InPlaceCollectionEditor.prototype, {
    createEditField: function() {
        if (this.options.callback) { var callbackSet = this.options.callback };
        this.__createEditField();
        if (callbackSet) { this.options.callback = callbackSet;    };
    }
});

Ajax.Autocompleter.prototype = Object.extend(Ajax.Autocompleter.prototype,{
  onComplete: function(request) {
      if (request.responseText == '<ul></ul>'){
        alert('No Matches Found!');
      };
      this.updateChoices(request.responseText);
  }
});
