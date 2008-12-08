<?php
	// Include required CSS and JavaScript 
	echo $html->css('bmdb-objects');
	echo $html->css('eip');
	echo $javascript->link('mootools-release-1.11');
	echo $javascript->link('eip');

	echo $html->css('autocomplete');
	echo $javascript->link('autocomplete/Observer');
	echo $javascript->link('autocomplete/Autocompleter');
	echo $html->css('bmdb-browser');
?>
<div class="menu">
	<!-- Breadcrumbs Area -->
	<div id="breadcrumbs">
	<a href="/<?php echo PROJROOT;?>/">Home</a> /
	<a href="/<?php echo PROJROOT;?>/publications"> Publications</a> /
	<?php echo $publication['Publication']['title']?>

	</div><!-- End Breadcrumbs -->

</div>
<h2>View Publication Details:</h2>
<h3 style="margin-left:25px;"><?php echo $publication['Publication']['title']?></h3>
<table style="margin-left:23px;">
  <tr><td>Author: &nbsp;</td><td><?php echo $publication['Publication']['author']?></td></tr>
  <tr><td>Journal: &nbsp;</td><td><?php echo $publication['Publication']['journal']?></td></tr>
  <tr><td>Published: &nbsp;</td><td><?php echo $publication['Publication']['published']?></td></tr>
  <tr><td>PubMed Link:</td><td>
  	<?php if ($publication['Publication']['pubmed_id']== 0) { 
			echo 'N/A';
		  } 
		  else { 
			echo "<a href=\"http://ncbi.nlm.nih.gov/pubmed/{$publication['Publication']['pubmed_id']}\">http://ncbi.nlm.nih.gov/pubmed/{$publication['Publication']['pubmed_id']}</a>";
		  }
	?></td></tr>
</table>
<p>&nbsp;</p>