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
	<a href="/terms">Terms</a> /
	<?php echo $term['Term']['label']?>
	</div><!-- End Breadcrumbs -->

</div>

<h2>Definition for <?php echo $term['Term']['label']?>:</h2>

<form method="post" action="/terms/edit">
 <input type="hidden" name="id" value="<?php echo $term['Term']['id']?>"/>
<table style="width:100%;">
 <tr><td><textarea name="definition" style="width:600px;height:80px;padding:6px;font-size:110%"><?php echo $term['Term']['definition']?></textarea></td></tr>
 <tr><td>&nbsp;</td><td><input type="submit" value="Update" style="padding:5px;"/>
 				&nbsp;  <a href="/terms/view/<?php echo $term['Term']['id']?>">cancel</a></td></tr>
</table>
</form>