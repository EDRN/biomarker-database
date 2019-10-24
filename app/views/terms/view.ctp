<?php
	// Include required CSS and JavaScript
	echo $html->css('bmdb-objects');
	echo $html->css('eip');
	echo $html->script('mootools-release-1.11');
	echo $html->script('eip');

	echo $html->css('autocomplete');
	echo $html->script('autocomplete/Observer');
	echo $html->script('autocomplete/Autocompleter');
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
	&quot;<?php echo $term['Term']['definition']?>&quot;
</blockquote>
<hr/>
<a href="/<?php echo PROJROOT?>/terms/edit/<?php echo $term['Term']['id']?>">Edit this Definition</a>
&nbsp;|&nbsp;
<a href="/<?php echo PROJROOT?>/terms/delete/<?php echo $term['Term']['id']?>">Delete this Definition</a>