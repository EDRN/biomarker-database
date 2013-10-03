<?php
	// Include required CSS and JavaScript
	echo $html->css('bmdb-objects');
	echo $html->css('eip');
	echo $javascript->link('mootools-release-1.11');
	echo $javascript->link('eip');

	echo $html->css('autocomplete');

	echo $javascript->link('jquery/jquery-1.8.2.min.js');
	echo $javascript->link('jquery/jquery-ui/jquery-ui-1.10.3.custom.js');
	echo $html->css('jquery-ui/jquery-ui-1.10.3.custom.min.css');
?>
<div class="menu">
	<!-- Breadcrumbs Area -->
	<div id="breadcrumbs">
		<span>You are here: &nbsp;</span>
		<a href="/<?php echo PROJROOT;?>/">Home</a> :: 
		<a href="/<?php echo PROJROOT;?>/biomarkers/">Biomarkers</a> ::
		<a href="/<?php echo PROJROOT;?>/biomarkers/view/<?php echo $biomarker['Biomarker']['id']?>"><?php echo $biomarkerName?><?php echo ((($biomarker['Biomarker']['isPanel']) == 1) ? ' (Panel)':'');?></a> : 
		<span>Publications</span>
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
		  <li class="activeLink"><a href="/<?php echo PROJROOT;?>/biomarkers/publications/<?php echo $biomarker['Biomarker']['id']?>">Publications</a></li>
		  <li class=""><a href="/<?php echo PROJROOT;?>/biomarkers/resources/<?php echo $biomarker['Biomarker']['id']?>">Resources</a></li>
		</ul>
		<div class="clr"><!--  --></div>
</div>
<h4 style="margin-bottom:0px;margin-left:20px;background-color: transparent;font-size: 18px;">Associated Publications
<div class="editlink">
<span class="fakelink toggle:addstudypub">+ Add a Publication</span>
</div>
</h4>
</h3>
<div id="addstudypub" class="addstudypub fadeOut">
	<form action="/<?php echo PROJROOT;?>/biomarkers/addPublication" method="POST">
		<input type="hidden" name="biomarker_id"  value="<?php echo $biomarker['Biomarker']['id']?>"/>
		<input type="hidden" id="publication_id" name="pub_id" value=""/>
		<input type="text" id="publicationsearch" value="" style="width:90%;"/><br/>
		<div>
			<span class="hint" style="float:left;margin-top:3px;">Begin typing a publication title. A list of options will appear.<br/>
			Don't see the publication you want? <a href="/<?php echo PROJROOT;?>/publications/import">Import a new publication</a></span>
			<input type="button" class="cancelbutton toggle:addstudypub" value="Cancel" style="float:right;padding:2px;margin:6px;margin-right:0px;"/>
			<input type="submit" name="associate_pub" value="Associate" style="float:right;padding:2px;margin:6px;margin-right:0px;"/>
			<div class="clr"><!-- clear --></div>
		</div>
		
	</form>
	<div class="clr"><!-- clear --></div>
</div>
<ul style="margin-left:20px;margin-top:10px;font-size:90%;">
<?php foreach ($biomarker['Publication'] as $publication):?>
	<li><div class="studypubsnippet">
			<a href="/<?php echo PROJROOT?>/publications/view/<?php echo $publication['id']?>"><?php echo $publication['title']?></a> &nbsp;[<a href="/<?php echo PROJROOT;?>/biomarkers/removePublication/<?php echo $biomarker['Biomarker']['id']?>/<?php echo $publication['id']?>">Remove this association</a>]<br/>
			<span style="color:#555;font-size:90%;">Author:
			<?php echo $publication['author']?>. &nbsp; Published in
			<?php echo $publication['journal']?>, &nbsp;
			<?php echo $publication['published']?>, &nbsp;
                        Id: <a href="http://www.ncbi.nlm.nih.gov/pubmed/<?php echo $publication['pubmed_id']?>"><?php echo $publication['pubmed_id']?></a></span>
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

		// Activate publication search links
		$('#publicationsearch').each(function() {
			$(this).autocomplete({
				source: '/<?php echo PROJROOT;?>/biomarkers/getAutocompletePublications',
				select: function(event, ul) {
					var studyName = ul.item.value.split('|')[0];
					var studyId = ul.item.value.split('|')[1];
					$(this).siblings("[name='pub_id']").val(studyId);
					ul.item.label = studyName;
					ul.item.value = studyName;
				}
			});
		});

		// Set custom rendering function for the autocomplete elements . We need to remove
		// the additional information passed along with the name that is preset after a pipe
		// before drawing the elements. We also highlight the matching substring in each results.
		$.ui.autocomplete.prototype._renderItem = function(ul, item) {
			// Strip out the info we want
			var newLabel = item.label.split("|")[0];

			// Highlight the substring
			var re = new RegExp('(' + this.term + ')', 'i');
			var highlightedLabel = newLabel.replace(re, "<span style='font-weight:bold;color:#93d1ed;'>$1</span>");
			return $("<li></li>")
					.data("item.autocomplete", newLabel)
					.append("<a>" + highlightedLabel + "</a>")
					.appendTo(ul);
		};
	});
</script>
		
			
