<?php
	// Include required CSS and JavaScript 
	//echo $javascript->link('jquery/jquery-1.3.2.min');
	echo $javascript->link('jquery/jquery-1.8.2.min');
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
	<!--<tr><th>Name</th><th style="display:none;">Aliases</th><th>QA State</th><th>Type</th><th>Panel</th><th>Associated Organs</th></tr>-->
	<tr><th>Name</th><th>QA State</th><th>Type</th><th>Panel</th><th>Associated Organs</th></tr>
</thead>
<tbody>
</tbody>
</table>


<p>&nbsp;</p>
<p style="border-bottom:solid 2px #666;">&nbsp;</p>


<script type="text/javascript">
$(document).ready(function() {
	$("#biomarkerelements").dataTable({
		"bProcessing": true,
		"bServerSide": true,
		"bDeferRender": true,
		"sAjaxSource": "https://<?php echo $_SERVER['HTTP_HOST'];?>/bmdb/apis/biomarkers_search",
		"oLanguage": {
			"oPaginate": {
				"sNext": "",
				"sPrevious": ""
			}
		},
		"aoColumns": [
			{"sWidth": "50%"},
			{"sWidth": "15%"},
			{"sWidth": "15%"},
			{"sWidth": "10%"},
			{"sWidth": "15%"},
		],
		"aLengthMenu": [[-1, 10, 50], ["All", 10, 50]],
		"iDisplayLength": -1,
	});
});
</script>
