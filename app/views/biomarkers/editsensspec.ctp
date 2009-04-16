<?php
	// Include required CSS and JavaScript 
	echo $html->css('bmdb-objects');
?>

<div class="menu">
	<!-- Breadcrumbs Area -->
	<div id="breadcrumbs">
		<span style="color:#ddd;">You are here: &nbsp;</span>
		<a href="/<?php echo PROJROOT;?>/">Home</a> :: 
		<a href="/<?php echo PROJROOT;?>/biomarkers/">Biomarkers</a> ::
		<a href="/<?php echo PROJROOT;?>/biomarkers/view/<?php echo $biomarker['Biomarker']['id']?>"><?php echo $biomarkerName?><?php echo ((($biomarker['Biomarker']['isPanel']) == 1) ? ' (Panel)':'');?></a> : 
		<span>Edit sensitivity-specificity data point:</span>
	</div><!-- End Breadcrumbs -->
</div>
<div id="outer_wrapper">
<div id="main_section">
<div id="content">
	<h2><?php echo $biomarkerName?> / <?php echo $study['Study']['title']?></h2>
	<form action="/<?php echo PROJROOT?>/biomarkers/doeditsensspec" method="POST">
		<input type="hidden" name="biomarker_id"   value="<?php echo $biomarker['Biomarker']['id']?>"/>
		<input type="hidden" name="organ_data_id"  value="<?php echo $organData['OrganData']['id']?>"/>
		<input type="hidden" name="sensitivity_id" value="<?php echo $sensitivity['Sensitivity']['id']?>"/>
		<input type="hidden" name="next_page"      value="<?php echo $next_page?>"/>
		<table class="associatedstudies">
		  <tr><th style="width:200px;">Field</th><th>Value</th></tr>
		  <tr><th>Sensitivity:</th><td><input type="text" name="sensitivity" value="<?php echo $sensitivity['Sensitivity']['sensitivity']?>"/>&nbsp;%</td></tr>
		  <tr><th>Specificity:</th><td><input type="text" name="specificity" value="<?php echo $sensitivity['Sensitivity']['specificity']?>"/>&nbsp;%</td></tr>
		  <tr><th>Prevalence:</th><td><input type="text" name="prevalence" value="<?php echo $sensitivity['Sensitivity']['prevalence']?>"/>&nbsp;%</td></tr>
	  	<tr><th>Notes:</th><td><textarea name="notes" style="width:95%;border-width:1px;padding:2px;height:80px;"><?php echo $sensitivity['Sensitivity']['notes']?></textarea></td></tr>
	  	<tr><td colspan="2" style="text-align:right;"><a class="fakelink" href="/<?php echo PROJROOT . "{$next_page}";?>">
	  		cancel &amp; return</a>&nbsp;&nbsp;&nbsp;<input type="submit" value="Save Changes"/></td>
		</table>
	</form>
	
</div>
</div>
</div>