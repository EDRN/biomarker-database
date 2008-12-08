<?php echo $html->css('bmdb-browser');?>
<div class="menu">
	<!-- Breadcrumbs Area -->
	<div id="breadcrumbs">
	<a href="/<?php echo PROJROOT;?>/">Home</a> /
	<a href="/<?php echo PROJROOT;?>/biomarkers">Biomarkers</a> /
	<a href="/<?php echo PROJROOT;?>/biomarkers/create">Create a Biomarker</a> / 
	Error
	</div><!-- End Breadcrumbs -->
</div>

<h2>An Error Occurred</h2>
<div class="error" style="margin-left:20px;">

You must specify a name for the Biomarker. 
<a href="/<?php echo PROJROOT;?>/biomarkers/create">Click here to try again</a>
</div>
<p>&nbsp;</p>