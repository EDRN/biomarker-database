<?php echo $html->css('frozenobject');?>
<?php echo $html->css('eip');?>
<?php echo $html->css('autocomplete');?>
<?php echo $javascript->link('mootools-release-1.11');?>
<?php echo $javascript->link('eip');?>
<?php echo $javascript->link('autocomplete/Observer');?>
<?php echo $javascript->link('autocomplete/Autocompleter');?>
<?php echo $html->css('frozenbrowser');?>
<?php 
	function printor($value,$alt,$no_zero = false) {
		if ($no_zero && $value == 0) {
			echo $alt;
			return;
		}

		if ($value == "") {
			echo $alt;
		} else {
			echo $value;
		}
	}

?>
<?php echo $html->css('frozenbrowser');?>
<div class="menu">
	<div class="mainContent">
		<h2 class="title">EDRN Biomarker Database</h2>
	</div>
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