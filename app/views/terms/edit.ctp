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
	<a href="/<?php echo PROJROOT;?>/terms">Terms</a> /
	<?php echo $term['Term']['label']?>
	</div><!-- End Breadcrumbs -->

</div>

<h2>Definition for <?php echo $term['Term']['label']?>:</h2>

<form method="post" action="/<?php echo PROJROOT;?>/terms/edit">
 <input type="hidden" name="id" value="<?php echo $term['Term']['id']?>"/>
<table style="width:100%;">
 <tr><td><textarea name="definition" style="width:600px;height:80px;padding:6px;font-size:110%"><?php echo $term['Term']['definition']?></textarea></td></tr>
 <tr><td>&nbsp;</td><td><input type="submit" value="Update" style="padding:5px;"/>
 				&nbsp;  <a href="/<?php echo PROJROOT?>/terms/view/<?php echo $term['Term']['id']?>">cancel</a></td></tr>
</table>
</form>