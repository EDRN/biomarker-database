// Formatted Text input
  function ti(id,name,label,style){
    document.write('<input type="text" style="' + 
      style + 'background:rgb(240,240,240);color:rgb(80,80,80);" ' +
      'id="' + id + '" ' + 
      'name="' + name + '" ' +
      'value="' + label + '" ' +
      'onfocus="this.style.color=\'rgb(0,0,0)\';this.style.background=\'rgb(255,255,255)\';this.value=\'\';\"' +
      'onblur="this.style.color=\'rgb(80,80,80)\';this.style.background=\'rgb(240,240,240)\';this.value = ((this.value == \'\')? \'' + label + '\' : this.value);"' +
      '/>');
  }
  /* 
   * Example of output:
   * input: ti('study_autocomplete','autocomplete_parameter','Search by Study Title...','width:350px;');
   * output:
   *
      <input type="text" 
            style="width:300px;background:rgb(240,240,240);color:rgb(80,80,80);" 
            id="study_autocomplete" 
            name="autocomplete_parameter" 
            value=" Search by Study Title..." 
            onFocus="this.style.color='rgb(0,0,0)';this.style.background='rgb(255,255,255)';this.value='';" 
            onBlur="this.style.color='rgb(80,80,80)';this.style.background='rgb(240,240,240)';alert(this.value);this.value = ((this.value=="")? ' Search by Study Title...' : this.value);"
      />
 */
 
 function noMatches(id) {
    $(id).style.background = 'rgb(200,30,30)';
 }
 function clearNoMatches(id) {
    $(id).style.background = 'rgb(255,255,255)';
 }
 function verifySelected(id,defaultValue){
    if(defaultValue == $(id).value) {
      return false;
    }
    return true;
 }
 
 //var TextInput = Class.create();
 
 