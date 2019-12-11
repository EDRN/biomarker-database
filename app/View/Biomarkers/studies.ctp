<?php
    App::uses('Biomarker', 'Model');

	// Include required CSS and JavaScript
	echo $this->Html->css('bmdb-objects');
	echo $this->Html->css('eip');
	// Deprecated in Cake 1.3:
	//echo $javascript->link('mootools-release-1.11');
	//echo $javascript->link('eip');
	// use this instead:
	echo $this->Html->script('mootools-release-1.11');
	echo $this->Html->script('eip');

	echo $this->Html->css('autocomplete');

	echo $this->Html->script('jquery/jquery-1.8.2.min.js');
	echo $this->Html->script('jquery/jquery-ui/jquery-ui-1.10.3.custom.js');
	echo $this->Html->css('jquery-ui/jquery-ui-1.10.3.custom.min.css');
	echo $this->Html->css('biomarker-studies')
?>
<div class="menu">
	<!-- Breadcrumbs Area -->
	<div id="breadcrumbs">
		<span>You are here: &nbsp;</span>
		<a href="/">Home</a> :: 
		<a href="/biomarkers/">Biomarkers</a> ::
		<a href="/biomarkers/view/<?php echo $biomarker['Biomarker']['id']?>"><?php echo $biomarkerName?><?php echo ((($biomarker['Biomarker']['isPanel']) == 1) ? ' (Panel)':'');?></a> : 
		<span>Studies</span>
	</div><!-- End Breadcrumbs -->
</div>
<div id="outer_wrapper">
<div id="main_section">
<div id="content">
<h2 class="biomarkerName"><?php echo $biomarkerName?> <?php echo ((($biomarker['Biomarker']['isPanel']) == 1) ? '(Panel)':'');?></h2>
		<h5 id="urn">urn:edrn:biomarker:<?php echo $biomarker['Biomarker']['id']?></h5>
		<h5>Created: <?php echo $biomarker['Biomarker']['created']?>. &nbsp;Last Modified: 
			<?php echo $biomarker['Biomarker']['modified']?></h5>
<div id="smalllinks">
		<ul>
		  <li class=""><a href="/biomarkers/view/<?php echo $biomarker['Biomarker']['id']?>">Basics</a></li>
		  <li class=""><a href="/biomarkers/organs/<?php echo $biomarker['Biomarker']['id']?>">Organs</a></li>
		  <li class="activeLink"><a href="/biomarkers/studies/<?php echo $biomarker['Biomarker']['id']?>">Studies</a></li>
		  <li class=""><a href="/biomarkers/publications/<?php echo $biomarker['Biomarker']['id']?>">Publications</a></li>
		  <li class=""><a href="/biomarkers/resources/<?php echo $biomarker['Biomarker']['id']?>">Resources</a></li>
		</ul>
		<div class="clr"><!--  --></div>
</div>
<h4 class="studyHeader">Associated Study Data
<div class="editlink">
<span class="fakelink toggle:addstudydata">+ Add a Study</span>
</div>
</h4>
<div id="addstudydata" class="addstudypub fadeOut">
	<form action="/biomarkers/addStudyData" method="POST">
		<input type="hidden" name="biomarker_id"  value="<?php echo $biomarker['Biomarker']['id']?>"/>
		<input type="hidden" id="study_id" name="study_id" value=""/>
		<input type="text" id="study-search" value="" style="width:99%;"/>
		<span class="hint">Begin typing. A list of options will appear.</span>
		<input type="button" class="cancelButton toggle:addstudydata" value="Cancel"/>
		<input type="submit" class="studyButton" name="associate_study" value="Associate"/>
		
	</form>
	<div class="clr"><!-- clear --></div>
</div>
<?php foreach ($studydatas as $study):?>
	<?php 
		   /*
			* Calculate NPV/PPV if Sens/Spec/Prevalence data is available
			* PPV = (Sens. x Prev.)/[Sens. x Prev. + (1-Spec.) x (1-Prev.)]
			* NPV = [Spec. x (1-Prev.)]/[(1-Sens.) x Prev. + Spec. x (1-Prev.)]
			* where
			*
			* Sens. = Sensitivity
			* Spec. = Specificity
			* Prev. = Prevalence
			*/
			$sens = ($study['BiomarkerStudyData']['sensitivity'] / 100);
			$spec = ($study['BiomarkerStudyData']['specificity'] / 100);
			$prev = $study['BiomarkerStudyData']['prevalence'];

			if ($sens > 0 && $spec > 0 && $prev > 0) {
				$ppv = (round(($sens * $prev)/($sens * $prev + (1-$spec) * (1-$prev)),2) * 100) . '%';
				$npv = (round(($spec * (1-$prev))/((1-$sens)*$prev + $spec * (1-$prev)),2) * 100) . '%';
			} else {
				$ppv = 'n/a';
				$npv = 'n/a';
			}
	
	?>
			<h4 class="studyTitle"><?php echo $study['Study']['title']?>
				<div class="editlink">
					<a class="biomarkerLightRed" href="/biomarkers/removeStudyData/<?php echo $biomarker['Biomarker']['id']?>/<?php echo $study['BiomarkerStudyData']['id']?>">
						x Delete
					</a>	
				</div>	
			</h4>
			<div class="studydetail" style="margin-bottom:15px;">
				<div class="lefttext" style="margin-right:16px;">
					<span id="description" class="textarea">
						<?php Biomarker::printor(substr($study['Study']['studyAbstract'],0,600) . '&nbsp;<a href="/studies/view/' . $study['Study']['id'] . 
							'" style="text-decoration:underline;font-size:90%;"><em>Click here to read more about this study</em></a>','<em>No Description Provided Yet.</em>');
						?>
					</span>
				</div>
				<!-- RELEVANT STUDY DATA -->
				<div class="rightcol" id="studyDetails">
					<h4>Details:</h4>
					<table cellpadding="3" cellspacing="0">
						<tr>
							<td class="label" style="width:70px;">Phase:</td>
							<td><em><span id="phase<?php echo $study['BiomarkerStudyData']['id']?>" class="editablelist object:study_data id:<?php echo $study['BiomarkerStudyData']['id']?> attr:phase opts:One|Two|Three|Four|Five"><?php Biomarker::printor($study['BiomarkerStudyData']['phase'],'click to select');?></span></em></td>
						</tr>
					</table>
					<br/>
					<a id="studyDetailsDefinition" href="/studies/view/<?php echo $study['Study']['id']?>">
						Go to this study's definition
					</a>
				</div>
				<div class="clr"><!-- clear --></div>
				<br/>
				<br/>
					
					
					
					<h5 style="position:relative;border-bottom:solid 1px #999;">Sensitivity / Specificity Information
						<div class="editlink" style="font-size:100%;margin-top:-8px;">
							<span class="fakelink toggle:addsensspec<?php echo $study['BiomarkerStudyData']['id']?>">+ Add Details</span>
						</div>
					</h5>
					<div id="addsensspec<?php echo $study['BiomarkerStudyData']['id']?>" class="addstudypub fadeOut" style="margin-left:14px;">
						<h5 style="margin-bottom:5px;margin-left:1px;">Add Sensitivity / Specificity Details:</h5>
						<form style="color:#555;" action="/biomarkers/addsensspec2" method="POST">
							<input type="hidden" name="biomarker_id" value="<?php echo $biomarker['Biomarker']['id']?>"/>
							<input type="hidden" name="study_data_id" value="<?php echo $study['BiomarkerStudyData']['id']?>"/>
							<input type="hidden" name="study_id" value="<?php echo $study['Study']['id']?>"/>
							<input type="hidden" name="specificAssayType" value=""/>
							Sensitivity (%): <input type="text" name="sensitivity" style="width:50px;"/>&nbsp;&nbsp;
							Specificity (%): <input type="text" name="specificity" style="width:50px;"/>&nbsp;&nbsp;
							Prevalence  (0 - 1.0):  <input type="text" name="prevalence"  style="width:50px;"/>&nbsp;
							<br/>
							Details: <br/><textarea name="sensspec_details" style="width:80%;border:solid 1px #ccc;padding:2px;color:#222;"></textarea>
							<input type="submit" value="Add" name="add_details" style="margin-bottom:3px;"/>
							<input type="button" value="Cancel" style="margin-bottom:3px;" class="cancelButton toggle:addsensspec<?php echo $study['BiomarkerStudyData']['id']?>"/>
						</form>
					</div>
					<br/>
					<div style="padding-left:0px;font-size:90%;">
						<!-- display sens/spec details here -->
						<?php if (count($study['Sensitivity']) > 0):?>
						<table class="associatedstudies" style="width:100%;">
						<tbody>
							<tr><th style="text-align:left;">Notes</th><th>Sensitivity</th><th>Specificity</th><th>Prevalence</th><th>NPV</th><th>PPV</th><th>Edit</th><th>Delete</th></tr>
						<?php endif;?>
						<?php foreach ($study['Sensitivity'] as $s):?>
						<?php
						/*
						 * Calculate NPV/PPV if Sens/Spec/Prevalence data is available
						 * PPV = (Sens. x Prev.)/[Sens. x Prev. + (1-Spec.) x (1-Prev.)]
						 * NPV = [Spec. x (1-Prev.)]/[(1-Sens.) x Prev. + Spec. x (1-Prev.)]
						 * where
						 *
						 * Sens. = Sensitivity
						 * Spec. = Specificity
						 * Prev. = Prevalence
						*/
						$sens = $s['sensitivity'] / 100;
						$spec = $s['specificity'] / 100;
						$prev = $s['prevalence'];

						if ($sens > 0 && $spec > 0 && $prev > 0) {
							$ppv = (round(($sens * $prev)/($sens * $prev + (1-$spec) * (1-$prev)),2) * 100) . '%';
							$npv = (round(($spec * (1-$prev))/((1-$sens)*$prev + $spec * (1-$prev)),2) * 100) . '%';
						} else {
							$ppv = 'n/a';
							$npv = 'n/a';
						}
						?>
							<tr><td><?php echo $s['notes']?></td>
								<td style="text-align:center;"><?php echo $s['sensitivity'];?>%</td>
								<td style="text-align:center;"><?php echo $s['specificity'];?>%</td>
								<td style="text-align:center;"><?php echo $s['prevalence'];?></td>
								<td style="text-align:center;"><?php echo $npv;?></td>
								<td style="text-align:center;"><?php echo $ppv;?></td>
								<td style="text-align:center;"><a class="fakelink" href="/biomarkers/editsensspec/<?php echo $s['id']?>/<?php echo $biomarker['Biomarker']['id']?>/0/<?php echo $study['Study']['id']?>/studies">Edit</a></td>
								<td style="text-align:center;">
									<a href="/biomarkers/removesensspec2/<?php echo $biomarker['Biomarker']['id']?>/<?php echo $study['BiomarkerStudyData']['id']?>/<?php echo $s['id']?>" 
										style="color:#d55;" 
										onclick="return confirm('This action can not be undone. Continue?')">
										x
									</a>
								</td>
							</tr>
						
						<?php endforeach;?>
						<?php if (count($study['Sensitivity']) > 0):?>
						</tbody>
						</table>
						<?php endif;?>
					</div>
					
					
					
					
					<br/>
				<h5 style="position:relative;border-bottom:solid 1px #999;">Related Publications
					<div class="editlink" style="font-size:100%;margin-top:-8px;">
						<span class="fakelink toggle:addstudypub<?php echo $study['BiomarkerStudyData']['id']?>">+ Add Publication</span>
					</div>
				</h5>
				<div id="addstudypub<?php echo $study['BiomarkerStudyData']['id']?>" class="addstudypub fadeOut" style="margin-left:14px;">
					<h5 style="margin-bottom:5px;margin-left:1px;">Associate a Publication:</h5>
					<form action="/biomarkers/addBiomarkerStudyDataPub" method="POST">
						<input type="hidden" name="biomarker_id"  value="<?php echo $biomarker['Biomarker']['id']?>"/>
						<input type="hidden" name="study_data_id" value="<?php echo $study['BiomarkerStudyData']['id']?>"/>
						<input type="hidden" id="publication<?php echo $study['BiomarkerStudyData']['id']?>_id" name="pub_id" value=""/>
						<input type="text" class="pubsearch id:<?php echo $study['BiomarkerStudyData']['id']?>" id="publication<?php echo $study['BiomarkerStudyData']['id']?>search" value="" style="width:90%;"/><br/>
						<div>
							<span class="hint" style="float:left;margin-top:3px;">Begin typing a publication title. A list of options will appear.<br/>
			Don't see the publication you want? <a href="/publications/import">Import a new publication</a></span>
							<input type="button" class="cancelButton toggle:addstudypub<?php echo $study['BiomarkerStudyData']['id']?>" value="Cancel" style="float:right;padding:2px;margin:6px;margin-right:0px;"/>
							<input type="submit" name="associate_pub" value="Associate" style="float:right;padding:2px;margin:6px;margin-right:0px;"/>
							<div class="clr"><!-- clear --></div>
						</div>
						
					</form>
					<div class="clr"><!-- clear --></div>
				</div>

				<ul style="margin-left:20px;margin-top:10px;font-size:90%;">
				<?php foreach ($study['Publication'] as $publication):?>
					<li><div class="studypubsnippet">
							<a href="/publications/view/<?php echo $publication['id']?>"><?php echo $publication['title']?></a> &nbsp;[<a href="/biomarkers/removeBiomarkerStudyDataPub/<?php echo $biomarker['Biomarker']['id']?>/<?php echo $study['BiomarkerStudyData']['id']?>/<?php echo $publication['id']?>">Remove this association</a>]<br/>
							<span style="color:#555;font-size:90%;">Author:
							<?php echo $publication['author']?>. &nbsp; Published in
							<?php echo $publication['journal']?>, &nbsp;
							<?php echo $publication['published']?></span>
						</div>
					</li>
				<?php endforeach;?>
				</ul>
				
				<br/>
				<h5 style="position:relative;border-bottom:solid 1px #999;">Related Resources
					<div class="editlink" style="font-size:100%;margin-top:-8px;">
						<span class="fakelink toggle:addstudyres<?php echo $study['BiomarkerStudyData']['id']?>">+ Add Resource</span>
					</div>
				</h5>
				<div id="addstudyres<?php echo $study['BiomarkerStudyData']['id']?>" class="addstudyres fadeOut" style="margin-left:14px;">
					<h5 style="margin-bottom:5px;margin-left:1px;">Add an External Resource:</h5>
					<form action="/biomarkers/addBiomarkerStudyDataResource" method="POST" style="margin-top:5px;">
						<input type="hidden" name="biomarker_id"  value="<?php echo $biomarker['Biomarker']['id']?>"/>
						<input type="hidden" name="study_data_id" value="<?php echo $study['BiomarkerStudyData']['id']?>"/>
						<div style="float:left;width:130px;color:#555;">URL: </div>
						<input type="text" style="width:70%;" name="url" value="http://"/><br/><br/>
						<div style="float:left;width:130px;color:#555;">Description:</div>
						<input type="text" name="desc" style="float:left;width:50%;"/>
						<input type="submit" name="associate_res" value="Associate" style="float:left;padding:2px;margin-right:0px;margin-left:6px;"/>
						<input type="button" class="cancelButton toggle:addstudyres<?php echo $study['BiomarkerStudyData']['id']?>" value="Cancel" style="float:left;padding:2px;margin:0px;margin-right:0px;margin-left:6px;"/>
						
					</form>
					<div class="clr"><!-- clear --></div>
				</div>
				
				<ul style="margin-left:20px;margin-top:10px;font-size:90%;">
				<?php foreach ($study['StudyDataResource'] as $resource):?>
					<li><div class="studyressnippet">
							<a href="<?php echo $resource['URL']?>"><?php echo $resource['URL']?></a>&nbsp;&nbsp;[<a href="/biomarkers/removeBiomarkerStudyDataResource/<?php echo $biomarker['Biomarker']['id']?>/<?php echo $study['BiomarkerStudyData']['id']?>/<?php echo $resource['id']?>">Remove this association</a>]<br/>
							<span style="color:#555;font-size:90%;">
							<?php echo $resource['description']?>
							</span>
						</div>
					</li>
				<?php endforeach;?>
				</ul>	
			</div>
		<?php endforeach;?>
				

</div>
</div>
</div>
<script type="text/javascript">
	jQuery.noConflict();

	// Activate Edit-in-place text editors
	window.addEvent('domready', function() {
		new eip($$('.editable'), '/biomarkers/savefield', {action: 'update'});
		new eiplist($$('.editablelist'),'/biomarkers/savefield', {action: 'update'});
	});

	// jQuery and MooTools clobber eachothers namespaces. `jQuery.noConflict()` prevents the clobbering
	// and this function let's us still call the jQuery code with $. 
	(function($) {
		$(function() {
			// Activate study searches
			var studyStrings = <?php echo "[" . $studystring . "]"; ?>;
			$('#study-search').autocomplete({
				source: studyStrings,
				select: function(event, ui) {
					var studyName = ui.item.value.split('|')[0];
					var studyId = ui.item.value.split('|')[1];
					$(this).siblings('#study_id').val(studyId);

					ui.item.label = studyName;
					ui.item.value = studyName;
				}
			});

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
			$('.cancelButton').each(function(index) {
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
			$('.pubsearch').each(function() {
				$(this).autocomplete({
					source: '/biomarkers/getAutocompletePublications',
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
	})(jQuery);
</script>
