<?php
	// Include required CSS and JavaScript 
	echo $this->Html->css('bmdb-objects');
	echo $this->Html->css('metrics');
?>

<div class="menu">
	<!-- Breadcrumbs Area -->
	<div id="breadcrumbs">
		<span style="color:#ddd;">You are here: &nbsp;</span>
		<a href="/">Home</a> :: 
		<span>Latest Changes</span>
	</div><!-- End Breadcrumbs -->
</div>
<div id="outer_wrapper">
<div id="main_section">

<div id="content">
<div class="metricSection">
	<h2>Biomarker Statistics</h2>
	<div class="rdflink">
	<a href="/rdf/biomarkers">Download as <img src="/img/RDF_icon.gif"/></a>
	</div>
	<span class="bigMetric"><?php echo $numBiomarkers?></span>
	<h4>Latest</h4>
	<ul>
	  <?php foreach ($latestBiomarkers as $b):?>
	  <li><?php echo $this->Html->link($b['DefaultName'],"/biomarkers/view/{$b['Biomarker']['id']}");?></a></li>
	  <?php endforeach ?>
	</ul>
</div>
<div class="metricSection">
	<h2>Protocol Statistics</h2>
	<div class="rdflink">
	<a href="/rdf/studies">Download as <img src="/img/RDF_icon.gif"/></a>
	</div>
	<span class="bigMetric"><?php echo $numProtocols?></span>
	<h4>Latest</h4>
	<ul>
	  <?php foreach ($latestProtocols as $p):?>
	  <li><?php echo $this->Html->link($p['Study']['title'],"/studies/view/{$p['Study']['id']}");?>
	  </li>
	  <?php endforeach ?>
	</ul>
</div>
<div class="metricSection last">
	<h2>Publication Statistics</h2>
	<div class="rdflink">
	<a href="/rdf/publications">Download as <img src="/img/RDF_icon.gif"/></a>
	</div>
	<span class="bigMetric"><?php echo $numPublications?></span>
	<h4>Latest</h4>
	<ul>
	  <?php foreach ($latestPublications as $p):?>
	  <li><?php echo $this->Html->link($p['Publication']['title'],"/publications/view/{$p['Publication']['id']}");?></a></li>
	  <?php endforeach ?>
	</ul>
</div>
<hr class="clear"/>
</div>
</div>
</div>