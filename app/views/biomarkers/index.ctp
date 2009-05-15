<?php
	// Include required CSS and JavaScript 
	echo $javascript->link('jquery/jquery-1.3.2.min');
	echo $javascript->link('jquery/plugins/dataTables/jquery.dataTables.min');

	
	echo $html->css('bmdb-objects');
	echo $html->css('eip');
	
	echo $html->css('dataTables/dataTables.css');
	echo $html->css('bmdb-browser');
?>
<div class="menu">
	<!-- Breadcrumbs Area -->
	<div id="breadcrumbs">
	<a href="/<?php echo PROJROOT;?>/">Home</a> / Biomarkers
	</div><!-- End Breadcrumbs -->
</div>
<div class="searcher">
	<a href="/<?php echo PROJROOT;?>/biomarkers/create">+ Create a New Biomarker</a>
</div>

<h2>Biomarker Directory:</h2>
<div class="hint" style="margin-top:-22px;margin-bottom:10px;color:#666;">
&nbsp;&nbsp;Browse the directory listing, or search any field using the search box on the right.
</div>

<br/>

<table id="biomarkerelements" class="dataTable" cellspacing="0" cellpadding="0">
<thead>
	<tr><th>Name</th><th style="display:none;">Aliases</th><th>QA State</th><th>Type</th><th>Panel</th><th>Associated Organs</th></tr>
</thead>
<tbody>
<?php
// Compute table cells
foreach ($biomarkers as $biomarker) {
  	// Build 'organsForBiomarker' list
	$odatas = array();
	foreach ($biomarker['OrganDatas'] as $od) {
		$odatas[] = "<a href=\"/".PROJROOT."/biomarkers/organs/{$biomarker['Biomarker']['id']}/{$od['OrganData']['id']}\">{$od['Organ']['name']}</a>"; 
	}
	$organsForBiomarker = implode(", ",$odatas);
	if ($organsForBiomarker == "") { $organsForBiomarker = "<em style=\"color:#888;\">Unknown</em>";}
	$biomarkerName   = $biomarker['DefaultName'];
	$biomarkerNames  = array();
	foreach ($biomarker['BiomarkerName'] as $n) {
		$biomarkerNames[] = $n['name'];
	} 
?>
<tr>
  <td><?php echo $html->link($biomarkerName,"/biomarkers/view/{$biomarker['Biomarker']['id']}");?></td>
  <td style="display:none;"><?php echo implode(" ", $biomarkerNames);?></td>
  <td><?php echo (($biomarker['Biomarker']['qastate'] == "")? "<em style=\"color:#888;\">Unknown</em>" : $biomarker['Biomarker']['qastate'])?></td>
  <td><?php echo (($biomarker['Biomarker']['type'] == "")? "<em style=\"color:#888;\">Unknown</em>" : $biomarker['Biomarker']['type'])?></td>
  <td><?php echo (($biomarker['Biomarker']['isPanel'] == 0) ? "No" : "Yes")?></td>
  <td><?php echo $organsForBiomarker?></td>
</tr>
<?php } ?>
</tbody>
</table>


<p>&nbsp;</p>
<p style="border-bottom:solid 2px #666;">&nbsp;</p>


<script type="text/javascript">

$(document).ready(function() {
	// Turn the table into a sortable, searchable table
	$("#biomarkerelements").dataTable();
	// Give the search box the initial focus
	$("#biomarkerelements_filter > input").focus();

});
</script>