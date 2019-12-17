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
<blockquote>
	&quot;<?php echo $term['Term']['definition']?>&quot;
</blockquote>
<hr/>
<a href="/terms/edit/<?php echo $term['Term']['id']?>">Edit this Definition</a>
&nbsp;|&nbsp;
<a href="/terms/delete/<?php echo $term['Term']['id']?>">Delete this Definition</a>