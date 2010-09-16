<?php
	// Include required CSS and JavaScript 
	echo $html->css('bmdb-objects');
	echo $html->css('eip');
	echo $javascript->link('mootools-release-1.11');
?>
<div style="margin-top:40px;padding-left:5px;">
<h2>Access Control / Security</h2>
Now viewing the Access Control List for:
<table style="margin:5px;">
  <tr><th style="text-align:left;">Object Type:</th><td><?php echo $objectType?>
  	&nbsp;(<a style="color:#6ae;text-decoration:underline;" href="/<?php echo PROJROOT . "/{$returnTo}"?>">Return to object's page</a>)</td></tr>
  <tr><th style="text-align:left;">Object Id:</th><td><?php echo $objectId?></td></tr>
</table>
</div>

<div id="groupSelection" style="display:block;">
	<div style="position:relative;margin:5px;margin-bottom:0px;font-weight:bold;color:#733;">
		Available Groups
		<div style="position:absolute;right:0px;top:0px;">
			Applied Groups
		</div>
	</div>
	<form action="/<?php echo PROJROOT;?>/acls/store" method="POST">
		<input type="hidden" name="object_type" value="<?php echo $objectType?>"/>
		<input type="hidden" name="object_id" value="<?php echo $objectId?>"/>
		<input type="hidden" name="return_to" value="<?php echo $returnTo?>"/>
		<input type="hidden" name="values" id="group_values" value=""/>
		
		<select id="allGroups" name="allGroups" size="20"  multiple="MULTIPLE">
		<?php foreach ($availableGroups as $gId => $gName): ?>
			<option id="opt-<?php echo $gName;?>" value="<?php echo $gName;?>"><?php echo $gName;?></option>
		<?php endforeach;?>
		
		</select>
		<select id="selectedGroups" name="selectedGroups" size="20" multiple="MULTIPLE" style="float:right;">
		<?php foreach ($selectedGroups as $gId => $gName): ?>
			<option id="opt-<?php echo $gName?>" value="<?php echo $gName?>"><?php echo $gName?></option>
		<?php endforeach;?>
		</select>
		<div style="clear:both;"><!-- clear --></div>
		<div id="panelSelectionButtons">
		  <input id="moveright" type="button" value="Move Selected Right" style="margin-left:auto;"/>&nbsp;
		  <input id="save"      type="submit" value="Save Changes"/>&nbsp;
		  <input id="moveleft"  type="button" value="Move Selected Left" style="margin-ight:auto;"/>
		</div> 
	</form>
</div>

<script type="text/javascript">

	window.addEvent('domready', function() {

	function addOption(which,value,text) {
    	var o = new Element('option',{'id': 'opt-'+value, 'value': value});
    	o.innerHTML = text;
    	o.inject(which);
    }
    
    function removeOption(which,value) {
        sel = $(which);
        for(i=0;i<sel.options.length;i++){
        	if(sel.options[i].value == value) {
        		sel.options[i] = null;
        		break;
        	}
        }
    }
    
    $('moveright').addEvent('click',function() {
    	var all = $('allGroups');
    	for (i=0;i<all.options.length;i++) {
    		
    	  if(all.options[i].selected) {
    	    value= all.options[i].value;
    	    text = all.options[i].innerHTML;
    	    removeOption('allGroups',value);
    	    addOption('selectedGroups',value,text);
    	    i=-1; // reset to account for changed indices
    	  }
    	  
    	}	
    });
  
    
    $('moveleft').addEvent('click',function() {
    	var panel = $('selectedGroups');
    	for (i=0;i<panel.options.length;i++) {
    	  if(panel.options[i].selected) {
    	    value= panel.options[i].value;
    	    text = panel.options[i].innerHTML;
    	    removeOption('selectedGroups',value);
    	    addOption('allGroups',value,text);
    	    i=-1; // reset to account for changed indices
    	  }
    	}
    }); 
    
    $('save').addEvent('click',function() {
        var str   = 'ignore';
    	var panel = $('selectedGroups');
    	for (i=0;i<panel.options.length;i++) {
    		str += ','+panel.options[i].value;
    	}
    	$('group_values').value = str;
    });
    
    
});


</script>