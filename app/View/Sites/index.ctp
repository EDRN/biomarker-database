<?php
	// Include required CSS and JavaScript 
	echo $this->Html->css('bmdb-objects');

	echo $this->Html->css('dataTables/dataTables.css');
	echo $this->Html->css('bmdb-browser');
	echo $this->Html->css('bmdb-sites');

	echo $this->Html->script('jquery/jquery-1.8.2.min.js');
	echo $this->Html->script('jquery/jquery-ui/jquery-ui-1.10.3.custom.js');
	echo $this->Html->script('jquery/plugins/dataTables/jquery.dataTables.min');
	echo $this->Html->css('jquery-ui/jquery-ui-1.10.3.custom.min.css');
?>
<div class="menu">
	<!-- Breadcrumbs Area -->
	<div id="breadcrumbs">
	<a href="/">Home</a> / Sites
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
		"sAjaxSource": "/apis/sites_search",
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
