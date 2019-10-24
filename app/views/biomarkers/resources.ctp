<?php
	// Include required CSS and JavaScript
	echo $html->css('bmdb-objects');
	echo $html->css('eip');
	// Deprecated in Cake 1.3:
	//echo $javascript->link('mootools-release-1.11');
	//echo $javascript->link('eip');
	// use this instead:
	echo $html->script('mootools-release-1.11');
	echo $html->script('eip');

	echo $html->css('autocomplete');

	echo $html->script('jquery/jquery-1.8.2.min.js');
	echo $html->script('jquery/jquery-ui/jquery-ui-1.10.3.custom.js');
	echo $html->css('jquery-ui/jquery-ui-1.10.3.custom.min.css');
?>

<div class="menu">
	<!-- Breadcrumbs Area -->
	<div id="breadcrumbs">
		<span>You are here: &nbsp;</span>
		<a href="/<?php echo PROJROOT;?>/">Home</a> :: 
		<a href="/<?php echo PROJROOT;?>/biomarkers/">Biomarkers</a> ::
		<a href="/<?php echo PROJROOT;?>/biomarkers/view/<?php echo $biomarker['Biomarker']['id']?>"><?php echo $biomarkerName?><?php echo ((($biomarker['Biomarker']['isPanel']) == 1) ? ' (Panel)':'');?></a> : 
		<span>Resources</span>
	</div><!-- End Breadcrumbs -->
</div>
<div id="outer_wrapper">
<div id="main_section">
<div id="content">
<h2 class="biomarkerName"><?php echo $biomarkerName?> <?php echo ((($biomarker['Biomarker']['isPanel']) == 1) ? '(Panel)':'');?></h2>
<h5 id="urn">urn:edrn:biomarker:<?php echo $biomarker['Biomarker']['id']?></h5>
<h5>Created: <?php echo $biomarker['Biomarker']['created']?>. &nbsp;Last Modified: 
	<?php echo $biomarker['Biomarker']['modified']?>
</h5>
<div id="smalllinks">
		<ul>
		  <li class=""><a href="/<?php echo PROJROOT;?>/biomarkers/view/<?php echo $biomarker['Biomarker']['id']?>">Basics</a></li>
		  <li class=""><a href="/<?php echo PROJROOT;?>/biomarkers/organs/<?php echo $biomarker['Biomarker']['id']?>">Organs</a></li>
		  <li class=""><a href="/<?php echo PROJROOT;?>/biomarkers/studies/<?php echo $biomarker['Biomarker']['id']?>">Studies</a></li>
		  <li class=""><a href="/<?php echo PROJROOT;?>/biomarkers/publications/<?php echo $biomarker['Biomarker']['id']?>">Publications</a></li>
		  <li class="activeLink"><a href="/<?php echo PROJROOT;?>/biomarkers/resources/<?php echo $biomarker['Biomarker']['id']?>">Resources</a></li>
		</ul>
		<div class="clr"><!--  --></div>
</div>
<h4 style="margin-bottom:0px;margin-left:20px;background-color: transparent;font-size: 18px;">Associated Resources
	<div class="editlink">
	<span class="fakelink toggle:addstudyres">+ Add a Resource</a>
	</div>
</h4>
<div id="addstudyres" class="addstudyres fadeOut" style="margin-left:20px">
	<form action="/<?php echo PROJROOT;?>/biomarkers/addResource" method="POST" style="margin-top:5px;">
		<input type="hidden" name="biomarker_id"  value="<?php echo $biomarker['Biomarker']['id']?>"/>
		<div style="float:left;width:110px;color:#555;">URL:</div>
		<input type="text" style="width:70%;" name="url" value="http://"/><br/><br/>
		<div style="float:left;width:110px;color:#555;">Description:</div>
		<input type="text" name="desc" style="float:left;width:50%;"/>
		<input type="submit" name="associate_res" value="Associate" style="float:left;padding:2px;margin-right:0px;margin-left:6px;"/>
		<input type="button" value="Cancel" class="cancelbutton toggle:addstudyres" style="float:left;padding:2px;margin:0px;margin-right:0px;margin-left:6px;"/>
		
	</form>
	<div class="clr"><!-- clear --></div>
</div>
<ul style="margin-left:20px;margin-top:10px;font-size:90%;">
	<?php foreach ($biomarker['BiomarkerResource'] as $resource):?>
		<li><div class="studyressnippet">
				<a href="<?php echo $resource['URL']?>"><?php echo $resource['URL']?></a>&nbsp;&nbsp;<a style="color:#d55;font-size:90%" href="/<?php echo PROJROOT;?>/biomarkers/removeResource/<?php echo $biomarker['Biomarker']['id']?>/<?php echo $resource['id']?>">x Remove this association</a><br/>
				<span style="color:#555;font-size:90%;">
				<?php echo $resource['description']?>
				</span>
			</div>
		</li>
	<?php endforeach;?>
</ul>



</div>
</div>
</div>

<script type="text/javascript">
	$(function() {
		// Activate all Fake Links
		$('.fakelink').each(function(index){
			var classes = $(this).attr('class').split(/\s+/);

			for (i=classes.length-1;i>=0;i--) {
				if (classes[i].contains('toggle:')) {
					var toggle = classes[i].split(":")[1];
				}
			}
			var toggleval = (toggle) ? toggle : '';

			$(this).click(function() {
				var toggleTarget = '#' + toggle;

				if ($(toggleTarget).hasClass("fadeOut")) {
					// show
					$(toggleTarget).removeClass("fadeOut").addClass("fadeIn");
				} else {
					// hide
					$(toggleTarget).removeClass("fadeIn").addClass("fadeOut");
				}
			});
		});

		// Activate all Cancel Buttons
		$('.cancelbutton').each(function(index) {
			var classes = $(this).attr('class').split(/\s+/);
			for (i=classes.length-1;i>=0;i--) {
				if (classes[i].contains('toggle:')) {
					var toggle = classes[i].split(":")[1];
				}
			}

			var toggleval = (toggle) ? toggle : '';
			$(this).click(function() {
				var toggleTarget = '#' + toggle;
				if ($(toggleTarget).hasClass("fadeOut")) {
					// show
					$(toggleTarget).removeClass("fadeOut").addClass("fadeIn");
				} else {
					// hide
					$(toggleTarget).removeClass("fadeIn").addClass("fadeOut");
				}
			});
		});
	});		
</script>
