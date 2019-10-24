<?php
	// Include required CSS and JavaScript 
	echo $html->css('bmdb-objects');
	echo $html->css('eip');
    // Deprecated in Cake 1.3:
  //echo $javascript->link('mootools-release-1.11');
    // Instead use:
    echo $html->script('mootools-release-1.11');
?>
<div class="menu">
	<!-- Breadcrumbs Area -->
	<div id="breadcrumbs">
		<span style="color:#ddd;">You are here: &nbsp;</span>
		<a href="/<?php echo PROJROOT;?>/">Home</a> :: Bulk Manage Data Security Settings
	</div><!-- End Breadcrumbs -->
</div>
<div id="outer_wrapper">
<div id="main_section" style="padding:5px;">
<h2>Manage Data Security Settings</h2>
<?php if ($message != ''):?>
	<div style="border:solid 1px green;background-color:#cfc;padding:5px;">
		<?php echo $message;?>
	</div>
<?php endif;?>
<div style="padding:5px;margin-right:2px;margin-top:5px;border:solid 1px gold;background-color:#ffc">
<strong>Note: </strong> Data Security settings can be set on an object-by-object basis by visiting the corresponding
object's page and clicking on "Set Security".
</div>
<fieldset>
  <legend>Bulk Grant Access</legend>
<form action="/<?php echo PROJROOT?>/acls/bulkGrant" method="POST">
  	Grant access to all objects of type 
  		<select name="objectType">
  		  <option name="biomarker">Biomarker</option>
  		  <option name="biomarkerorgan">BiomarkerOrgan</option>
  		</select> to 
  	all members of 
  		<select name="group">
  			<?php foreach ($allgroups as $gId => $gName):?>
  				<option value="<?php echo $gName?>"><?php echo $gName?></option>
  			<?php endforeach;?>
  		</select>
  		<input type="submit" value="Confirm"/>
</form>
</fieldset>
<fieldset>
  <legend>Revoke Access</legend>
<form action="/<?php echo PROJROOT?>/acls/bulkRevoke" method="POST">
  	Revoke access to all objects of type 
  		<select name="objectType">
  		  <option name="biomarker">Biomarker</option>
  		  <option name="biomarkerorgan">BiomarkerOrgan</option>
  		</select> by 
  	all members of 
  		<select name="group">
  			<?php foreach ($allgroups as $gId => $gName):?>
  				<option value="<?php echo $gName?>"><?php echo $gName?></option>
  			<?php endforeach;?>
  		</select>
  		<input type="submit" value="Confirm"/>
</form>
</fieldset>




</div>
</div>