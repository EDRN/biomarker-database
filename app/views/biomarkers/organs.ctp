<?php
	// Include required CSS and JavaScript
	echo $html->css('bmdb-objects');
	echo $html->css('eip');
	echo $html->css('biomarkers');
	echo $javascript->link('mootools-release-1.11');
	echo $javascript->link('eip');

	echo $html->css('autocomplete');

	echo $javascript->link('jquery/jquery-1.8.2.min.js');
	echo $javascript->link('jquery/jquery-ui/jquery-ui-1.10.3.custom.js');
	echo $html->css('jquery-ui/jquery-ui-1.10.3.custom.min.css');

	echo $html->css('biomarkers-organs');
?>


<div class="menu">
	<!-- Breadcrumbs Area -->
	<div id="breadcrumbs">
		<span>You are here: &nbsp;</span>
		<a href="/<?php echo PROJROOT;?>/">Home</a> :: 
		<a href="/<?php echo PROJROOT;?>/biomarkers/">Biomarkers</a> ::
		<a href="/<?php echo PROJROOT;?>/biomarkers/view/<?php echo $biomarker['Biomarker']['id']?>"><?php echo $biomarkerName?><?php echo ((($biomarker['Biomarker']['isPanel']) == 1) ? ' (Panel)':'');?></a> : 
		<span>Organs</span>
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
		  <li class=""><a href="/<?php echo PROJROOT;?>/biomarkers/view/<?php echo $biomarker['Biomarker']['id']?>">Basics</a></li>
		  <li class="activeLink"><a href="/<?php echo PROJROOT;?>/biomarkers/organs/<?php echo $biomarker['Biomarker']['id']?>">Organs</a></li>
		  <li class=""><a href="/<?php echo PROJROOT;?>/biomarkers/studies/<?php echo $biomarker['Biomarker']['id']?>">Studies</a></li>
		  <li class=""><a href="/<?php echo PROJROOT;?>/biomarkers/publications/<?php echo $biomarker['Biomarker']['id']?>">Publications</a></li>
		  <li class=""><a href="/<?php echo PROJROOT;?>/biomarkers/resources/<?php echo $biomarker['Biomarker']['id']?>">Resources</a></li>
		</ul>
		<div class="clr"><!--  --></div>
</div>		

			
			
<?php if ($organData != false):?>
<em id="organDataContentHeader">The following organs have data associated with this biomarker...</em>
<div id="organdatacontent">
		
<!-- SET UP PAGINATION FOR ORGANDATAS -->			
<div id="smallEditlinks">
	<ul>
		<?php foreach($organdatas as $od): ?>
			<li class="<?php echo (($od['Organ']['id'] == $organData['Organ']['id'])? "activeLink" : "");?>">
			<a href="/<?php echo PROJROOT;?>/biomarkers/organs/<?php echo $biomarker['Biomarker']['id'] . "/" . $od['OrganData']['id']?>">
				<?php echo $od['Organ']['name']?>
			</a>
			</li>
		<?php endforeach;?>	
	</ul>
	<div class="clr"><!-- clear --></div>
</div>		
			
			

<h4 class="organDataBlockHeader">Associated 
		<div class="editlink">
			<a href="/<?php echo PROJROOT;?>/biomarkers/removeOrganData/<?php echo $biomarker['Biomarker']['id']?>/<?php echo $organData['OrganData']['id']?>">
				<span class="biomarkerLightRed">x Delete</span>
			</a>
			&nbsp;| 
			<a href="/<?php echo PROJROOT;?>/acls/edit/biomarkerorgan/<?php echo $organData['OrganData']['id']?>/<?php echo $biomarker['Biomarker']['id']?>">
				Set Security
			</a>
		</div>
		<?php echo $organData['Organ']['name']?> Data:
	</h4>
	<div class="organdatablock">
		<div class="lefttext">
			
			<h5>Performance comment for this biomarker with respect to <?php echo $organData['Organ']['name']?></h5>
			<span id="performance_comment" class="editable textarea object:organ_data id:<?php echo $organData['OrganData']['id']?> attr:performance_comment">
				<?php Biomarker::printor($organData['OrganData']['performance_comment'],'No Performance Comment Provided Yet... Click Here to Add.');?>
			</span>
			
			<h5>Description of this biomarker with respect to <?php echo $organData['Organ']['name']?></h5>
			<span id="description" class="editable textarea object:organ_data id:<?php echo $organData['OrganData']['id']?> attr:description">
				<?php Biomarker::printor($organData['OrganData']['description'],'No Description Provided Yet... Click Here to Add.');?>
			</span>
			
		</div>
		<div class="rightcol">
			<h4 id="organAttributesHeader">Attributes:</h4>
			<table id="organAttributesTable" cellspacing="0" cellpadding="3">
				<tr>
					<td class="label">Phase:</td>
					<td>
						<em>
							<span id="security" class="editablelist object:organ_data id:<?php echo $organData['OrganData']['id']?> attr:phase opts:One|Two|Three|Four|Five">
								<?php Biomarker::printor($organData['OrganData']['phase'],'click to select');?>
							</span>
						</em>
					</td>
				</tr>
				<tr>
					<td class="label">QA State:</td>
					<td>
						<em>
							<span id="qastate" class="editablelist object:organ_data id:<?php echo $organData['OrganData']['id']?> attr:qastate opts:New|Under_Review|Curated|Accepted|Rejected">
								<?php Biomarker::printor($organData['OrganData']['qastate'],'click to select');?>
							</span>
						</em>
					</td>
				</tr>
				<tr>
					<td class="label">Clinical Translation</td>
					<td>
						<em>
							<span id="clinical_translation" class="editablelist object:organ_data id:<?php echo $organData['OrganData']['id']?> attr:clinical_translation opts:None|FDA|CLIA">
								<?php Biomarker::printor($organData['OrganData']['clinical_translation'],'click to select');?>
							</span>
						</em>
					</td>
				</tr>
			</table>
		</div>
		
		<!-- DEFINITIONS -->
		<div class="clr"><!-- clear --></div>
		<h3 class="organSubsectionHeader">Definitions
			<div class="editlink">
				<span class="fakelink toggle:adddefinition">+ Add Definition</span>
			</div>
		</h3>
		<div id="adddefinition" class="addstudydata fadeOut">
			<h5 class="organSubsectionActivationLink">Associate a Definition:</h5>
			<div style="width:80%;">
				<form action="/<?php echo PROJROOT;?>/biomarkers/addOrganTermDefinition" method="POST">
					<input type="hidden" name="organ_data_id" value="<?php echo $organData['OrganData']['id']?>"/>
					<input type="hidden" name="biomarker_id"  value="<?php echo $biomarker['Biomarker']['id']?>"/>
					<input type="hidden" id="term_id" name="term_id" value=""/>
					<input type="text" id="term-search" value="" style="width:100%;"/>
					<span class="hint">Begin typing. A list of defined terms will appear.</span> &nbsp;
					<span class="hint">Can't find your term? <a href="/<?php echo PROJROOT?>/terms/define">Define It!</a></span><br/>
					<input type="button" class="cancelButton toggle:adddefinition" value="Cancel"/>
					<input type="submit" class="submitButton" name="associate_term" value="Associate"/>
					
				</form>
				<div class="clr"><!-- clear --></div>
			</div>
		</div>
		<dl>
		<?php foreach ($organData['Term'] as $term):?>
			<dt>
				<?php echo $term['label']?> &nbsp;&nbsp;
				<a class="definitionTermRemove" alt="Remove" href="/<?php echo PROJROOT?>/biomarkers/removeOrganTermDefinition/<?php echo $biomarker['Biomarker']['id']?>/<?php echo $organData['OrganData']['id']?>/<?php echo $term['id']?>">
					x Remove Definition
				</a>
			</dt>
			<dd><?php echo $term['definition']?></dd>
		<?php endforeach?>
		</dl>
		
		<!-- SUPPORTING STUDY DATA -->
		<h3 class="organSubsectionHeader">Supporting Study Data
			<div class="editlink">
				<span class="fakelink toggle:addstudydata">+ Add Study</span>
			</div>
		</h3>
		<div id="addstudydata" class="addstudydata fadeOut">
			<h5 class="organSubsectionHeader">Associate a Study:</h5>
			<div style="width:80%;">
				<form action="/<?php echo PROJROOT;?>/biomarkers/addorganstudydata" method="POST">
					<input type="hidden" name="organ_data_id" value="<?php echo $organData['OrganData']['id']?>"/>
					<input type="hidden" name="biomarker_id"  value="<?php echo $biomarker['Biomarker']['id']?>"/>
					<input type="hidden" id="study_id" name="study_id" value=""/>
					<input type="text" id="study-search" value="" style="width:100%;"/>
					<span class="hint">Begin typing. A list of options will appear.</span><br/>
					<input type="button" class="cancelButton toggle:addstudydata" value="Cancel"/>
					<input type="submit" class="submitButton" name="associate_study" value="Associate"/>
					
				</form>
				<div class="clr"><!-- clear --></div>
			</div>
		</div>
		<div class="spacer">
		<?php foreach ($organData['StudyData'] as $study):?>
		
		<h4 class="studyTitle"><?php echo $study['Study']['title']?></h4>
		<div class="studyAssociationContainer">
		<div>
			<a class="studyAssociationRemove" href="/<?php echo PROJROOT;?>/biomarkers/removeOrganStudyData/<?php echo $biomarker['Biomarker']['id']?>/<?php echo $organData['OrganData']['id']?>/<?php echo $study['id']?>" onclick="return confirm('This action can not be undone. Continue?')">
				x Remove Association
			</a>
		</div>
		<!-- start study  -->
					<div class="lefttext studyDataLeftColumn">
						<span id="description" class="textarea">
							<?php Biomarker::printor(substr($study['Study']['studyAbstract'],0,600).'&nbsp;<a href="/'.PROJROOT.'/studies/view/'.$study['Study']['id'].'" style="text-decoration:underline;font-size:90%;"><em>Click here to read more about this study</em></a>','<em>No Description Provided Yet.</em>');?>
						</span>
					</div>
					<!-- RELEVANT STUDY DATA -->
					<div class="rightcol" id="studyDataParameters">
						<h4 class="studyDataParametersHeader">Study Parameters:</h4>
						<table class="studyDataParametersTable" cellspacing="0" cellpadding="3">
							<tr>
								<td class="label">Phase:</td>
								<td>
									<em>
										<span id="phase<?php echo $study['id']?>" class="editablelist object:organ_study_data id:<?php echo $study['id']?> attr:phase opts:One|Two|Three|Four|Five">
											<?php Biomarker::printor($study['phase'],'click to select');?>
										</span>
									</em>
								</td>
							</tr>
							<tr>
								<td class="label">Prevalence: (0.0 - 1.0)</td>
								<td>
									<em>
										<span id="prevalence<?php echo $study['id']?>" class="editable object:organ_study_data id:<?php echo $study['id']?> attr:prevalence">
											<?php echo $study['prevalence']?>
										</span>
									</em>
								</td>
							</tr>
						</table>
						<br/>
						<a class="studyDefinitionLink" href="/<?php echo PROJROOT;?>/studies/view/<?php echo $study['Study']['id']?>">
							Go to this study's definition
						</a>
					</div>
					<div class="clr"><!-- clear --></div>
					<br/>
					<h5 class="studySubsectionFold">Biomarker Characteristics Summary
						<div class="editlink studySubsectionFoldToggle">
							<span class="fakelink toggle:addsensspec<?php echo $study['id']?>">+ Add Details</span>
						</div>
					</h5>
					<div id="addsensspec<?php echo $study['id']?>" class="addstudypub fadeOut" style="margin-left:14px;">
						<h5 class="detailsEditHeader">Add Biomarker Characteristics Details:</h5>
						<form class="studyDetailsEditForm" action="/<?php echo PROJROOT;?>/biomarkers/addsensspec" method="POST">
							<input type="hidden" name="biomarker_id" value="<?php echo $biomarker['Biomarker']['id']?>"/>
							<input type="hidden" name="organ_data_id" value="<?php echo $organData['OrganData']['id']?>"/>
							<input type="hidden" name="study_data_id" value="<?php echo $study['id']?>"/>
							<input type="hidden" name="study_id" value="<?php echo $study['Study']['id']?>"/>
							Sensitivity (%): <input type="text" name="sensitivity" style="width:50px;"/>&nbsp;&nbsp;
							Specificity (%): <input type="text" name="specificity" style="width:50px;"/>&nbsp;&nbsp;
							Prevalence  (0 - 1.0):  <input type="text" name="prevalence"  style="width:50px;"/>&nbsp;
							<br/>
							Specific Assay Type: <br/>
							<textarea class="studyDetailsEditTextArea" name="specificAssayType"></textarea>
							<br/>
							Notes: <br/><textarea class="studyDetailsEditTextArea" name="sensspec_details"></textarea>
							<input type="submit" value="Add" name="add_details" style="margin-bottom:3px;"/>
							<input type="button" value="Cancel" style="margin-bottom:3px;" class="cancelButton toggle:addsensspec<?php echo $study['id']?>"/>
						</form>
					</div>
					<br/>
					<div class="studyDetailsDisplay">
						<!-- display sens/spec details here -->
						<?php if (count($study['Sensitivity']) > 0):?>
						<table class="associatedstudies" style="width:100%;">
						<tbody>
							<tr><th style="text-align:left;">Notes</th>
								<th>Sensitivity</th><th>Specificity</th>
								<th>Prevalence</th>
								<th>NPV</th>
								<th>PPV</th>
								<th>Assay Type</th>
								<th>Edit</th>
								<th>Delete</th></tr>
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
								<td style="text-align:center;"><?php echo $s['specificAssayType'];?></td>
								<td style="text-align:center;">
									<a class="fakelink" href="/<?php echo PROJROOT;?>/biomarkers/editsensspec/<?php echo $s['id']?>/<?php echo $biomarker['Biomarker']['id']?>/<?php echo $organData['OrganData']['id']?>/<?php echo $study['id']?>">Edit</a>
								</td>
								<td style="text-align:center;">
									<a href="/<?php echo PROJROOT;?>/biomarkers/removesensspec/<?php echo $biomarker['Biomarker']['id']?>/<?php echo $organData['OrganData']['id']?>/<?php echo $study['id']?>/<?php echo $s['id']?>" 
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
					<h5 class="studySubsectionFold">Decision Rule</h5>
					<span id="performance_comment" class="editable textarea object:organ_study_data id:<?php echo $study['id']?> attr:decision_rule">
						<?php Biomarker::printor($study['decision_rule'],'No Decision Rule Detail Provided Yet... Click Here to Add.');?>
					</span>
			
					<br/>
					<h5 class="studySubsectionFold">Related Publications
						<div class="editlink studySubsectionFoldToggle">
							<span class="fakelink toggle:addstudypub<?php echo $study['id']?>">+ Add Publication</span>
						</div>
					</h5>
					<div id="addstudypub<?php echo $study['id']?>" class="addstudypub fadeOut" style="margin-left:14px;">
						<h5 class="detailsEditHeader">Associate a Publication:</h5>
						<form action="/<?php echo PROJROOT;?>/biomarkers/addstudydatapub" method="POST">
							<input type="hidden" name="biomarker_id"  value="<?php echo $biomarker['Biomarker']['id']?>"/>
							<input type="hidden" name="organ_data_id" value="<?php echo $organData['OrganData']['id']?>"/>
							<input type="hidden" name="study_data_id" value="<?php echo $study['id']?>"/>
							<input type="hidden" id="publication<?php echo $study['id']?>_id" name="pub_id" value=""/>
							<input type="text" class="pubsearch id:<?php echo $study['id']?>" id="publication<?php echo $study['id']?>search" value="" style="width:90%;"/><br/>
							<div>
								<span class="hint">
									Begin typing a publication title. A list of options will appear.<br/>
									Don't see the publication you want? 
									<a href="/<?php echo PROJROOT;?>/publications/import">
										Import a new publication
									</a>
								</span>
								<input type="button" class="cancelButton toggle:addstudypub<?php echo $study['id']?>" value="Cancel"/>
								<input type="submit" class="submitButton" name="associate_pub" value="Associate"/>
								<div class="clr"><!-- clear --></div>
							</div>
							
						</form>
						<div class="clr"><!-- clear --></div>
					</div>
	
					<ul class="studyPublicationList">
						<?php foreach ($study['Publication'] as $publication):?>
							<li>
								<div class="studypubsnippet">
									<a href="/<?php echo PROJROOT?>/publications/view/<?php echo $publication['id']?>">
										<?php echo $publication['title']?>
									</a> 
									&nbsp;[
									<a href="/<?php echo PROJROOT;?>/biomarkers/removeStudyDataPub/<?php echo $biomarker['Biomarker']['id']?>/<?php echo $organData['OrganData']['id']?>/<?php echo $study['id']?>/<?php echo $publication['id']?>">
										Remove this association
									</a>]<br/>
									<span class="studyPublicationDetails">
										Author:
										<?php echo $publication['author']?>. &nbsp; Published in
										<?php echo $publication['journal']?>, &nbsp;
										<?php echo $publication['published']?>
									</span>
								</div>
							</li>
						<?php endforeach;?>
					</ul>
					
					<br/>
					<h5 class="studySubsectionFold">Related Resources
						<div class="editlink studySubsectionFoldToggle">
							<span class="fakelink toggle:addstudyres<?php echo $study['id']?>">+ Add Resource</span>
						</div>
					</h5>
					<div id="addstudyres<?php echo $study['id']?>" class="addstudyres fadeOut" style="margin-left:14px;">
						<h5 class="detailsEditHeader">Add an External Resource:</h5>
						<form action="/<?php echo PROJROOT;?>/biomarkers/addStudyDataResource" method="POST" style="margin-top:5px;">
							<input type="hidden" name="biomarker_id"  value="<?php echo $biomarker['Biomarker']['id']?>"/>
							<input type="hidden" name="organ_data_id" value="<?php echo $organData['OrganData']['id']?>"/>
							<input type="hidden" name="study_data_id" value="<?php echo $study['id']?>"/>
							<div class="studyResourceDetailsLabel">URL:</div>
							<input type="text" style="width:70%;" name="url" value="http://"/>
							<br/><br/>
							<div class="studyResourceDetailsLabel">Description:</div>
							<input type="text" class="studyResourceDetailsDescription" name="desc"/>
							<input type="button" class="cancelButton toggle:addstudyres<?php echo $study['id']?>" value="Cancel">
							<input type="submit" class="submitButton" name="associate_res" value="Associate">
						</form>
						<div class="clr"><!-- clear --></div>
					</div>
					
					<ul class="studyResourceList">
					<?php foreach ($study['StudyDataResource'] as $resource):?>
						<li><div class="studyressnippet">
								<a href="<?php echo $resource['URL']?>">
									<?php echo $resource['URL']?>
								</a>
								&nbsp;&nbsp;[
								<a href="/<?php echo PROJROOT;?>/biomarkers/removeStudyDataResource/<?php echo $biomarker['Biomarker']['id']?>/<?php echo $organData['OrganData']['id']?>/<?php echo $study['id']?>/<?php echo $resource['id']?>">
									Remove this association
								</a>]<br/>
								<span class="studyResourceDetails">
								<?php echo $resource['description']?>
								</span>
							</div>
						</li>
					
					
					<?php endforeach;?>
					</ul>
					<br/> 
				</div>
		<!-- end study -->
		
		<?php endforeach;?>
		<?php if (count($organData['StudyData']) > 0): ?> <?php endif;?>
		<br/>
		</div><!-- end spacer -->
		<h3 class="subsectionHeader">Supporting Publication Data &nbsp;(Biomarker-Organ level)
			<div class="editlink">
				<span class="fakelink toggle:addstudypub">+ Add a Publication</a>
			</div>
		</h3>
		<div id="addstudypub" class="addstudypub fadeOut">
			<h5 class="subsectionTitle">Associate a Publication:</h5>
			<form action="/<?php echo PROJROOT;?>/biomarkers/addOrganDataPub" method="POST">
				<input type="hidden" name="biomarker_id"  value="<?php echo $biomarker['Biomarker']['id']?>"/>
				<input type="hidden" name="organ_data_id" value="<?php echo $organData['OrganData']['id']?>"/>
				<input type="hidden" id="organpublication_id" name="pub_id" value=""/>
				<input type="text" id="organpublicationsearch" value="" style="width:90%;"/><br/>
				<div>
					<span class="hint">
						Begin typing a publication title. A list of options will appear.
						<br/>
						Don't see the publication you want? 
						<a href="/<?php echo PROJROOT;?>/publications/import">
							Import a new publication
						</a>
					</span>
					<input type="button" class="cancelButton toggle:addstudypub" value="Cancel"/>
					<input type="submit" class="submitButton" name="associate_pub" value="Associate"/>
					<div class="clr"><!-- clear --></div>
				</div>
			</form>
			<div class="clr"><!-- clear --></div>
		</div>
		<ul class="displayList">
			<?php foreach ($organData['Publication'] as $publication):?>
				<li>
					<div class="studypubsnippet">
						<a href="/<?php echo PROJROOT?>/publications/view/<?php echo $publication['id']?>">
							<?php echo $publication['title']?>
						</a> 
						&nbsp;[
						<a href="/<?php echo PROJROOT;?>/biomarkers/removeOrganDataPub/<?php echo $biomarker['Biomarker']['id']?>/<?php echo $organData['OrganData']['id']?>/<?php echo $publication['id']?>">
							Remove this association
						</a>
						]<br/>
						<span style="color:#555;font-size:90%;">Author:
						<?php echo $publication['author']?>. &nbsp; Published in
						<?php echo $publication['journal']?>, &nbsp;
						<?php echo $publication['published']?></span>
					</div>
				</li>
			<?php endforeach;?>
		</ul>
		
		<h3 class="subsectionHeader">Supporting Resource Data &nbsp;(Biomarker-Organ level)
			<div class="editlink">
				<span class="fakelink toggle:addstudyres">+ Add a Resource</span>
			</div>
		</h3>
		<div id="addstudyres" class="addstudyres fadeOut">
			<h5 class="subsectionTitle">Add an External Resource:</h5>
			<form action="/<?php echo PROJROOT;?>/biomarkers/addOrganDataResource" method="POST" style="margin-top:5px;">
				<input type="hidden" name="biomarker_id"  value="<?php echo $biomarker['Biomarker']['id']?>"/>
				<input type="hidden" name="organ_data_id" value="<?php echo $organData['OrganData']['id']?>"/>
				<div class="resourceDetailsLabel">URL: </div>
				<input type="text" style="width:70%;" name="url" value="http://"/><br/><br/>
				<div class="resourceDetailsLabel">Description:</div>
				<input type="text" class="resourceDetailsDescription" name="desc"/>
				<input type="button" class="cancelButton toggle:addstudyres" value="Cancel"/>
				<input type="submit" class="submitButton" name="associate_res" value="Associate"/>
				
			</form>
			<div class="clr"><!-- clear --></div>
		</div>
		<ul class="displayList">
			<?php foreach ($organData['OrganDataResource'] as $resource):?>
				<li><div class="studyressnippet">
					<a href="<?php echo $resource['URL']?>">
						<?php echo $resource['URL']?>
					</a>
					&nbsp;&nbsp;[
					<a href="/<?php echo PROJROOT;?>/biomarkers/removeOrganDataResource/<?php echo $biomarker['Biomarker']['id']?>/<?php echo $organData['OrganData']['id']?>/<?php echo $resource['id']?>">
						Remove this association
					</a>
					]<br/>
					<span class="publicationDetails">
						<?php echo $resource['description']?>
					</span>
					</div>
				</li>
			<?php endforeach;?>
		</ul>
			
		
		
		
	</div><!-- end organ data block for <?php echo $organData['Organ']['name']?> -->

</div><!-- end organdatacontent -->
<?php endif;?>
<?php if($organData == false): ?>
<div id="emptyOrganAssociationNotice">
<em>No organs have been associated with this Biomarker. Select from the 'Curation Actions' box on the right.</em>
</div>
<?php endif;?>

</div><!-- end content -->
<div id="supplements">
<div class="box">
<h3 class="title">Actions</h3>
	<ul>
		<li><a href="#">Download as .PDF</a>&nbsp;&nbsp;(coming soon)</li>
	</ul>
</div>
<br/>
<div class="box">
<h3 class="title">Curation Actions</h3>
<form method="POST" action="/<?php echo PROJROOT;?>/biomarkers/addOrganData">
	<input type="hidden" name="biomarker_id" value="<?php echo $biomarker['Biomarker']['id']?>"/>
	<ul>
		<li class="biomarkerDarkGray">Associate an Organ:<br/><br/>
		<select id="organAssociationSelect" name="organ">
			<?php foreach ($organ as $o):?>
				<option value="<?php echo $o['Organ']['id']?>"><?php echo $o['Organ']['name']?></option>
			<? endforeach;?>
		
		</select><br/><br/>
		<input id="organAssociationSubmit" type="submit"/>
		
		
		</li>
	</ul>
</div>

</div><!-- end supplements -->
</div>
</div>

<script type="text/javascript">
	jQuery.noConflict();

	// Activate Edit-in-place text editors
	window.addEvent('domready', function() {
		new eip($$('.editable'), '/<?php echo PROJROOT;?>/biomarkers/savefield', {action: 'update'});
		new eiplist($$('.editablelist'),'/<?php echo PROJROOT;?>/biomarkers/savefield', {action: 'update'});
	});

	// jQuery and MooTools clobber eachother's namespaces. `jQuery.noConflict()` prevents the clobbering
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
			$('.detailslink').each(function(a){
				var classes = $(this).attr('class').split(/\s+/);

				for (i=classes.length-1;i>=0;i--) {
					if (classes[i].contains('toggle:')) {
						var toggle = classes[i].split(":")[1];
					}
				}
				var toggleval = (toggle) ? toggle : '';

				$(this).click(function() {
					var toggleTarget = '#' + toggle;

					if($(toggleTarget).css("display") == 'none') {
						// show
						$(toggleTarget).removeClass("fadeOut").addClass("fadeIn");
					} else {
						// hide
						$(toggleTarget).removeClass("fadeIn").addClass("fadeOut");
					}
				});
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
				if($(toggleTarget).hasClass("fadeOut")) {
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

		// Activate term autocomplete box
		$('#term-search').each(function() {
			$(this).autocomplete({
				source: '/<?php echo PROJROOT;?>/terms/getAutocompleteTerms',
				select: function(event, ul) {
					var studyName = ul.item.value.split('|')[0];
					var studyId = ul.item.value.split('|')[1];
					$(this).siblings("[name='term_id']").val(studyId);
					ul.item.label = studyName;
					ul.item.value = studyName;
				}
			});
		});

		// Activate OrganData Associate Publication autocomplete box
		$('#organpublicationsearch').each(function() {
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
        })(jQuery);
</script>
