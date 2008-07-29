//Copyright (c) 2008, California Institute of Technology.
//ALL RIGHTS RESERVED. U.S. Government sponsorship acknowledged.
//

var eip = new Class({

    /**
     * Initialize
     * @param elements els An array of elements.
     * @param string action the path to the file to target with form.
     * @param object params (optional) Any extra parameters you would like to send with the AJAX call.
     * @param object options (optional) Override the default classes with this.
     */
  initialize: function(els, action, params, options) {

    // Handle array of elements or single element
    if ($type(els) == 'array') {
      els.each(function(el){
        this.prepForm(el);
      }.bind(this));
    } else if ($type(els) == 'element') {
      this.prepForm(els);
    } else {
      return;
    }

    // Store the action (path to file) and params
    this.action = action;
    this.params = params;

    // Default options
    this.options = Object.extend({
      overCl: 'over',
      hiddenCl: 'hidden',
      editableCl: 'editable',
      textareaCl: 'textarea'
    }, options || {} );

    // Create the ability to store a form in the element
    Element.implement({form: Object});

  },


  /**
     * Add events to element
     * @param element el Your target element.
     */
  prepForm: function(el) {

    var obj = this;
    el.addEvents({
      'mouseover': function(){this.addClass(obj.options.overCl);},
      'mouseout': function(){this.removeClass(obj.options.overCl);},
      'click': function(){obj.showForm(this);}
    });

  },


  /**
     * Build and/or show form
     * @param element el Your target element.
     */
  showForm: function(el) {

    // Get the objecttype, id, and attribute from your element
    var classes = el.getProperty('class').split(" ");
    for (i=classes.length-1;i>=0;i--) {
      if (classes[i].contains('object:')) {
        var objType = classes[i].split(":")[1];
      } else if (classes[i].contains('id:')) {
        var id = classes[i].split(":")[1];
      } else if (classes[i].contains('attr:')) {
        var attr = classes[i].split(":")[1];
      }
    }
    

    // Hide your target element
    el.addClass(this.options.hiddenCl);

    // If the form exists already, let's show that
    if (el.form) {
      el.form.removeClass(this.options.hiddenCl);
      el.form[target].focus();
      return;
    }

    // Create new form
    var form = new Element('form', {
      'id': 'form_' + el.getProperty('id'),
      'action': this.action,
      'class': this.options.editableCl
    });

    // Store new form in the element
    el.form = form;

    // Create a textarea or input for user
    if (el.hasClass(this.options.textareaCl)) {
      var input = new Element('textarea', {
        'name': attr,
        'class': 'eiptextarea'
      }).injectInside(form);
      input.appendText(el.innerHTML);
    } else {
      var input = new Element('input', {
        'name': attr,
        'value': el.innerHTML
      }).injectInside(form);
    }
    // Need this to pass to the buttons
    var obj = this;

    // Add a submit button
    new Element('input', {
      'type': 'submit',
      'value': 'save',
      'events': {
        'click': function(evt){
          (new Event(evt)).stop();
          el.empty();
          el.appendText('saving...');
          obj.hideForm(form, el);
          form.send({update: el});
        }
      }
    }).injectInside(form);

    // Add a cancel button
    new Element('input', {
      'type': 'button',
      'value': 'cancel',
      'events': {
        'click': function(form, el){
          obj.hideForm(form, el);
        }.pass([form, el])
      }
    }).injectInside(form);

    // For every param, add a hidden input
    for (param in this.params) {
      new Element('input', {
        'type': 'hidden',
        'name': param,
        'value': this.params[param]
      }).injectInside(form);
    }

    //
    new Element('input', {
      'type': 'hidden',
      'name': 'id',
      'value': id
    }).injectInside(form);
    
    new Element('input', {
      'type': 'hidden',
      'name': 'object',
      'value': objType
    }).injectInside(form);
    
    new Element('input', {
      'type': 'hidden',
      'name': 'attr',
      'value': attr
    }).injectInside(form);

    // Add the form after the target element
    form.injectAfter(el);

    // Focus on the input
    input.focus();
    input.select();
  },

  /**
     * Hide form
     * @param element form Your target form.
     * @param element el Your target element.
     */
  hideForm: function(form, el) {
    form.addClass(this.options.hiddenCl);
    el.removeClass(this.options.hiddenCl);
  }

});


var eiplist = new Class({

    /**
     * Initialize
     * @param elements els An array of elements.
     * @param string action the path to the file to target with form.
     * @param object params (optional) Any extra parameters you would like to send with the AJAX call.
     * @param object options (optional) Override the default classes with this.
     */
  initialize: function(els, action, params, options) {

    // Handle array of elements or single element
    if ($type(els) == 'array') {
      els.each(function(el){
        this.prepForm(el);
      }.bind(this));
    } else if ($type(els) == 'element') {
      this.prepForm(els);
    } else {
      return;
    }

    // Store the action (path to file) and params
    this.action = action;
    this.params = params;

    // Default options
    this.options = Object.extend({
      overCl: 'over',
      hiddenCl: 'hidden',
      editableCl: 'editable',
      textareaCl: 'textarea'
    }, options || {} );

    // Create the ability to store a form in the element
    Element.implement({form: Object});

  },


  /**
     * Add events to element
     * @param element el Your target element.
     */
  prepForm: function(el) {

    var obj = this;
    el.addEvents({
      'mouseover': function(){this.addClass(obj.options.overCl);},
      'mouseout': function(){this.removeClass(obj.options.overCl);},
      'click': function(){obj.showForm(this);}
    });

  },


  /**
     * Build and/or show form
     * @param element el Your target element.
     */
  showForm: function(el) {

    // Get the objecttype, id, and attribute from your element
    var classes = el.getProperty('class').split(" ");
    for (i=classes.length-1;i>=0;i--) {
      if (classes[i].contains('object:')) {
        var objType = classes[i].split(":")[1];
      } else if (classes[i].contains('id:')) {
        var id = classes[i].split(":")[1];
      } else if (classes[i].contains('attr:')) {
        var attr = classes[i].split(":")[1];
      } else if (classes[i].contains('opts:')) {
        var opts = classes[i].split(":")[1];
      }
    }
    

    // Hide your target element
    el.addClass(this.options.hiddenCl);

    // If the form exists already, let's show that
    if (el.form) {
      el.form.removeClass(this.options.hiddenCl);
      el.form[target].focus();
      return;
    }

    // Create new form
    var form = new Element('form', {
      'id': 'form_' + el.getProperty('id'),
      'action': this.action,
      'class': this.options.editableCl
    });

    // Store new form in the element
    el.form = form;
    
    // Create a select box for user
    var select = new Element('select', {
      'name': attr
    }).injectInside(form);
    // Create options
    var optlist = opts.split("|");
    for(i=0;i<optlist.length;i++) {
      if (optlist[i] == el.innerHTML) {
        var option = new Element('option', {
          'value': optlist[i].replace("_"," "),
          'selected': 'selected'
        });
      } else {
        var option = new Element('option', {
          'value': optlist[i].replace("_"," ")
       });
     }
      option.injectInside(select);
      option.appendText(optlist[i].replace("_"," "));
    }
    
    // Need this to pass to the buttons
    var obj = this;

    // Add a submit button
    new Element('input', {
      'type': 'submit',
      'value': 'save',
      'events': {
        'click': function(evt){
          (new Event(evt)).stop();
          el.empty();
          el.appendText('saving...');
          obj.hideForm(form, el);
          form.send({update: el});
        }
      }
    }).injectInside(form);

    // Add a cancel button
    new Element('input', {
      'type': 'button',
      'value': 'cancel',
      'events': {
        'click': function(form, el){
          obj.hideForm(form, el);
        }.pass([form, el])
      }
    }).injectInside(form);

    // For every param, add a hidden input
    for (param in this.params) {
      new Element('input', {
        'type': 'hidden',
        'name': param,
        'value': this.params[param]
      }).injectInside(form);
    }

    //
    new Element('input', {
      'type': 'hidden',
      'name': 'id',
      'value': id
    }).injectInside(form);
    
    new Element('input', {
      'type': 'hidden',
      'name': 'object',
      'value': objType
    }).injectInside(form);
    
    new Element('input', {
      'type': 'hidden',
      'name': 'attr',
      'value': attr
    }).injectInside(form);

    // Add the form after the target element
    form.injectAfter(el);

    // Focus on the input
    select.focus();
  },

  /**
     * Hide form
     * @param element form Your target form.
     * @param element el Your target element.
     */
  hideForm: function(form, el) {
    form.addClass(this.options.hiddenCl);
    el.removeClass(this.options.hiddenCl);
  }

});
