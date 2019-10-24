<?php
	// Include required CSS and JavaScript 
	echo $html->css('bmdb-objects');
	echo $html->css('eip');
	echo $html->script('mootools-release-1.11');
	echo $html->script('eip');

	echo $html->css('autocomplete');
	//echo $html->script('autocomplete/Observer');
	//echo $html->script('autocomplete/Autocompleter');

	echo $html->script('jquery/jquery-1.8.2.min.js');
	echo $html->script('jquery/jquery-ui/jquery-ui-1.10.3.custom.js');
	echo $html->css('jquery-ui/jquery-ui-1.10.3.custom.min.css');
?>
<div class="menu">
	<!-- Breadcrumbs Area -->
	<div id="breadcrumbs">
		<span>You are here: &nbsp;</span>
		<a href="/<?php echo PROJROOT;?>/">Home</a> :: 
		<a href="/<?php echo PROJROOT;?>/sites/">Sites</a> ::
		<span><?php echo $site['Site']['name']?> </span>
	</div><!-- End Breadcrumbs -->

</div>

<div id="outer_wrapper">
<div id="main_section">
<div id="content">
	<h2><?php echo $site['Site']['name']?></h2>
	<h5 id="urn">urn:edrn:study:<?php echo $site['Site']['site_id']?></h5>
</div>
</div>
</div>
