<?php
	// Include required CSS and JavaScript 
	echo $this->Html->css('bmdb-objects');
	echo $this->Html->css('eip');
	echo $this->Html->script('mootools-release-1.11');
	echo $this->Html->script('eip');

	echo $this->Html->css('autocomplete');
	//echo $this->Html->script('autocomplete/Observer');
	//echo $this->Html->script('autocomplete/Autocompleter');

	echo $this->Html->script('jquery/jquery-1.8.2.min.js');
	echo $this->Html->script('jquery/jquery-ui/jquery-ui-1.10.3.custom.js');
	echo $this->Html->css('jquery-ui/jquery-ui-1.10.3.custom.min.css');
?>
<div class="menu">
	<!-- Breadcrumbs Area -->
	<div id="breadcrumbs">
		<span>You are here: &nbsp;</span>
		<a href="/">Home</a> :: 
		<a href="/sites/">Sites</a> ::
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
