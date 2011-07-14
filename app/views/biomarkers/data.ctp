<?php
	// Include required CSS and JavaScript 
	echo $html->css('bmdb-objects');
	echo $html->css('eip');
	echo $javascript->link('mootools-release-1.11');
?>
<div class="menu">
	<!-- Breadcrumbs Area -->
	<div id="breadcrumbs">
		<span>You are here: &nbsp;</span>
		<a href="/<?php echo PROJROOT;?>/">Home</a> :: 
		<a href="/<?php echo PROJROOT;?>/biomarkers/">Biomarkers</a> ::
		<a href="/<?php echo PROJROOT;?>/biomarkers/view/<?php echo $biomarker['Biomarker']['id']?>"><?php echo $biomarkerName?><?php echo ((($biomarker['Biomarker']['isPanel']) == 1) ? ' (Panel)':'');?></a> : 
		<span>Datasets</span>
	</div><!-- End Breadcrumbs -->
</div>

<div id="outer_wrapper fullwidth">
<div id="main_section">
<div id="content" style="width:100%;">

		<h2 class="biomarkerName"><span id="name" class="editable object:biomarker id:<?php echo $biomarker['Biomarker']['id']?> attr:name"><?php echo $biomarkerName?></span> <?php echo ((($biomarker['Biomarker']['isPanel']) == 1) ? '(Panel)':'');?></h2>
		<h5 id="urn">urn:edrn:biomarker:<?php echo $biomarker['Biomarker']['id']?></h5>
		<h5>Created: <?php echo $biomarker['Biomarker']['created']?>. &nbsp;Last Modified: 
			<?php echo $biomarker['Biomarker']['modified']?></h5>

<div style="margin-top:40px;padding-left:5px;">
	<h3>eCAS Dataset Association Tool</h3>
	<p class="instructions">
	This page lets you can associate datasets from eCAS with this biomarker (<?php echo $biomarkerName?> (id: <?php echo $biomarker['Biomarker']['id']?>)). 
	The list of available eCAS datasets is on the left, and the list of eCAS datasets already associated with this biomarker is on the right. You
	can associate an eCAS dataset with this biomarker by selecting the dataset from the left, and clicking "Move Selected Right." Likewise, you can disassociate
	an eCAS dataset from this biomarker by selecting the dataset from the right, and clicking "Move Selected Left." Don't forget to click "Save Changes" when
	you have finished.
	</p>
	<strong>Note: </strong> Multiple entries can be moved at one time by holding down control (ctrl) on a PC (or command on a Mac) while clicking.
</div>

<div id="datasetSelection" style="display:block;">
	<div style="position:relative;margin:5px;margin-bottom:0px;font-weight:bold;color:#733;">
		Available Datasets
		<div style="position:absolute;right:0px;top:0px;">
			Associated Datasets
		</div>
	</div>
	<form action="/<?php echo PROJROOT;?>/biomarkers/updateDatasets" method="POST">
		<input type="hidden" name="biomarker_id" value="<?php echo $biomarker['Biomarker']['id']?>"/>
		<input type="hidden" name="values" id="dataset_values" value=""/>
		
		<select id="alldatasets" name="alldatasets" size="20"  multiple="MULTIPLE">
		<?php foreach ($availableDatasets as $pname => $pdata): ?>
			<option id="opt-<?php echo $pname;?>" value="<?php echo $pname;?>"><?php echo $pname;?></option>
		<?php endforeach;?>
		
		</select>
		<select id="selecteddatasets" name="selecteddatasets" size="20" multiple="MULTIPLE" style="float:right;">
		<?php foreach ($associatedDatasets as $dsname => $dsdata): ?>
			<option id="opt-<?php echo $dsname?>" value="<?php echo $dsname?>"><?php echo $dsname?></option>
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
    	var all = $('alldatasets');
    	for (i=0;i<all.options.length;i++) {
    		
    	  if(all.options[i].selected) {
    	    value= all.options[i].value;
    	    text = all.options[i].innerHTML;
    	    removeOption('alldatasets',value);
    	    addOption('selecteddatasets',value,text);
    	    i=-1; // reset to account for changed indices
    	  }
    	  
    	}	
    });
  
    
    $('moveleft').addEvent('click',function() {
    	var panel = $('selecteddatasets');
    	for (i=0;i<panel.options.length;i++) {
    	  if(panel.options[i].selected) {
    	    value= panel.options[i].value;
    	    text = panel.options[i].innerHTML;
    	    removeOption('selecteddatasets',value);
    	    addOption('alldatasets',value,text);
    	    i=-1; // reset to account for changed indices
    	  }
    	}
    }); 
    
    $('save').addEvent('click',function() {
        var str   = 'ignore';
    	var panel = $('selecteddatasets');
    	for (i=0;i<panel.options.length;i++) {
    		str += ','+panel.options[i].value;
    	}
    	$('dataset_values').value = str;
    });
    
    
});


</script>