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
	<a href="/<?php echo PROJROOT;?>/">Home</a> / Terms
	</div><!-- End Breadcrumbs -->

</div>
<div class="searcher">
	<div>
	<form action="/<?php echo PROJROOT;?>/terms/redirection" method="POST">
		<input type="hidden" id="term_id" name="id" value=""/>
		<input type="text" id="term-search" value="Begin typing a term here..."/>
		<input type="submit" value="Search"/><br/>
		<a href="/<?php echo PROJROOT;?>/terms/define">Define a new Term</a>
		<div class="clr"><!--  --></div>
	</form>
	</div>
</div>
<h2>Term Directory:</h2>
<div class="hint" style="margin-top:-22px;margin-bottom:10px;color:#666;">
&nbsp;&nbsp;Browse the directory listing, or search for a term using the box on the right.
</div>

<br/>
