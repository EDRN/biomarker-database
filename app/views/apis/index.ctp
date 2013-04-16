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
	<a href="/<?php echo PROJROOT;?>/">Home</a> / APIs
	</div><!-- End Breadcrumbs -->
</div>

<h2>Biomarker Database API Directory:</h2>
<div class="hint" style="margin-top:-22px;margin-bottom:10px;color:#666;">
&nbsp;&nbsp;Browse the API directory listing to see what BMDB data can be accessed via these services

</div>

<br/>

<table id="biomarkerelements" class="dataTable" cellspacing="0" cellpadding="0">
<thead>
	<tr><th>Name</th><th style="width:400px;">Description</th><th>Access</th><th>Endpoint</th></tr>
</thead>
<tbody>
<tr>
  <td><a href="/<?php echo PROJROOT;?>/apis/biomarkers">Biomarker Summary Information</a></td>
  <td>High-level information about all biomarkers in the database. Research-sensitive information is omitted from the data returned.</td>
  <td>Public</td>
  <td><a href="/<?php echo PROJROOT;?>/apis/biomarkers">./apis/biomarkers</a></td>
</tr>


</tbody>
</table>


<p>&nbsp;</p>
<p style="border-bottom:solid 2px #666;">&nbsp;</p>


<script type="text/javascript">

$(document).ready(function() {
	// Turn the table into a sortable, searchable table
	$("#biomarkerelements").dataTable({
		// Fix the incorrect pagination button display by removing the link text. Otherwise, the pagination
		// button image is overlapped by the text and made unusable. 
		"oLanguage": {
                        "oPaginate": {
                                "sNext": "",
                                "sPrevious": ""
                        }
                },
	});
	// Give the search box the initial focus
	$("#biomarkerelements_filter > input").focus();

});
</script>
