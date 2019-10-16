<?php
	// Include required CSS and JavaScript 
	echo $html->css('bmdb-objects');

	echo $html->css('dataTables/dataTables.css');
	echo $html->css('bmdb-browser');
	echo $html->css('bmdb-sites');

	echo $javascript->link('jquery/jquery-1.8.2.min.js');
	echo $javascript->link('jquery/jquery-ui/jquery-ui-1.10.3.custom.js');
	echo $javascript->link('jquery/plugins/dataTables/jquery.dataTables.min');
	echo $html->css('jquery-ui/jquery-ui-1.10.3.custom.min.css');
?>
<div class="menu">
	<!-- Breadcrumbs Area -->
	<div id="breadcrumbs">
	<a href="/<?php echo PROJROOT;?>/">Home</a> / Sites
	</div><!-- End Breadcrumbs -->
</div>
<table id="studyelements" class="dataTable">
<thead>
        <tr><th>Site_Id</th><th>Name</th></tr>
</thead>
</table>
<script>
$(document).ready(function() {
	$("#studyelements").dataTable({
		"bProcessing": true,
		"bServerSide": true,
		"bDeferRender": true,
		"sAjaxSource": "/bmdb/apis/sites_search",
		"oLanguage": {
			"oPaginate": {
				"sNext": "",
				"sPrevious": ""
			}
		},
		"aoColumns": [
			{"sWidth": "250px"},
			{"sWidth": "750px"},
		],
		"aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
	});
});
</script>
