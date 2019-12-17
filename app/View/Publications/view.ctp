<?php
	// Include required CSS and JavaScript 
	echo $this->Html->css('bmdb-objects');
	echo $this->Html->css('eip');
	echo $this->Html->script('mootools-release-1.11');
	echo $this->Html->script('eip');

	echo $this->Html->css('autocomplete');
	echo $this->Html->script('autocomplete/Observer');
	echo $this->Html->script('autocomplete/Autocompleter');
	echo $this->Html->css('bmdb-browser');
?>
<div class="menu">
	<!-- Breadcrumbs Area -->
	<div id="breadcrumbs">
	<a href="/">Home</a> /
	<a href="/publications"> Publications</a> /
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