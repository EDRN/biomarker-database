<?php
	// Include required CSS and JavaScript 
	echo $html->css('bmdb-objects');
	echo $html->css('metrics');
?>

<div class="menu">
	<!-- Breadcrumbs Area -->
	<div id="breadcrumbs">
		<span style="color:#ddd;">You are here: &nbsp;</span>
		<a href="/<?php echo PROJROOT;?>/">Home</a> :: 
		<span>Latest Changes</span>
	</div><!-- End Breadcrumbs -->
</div>
<div id="outer_wrapper">
<div id="main_section">

<div id="content">
<div class="metricSection">
	<h2>Biomarker Statistics</h2>
	<span class="bigMetric"><?php echo $numBiomarkers?></span>
	<h4>Latest</h4>
	<ul>
	  <?php foreach ($latestBiomarkers as $b):?>
	  <li><?php echo $html->link($b['DefaultName'],"/biomarkers/view/{$b['Biomarker']['id']}");?></a></li>
	  <?php endforeach ?>
	</ul>
</div>
<div class="metricSection">
	<h2>Protocol Statistics</h2>
	<span class="bigMetric"><?php echo $numProtocols?></span>
	<h4>Latest</h4>
	<ul>
	  <?php foreach ($latestProtocols as $p):?>
	  <li><?php echo $html->link($p['Study']['title'],"/studies/view/{$p['Study']['id']}");?>
	  </li>
	  <?php endforeach ?>
	</ul>
</div>
<div class="metricSection last">
	<h2>Publication Statistics</h2>
	<span class="bigMetric"><?php echo $numPublications?></span>
	<h4>Latest</h4>
	<ul>
	  <?php foreach ($latestPublications as $p):?>
	  <li><?php echo $html->link($p['Publication']['title'],"/publications/view/{$p['Publication']['id']}");?></a></li>
	  <?php endforeach ?>
	</ul>
</div>
<hr class="clear"/>
</div>
</div>
</div>