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
<blockquote>
	<?php echo $term['Term']['definition']?>
</blockquote>
<hr/>
<a href="/<?php echo PROJROOT?>/terms/edit/<?php echo $term['Term']['id']?>">Edit this Definition</a>