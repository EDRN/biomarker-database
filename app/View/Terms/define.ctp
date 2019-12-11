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
	<a href="/">Home</a> / Terms
	</div><!-- End Breadcrumbs -->

</div>

<h2>Define a New Term:</h2>

<form method="post" action="/terms/define">
<table style="width:100%;">
  <tr><th style="width:200px;">Label:</th><td><input type="text" name="label" style="width:400px;padding:6px;font-size:110%"/></td></tr>
  <tr><th>Definition:</th><td><textarea name="definition" style="width:600px;height:80px;padding:6px;font-size:110%"></textarea></td></tr>
  </tr>
  <tr><td>&nbsp;</td><td><input type="submit" value="Add to Glossary" style="padding:5px;"/></td></tr>
</table>
</form>