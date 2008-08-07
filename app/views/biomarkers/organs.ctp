<?php echo $html->css('frozenobject');?>
<?php echo $html->css('eip');?>
<?php echo $html->css('autocomplete');?>
<?php echo $javascript->link('mootools-release-1.11');?>
<?php echo $javascript->link('eip');?>
<?php echo $javascript->link('autocomplete/Observer');?>
<?php echo $javascript->link('autocomplete/Autocompleter');?>

<?php 
	function printor($value,$alt) {
		if ($value == "") {
			echo $alt;
		} else {
			echo $value;
		}
	}

?>
<?php

?>
<div class="menu">
	<div class="mainContent">
	<h2 class="title">EDRN Biomarker Database</h2>
	</div>
	<!-- Breadcrumbs Area -->
	<div id="breadcrumbs">
		<span style="color:#ddd;">You are here: &nbsp;</span>
		<a href="/<?php echo PROJROOT;?>/">Home</a> :: 
		<a href="/<?php echo PROJROOT;?>/biomarkers/">Biomarkers</a> ::
		<a href="/<?php echo PROJROOT;?>/biomarkers/view/<?php echo $biomarker['Biomarker']['id']?>"><?php echo $biomarker['Biomarker']['name']?> </a> : 
		<span>Organs</span>
	</div><!-- End Breadcrumbs -->
		
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
</div>
<div id="outer_wrapper">
<div id="main_section">
<div id="content">
<h2><?php echo $biomarker['Biomarker']['name']?></h2>
		<h5 id="urn">urn:edrn:biomarker:<?php echo $biomarker['Biomarker']['id']?></h5>
		<h5>Created: <?php echo $biomarker['Biomarker']['created']?>. &nbsp;Last Modified: 
			<?php echo $biomarker['Biomarker']['modified']?></h5>
			
			
			
			
<?php if ($organData != false):?>
<div id="organdatacontent">
		
<!-- SET UP PAGINATION FOR ORGANDATAS -->			
<div id="smallEditlinks">
	<ul>
		<?php foreach($biomarker['OrganData'] as $od):?>
		<li class="<?php echo (($od['Organ']['id'] == $organData['Organ']['id'])? "activeLink" : "");?>"><a href="/<?php echo PROJROOT;?>/biomarkers/organs/<?php echo $biomarker['Biomarker']['id'] . "/" . $od['id']?>"><?php echo $od['Organ']['name']?></a></li>
		<?php endforeach;?>	
	</ul>
	<div class="clr"><!-- clear --></div>
</div>		
			
			

<h4 class="organdatablockheader" style="background-color:transparent;font-size:18px;">Associated 
		<div class="editlink">
			<a href="/<?php echo PROJROOT;?>/biomarkers/removeOrganData/<?php echo $biomarker['Biomarker']['id']?>/<?php echo $organData['id']?>" style="color:#d55;">x Delete</a>
		</div>
		<?php echo $organData['Organ']['name']?> Data:
	</h4>
	<div class="organdatablock">
		<div class="lefttext">
			<span id="description" class="editable textarea object:organ_data id:<?php echo $organData['id']?> attr:description"><?php printor($organData['description'],'No Description Provided Yet... Click Here to Add.');?></span>
		</div>
		<div class="rightcol">
			<h4>Attributes:</h4>
			<table>
				<tr>
					<td class="label">Phase:</td>
					<td><em><span id="security" class="editablelist object:organ_data id:<?php echo $organData['id']?> attr:phase opts:One|Two|Three|Four|Five"><?php printor($organData['phase'],'click to select');?></span></em></td>
				</tr>
				<tr>
					<td class="label">QA State:</td>
					<td><span id="qastate"><em><span id="qastate" class="editablelist object:organ_data id:<?php echo $organData['id']?> attr:qastate opts:New|Under_Review|Accepted|Rejected"><?php printor($organData['qastate'],'click to select');?></span></em></td>
				</tr>
			</table>
		</div>
		<div class="clr"><!-- clear --></div>
		<h3>Aggregate Results</h3>
		<div style="margin-left:30px;width:96%;">
		
		<!-- SENSITIVITY -->
		<div class="rightcol" style="margin-left:0px;margin-top:-15px;width:24%">
			<h4>Sensitivity:</h4>
			<table style="background-color:#fff;">
				<tr>
					<td class="label">Minimum:</td>
					<td><em><span id="sensitivitymin" class="editable object:organ_data id:<?php echo $organData['id']?> attr:sensitivity_min"><?php echo $organData['sensitivity_min']?></span></em>%</td>
				</tr>
				<tr>
					<td class="label">Maximum:</td>
					<td><em><span id="sensitivitymax" class="editable object:organ_data id:<?php echo $organData['id']?> attr:sensitivity_max"><?php echo $organData['sensitivity_max']?></span></em>%</td>
				</tr>
			</table>
		</div>
		<!-- SPECIFICITY -->
		<div class="rightcol" style="margin-left:0px;margin-top:-15px;width:24%;">
			<h4>Specificity:</h4>
			<table style="background-color:#fff;">
				<tr>
					<td class="label">Minimum:</td>
					<td><em><span id="specificitymin" class="editable object:organ_data id:<?php echo $organData['id']?> attr:specificity_min"><?php echo $organData['specificity_min']?></span></em>%</td>
				</tr>
				<tr>
					<td class="label">Maximum:</td>
					<td><em><span id="specificitymax" class="editable object:organ_data id:<?php echo $organData['id']?> attr:specificity_max"><?php echo $organData['specificity_max']?></span></em>%</td>
				</tr>
			</table>
		</div>
		<!-- NEGATIVE PREDICTIVE VALUE -->
		<div class="rightcol" style="margin-left:0px;margin-top:-15px;width:24%;">
			<h4>N.P.V.:</h4>
			<table style="background-color:#fff;">
				<tr>
					<td class="label">Minimum:</td>
					<td><em><span id="npvmin" class="editable object:organ_data id:<?php echo $organData['id']?> attr:npv_min"><?php echo $organData['npv_min']?></span></em>%</td>
				</tr>
				<tr>
					<td class="label">Maximum:</td>
					<td><em><span id="npvmax" class="editable object:organ_data id:<?php echo $organData['id']?> attr:npv_max"><?php echo $organData['npv_max']?></span></em>%</td>
				</tr>
			</table>
		</div>
		<!-- POSITIVE PREDICTIVE VALUE -->
		<div class="rightcol" style="margin-left:0px;margin-top:-15px;width:24%">
			<h4>P.P.V.:</h4>
			<table style="background-color:#fff;">
				<tr>
					<td class="label">Minimum:</td>
					<td><em><span id="ppvmin" class="editable object:organ_data id:<?php echo $organData['id']?> attr:ppv_min"><?php echo $organData['ppv_min']?></span></em>%</td>
				</tr>
				<tr>
					<td class="label">Maximum:</td>
					<td><em><span id="ppvmax" class="editable object:organ_data id:<?php echo $organData['id']?> attr:ppv_max"><?php echo $organData['ppv_max']?></span></em>%</td>
				</tr>
			</table>
		</div>
		<div class="clr"><!-- clear --></div>
		<p>&nbsp;</p>
		</div>
		
		
		
		
		<h3 style="position:relative;">Supporting Study Data
			<div class="editlink">
				<span class="fakelink toggle:addstudydata">+ Add Study</span>
			</div>
		</h3>
		<div id="addstudydata" class="addstudydata" style="display:none;">
			<h5 style="margin-bottom:5px;margin-left:1px;">Associate a Study:</h5>
			<div style="width:80%;">
				<form action="/<?php echo PROJROOT;?>/biomarkers/addorganstudydata" method="POST">
					<input type="hidden" name="organ_data_id" value="<?php echo $organData['id']?>"/>
					<input type="hidden" name="biomarker_id"  value="<?php echo $biomarker['Biomarker']['id']?>"/>
					<input type="hidden" id="study_id" name="study_id" value=""/>
					<input type="text" id="study-search" value="" style="width:100%;"/>
					<span class="hint" style="float:left;margin-top:3px;">Begin typing. A list of options will appear.</span>
					<input type="button" class="cancelbutton toggle:addstudydata" value="Cancel" style="float:right;padding:2px;margin:6px;margin-right:-4px;"/>
					<input type="submit" name="associate_study" value="Associate" style="float:right;padding:2px;margin:6px;margin-right:0px;"/>
					
				</form>
				<div class="clr"><!-- clear --></div>
			</div>
		</div>
		<?php foreach ($organData['StudyData'] as $study):?>
			<h4 style="margin:0px;margin-left:60px;padding-top:5px;border-left:solid 1px #ccc;border-top:solid 1px #ccc;"><?php echo $study['Study']['title']?>
				<div class="editlink">
					<a href="/<?php echo PROJROOT;?>/biomarkers/removeOrganStudyData/<?php echo $biomarker['Biomarker']['id']?>/<?php echo $organData['id']?>/<?php echo $study['id']?>" style="color:#d55;">x Delete</a>	
				</div>	
			</h4>
			<div class="studydetail">
				<div class="lefttext" style="margin-right:16px;">
					<span id="description" class="textarea"><?php printor(substr($study['Study']['studyAbstract'],0,600).'&nbsp;<a href="/'.PROJROOT.'/studies/view/'.$study['Study']['id'].'" style="text-decoration:underline;font-size:90%;"><em>Click here to read more about this study</em></a>','<em>No Description Provided Yet.</em>');?></span>
				</div>
				<!-- RELEVANT STUDY DATA -->
				<div class="rightcol" style="margin-left:0px;margin-top:0;">
					<h4>Study Results:</h4>
					<table>
						<tr>
							<td class="label">Sensitivity:</td>
							<td><em><span id="sensitivity<?php echo $study['id']?>" class="editable object:organ_study_data id:<?php echo $study['id']?> attr:sensitivity"><?php echo $study['sensitivity']?></span></em>%</td>
						</tr>
						<tr>
							<td class="label">Specificity:</td>
							<td><em><span id="specificity<?php echo $study['id']?>" class="editable object:organ_study_data id:<?php echo $study['id']?> attr:specificity"><?php echo $study['specificity']?></span></em>%</td>
						</tr>
							<tr>
							<td class="label">NPV:</td>
							<td><em><span id="npv<?php echo $study['id']?>" class="editable object:organ_study_data id:<?php echo $study['id']?> attr:npv"><?php echo $study['npv']?></span></em>%</td>
						</tr>
						<tr>
							<td class="label">PPV:</td>
							<td><em><span id="ppv<?php echo $study['id']?>" class="editable object:organ_study_data id:<?php echo $study['id']?> attr:ppv"><?php echo $study['ppv']?></span></em>%</td>
						</tr>
					</table>
					<br/>
					<a style="text-decoration:underline;font-size:90%;" href="/<?php echo PROJROOT;?>/studies/view/<?php echo $study['Study']['id']?>">Go to this study's definition</a>
				</div>
				<div class="clr"><!-- clear --></div>
				<br/>
				<h5 style="position:relative;border-bottom:solid 1px #999;">Related Publications
					<div class="editlink" style="font-size:100%;margin-top:-8px;">
						<span class="fakelink toggle:addstudypub<?php echo $study['id']?>">+ Edit This List</span>
					</div>
				</h5>
				<div id="addstudypub<?php echo $study['id']?>" class="addstudypub" style="margin-left:14px;display:none;">
					<form action="/<?php echo PROJROOT;?>/biomarkers/addstudydatapub" method="POST">
						<input type="hidden" name="biomarker_id"  value="<?php echo $biomarker['Biomarker']['id']?>"/>
						<input type="hidden" name="organ_data_id" value="<?php echo $organData['id']?>"/>
						<input type="hidden" name="study_data_id" value="<?php echo $study['id']?>"/>
						<input type="hidden" id="publication<?php echo $study['id']?>_id" name="pub_id" value=""/>
						<input type="text" class="pubsearch id:<?php echo $study['id']?>" id="publication<?php echo $study['id']?>search" value="" style="width:90%;"/>
						<span class="hint" style="float:left;margin-top:3px;">Begin typing a publication title. A list of options will appear.<br/>
		Don't see the publication you want? <a href="/<?php echo PROJROOT;?>/publications/import">Import a new publication</a></span>
						<input type="button" class="cancelbutton toggle:addstudypub<?php echo $study['id']?>" value="Cancel" style="float:right;padding:2px;margin:6px;margin-right:0px;"/>
						<input type="submit" name="associate_pub" value="Associate" style="float:right;padding:2px;margin:6px;margin-right:0px;"/>
						
					</form>
					<div class="clr"><!-- clear --></div>
				</div>

				<ul style="margin-left:20px;margin-top:10px;font-size:90%;">
				<?php foreach ($study['Publication'] as $publication):?>
					<li><div class="studypubsnippet">
							<a href="#"><?php echo $publication['title']?></a> &nbsp;[<a href="/<?php echo PROJROOT;?>/biomarkers/removeStudyDataPub/<?php echo $biomarker['Biomarker']['id']?>/<?php echo $organData['id']?>/<?php echo $study['id']?>/<?php echo $publication['id']?>">Remove this association</a>]<br/>
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
						<span class="fakelink toggle:addstudyres<?php echo $study['id']?>">+ Edit This List</span>
					</div>
				</h5>
				<div id="addstudyres<?php echo $study['id']?>" class="addstudyres" style="margin-left:14px;display:none;">
					<form action="/<?php echo PROJROOT;?>/biomarkers/addStudyDataResource" method="POST" style="margin-top:5px;">
						<input type="hidden" name="biomarker_id"  value="<?php echo $biomarker['Biomarker']['id']?>"/>
						<input type="hidden" name="organ_data_id" value="<?php echo $organData['id']?>"/>
						<input type="hidden" name="study_data_id" value="<?php echo $study['id']?>"/>
						<div style="float:left;width:90px;color:#555;">URL: &nbsp;&nbsp;http://</div>
						<input type="text" style="width:80%;" name="url"/><br/><br/>
						<div style="float:left;width:90px;color:#555;">Description:</div>
						<input type="text" name="desc" style="float:left;width:50%;"/>
						<input type="submit" name="associate_res" value="Associate" style="float:left;padding:2px;margin-right:0px;margin-left:6px;"/>
						<input type="button" class="cancelbutton toggle:addstudyres<?php echo $study['id']?>" value="Cancel" style="float:left;padding:2px;margin:0px;margin-right:0px;margin-left:6px;"/>
						
					</form>
					<div class="clr"><!-- clear --></div>
				</div>
				
				<ul style="margin-left:20px;margin-top:10px;font-size:90%;">
				<?php foreach ($study['StudyDataResource'] as $resource):?>
					<li><div class="studyressnippet">
							<a href="http://<?php echo $resource['URL']?>"><?php echo $resource['URL']?></a>&nbsp;&nbsp;[<a href="/<?php echo PROJROOT;?>/biomarkers/removeStudyDataResource/<?php echo $biomarker['Biomarker']['id']?>/<?php echo $organData['id']?>/<?php echo $study['id']?>/<?php echo $resource['id']?>">Remove this association</a>]<br/>
							<span style="color:#555;font-size:90%;">
							<?php echo $resource['description']?>
							</span>
						</div>
					</li>
				
				
				<?php endforeach;?>
				</ul>
				<br/> 
			</div>
		
		
		<?php endforeach;?>
		<h3 style="position:relative;margin-bottom:0px;">Supporting Publication Data &nbsp;(Biomarker-Organ level)
			<div class="editlink">
				<span class="fakelink toggle:addstudypub">+ Add a Publication</a>
			</div>
		</h3>
		<div id="addstudypub" class="addstudypub" style="margin-left:16px;padding-top:8px;display:none;">
			<form action="/<?php echo PROJROOT;?>/biomarkers/addOrganDataPub" method="POST">
				<input type="hidden" name="biomarker_id"  value="<?php echo $biomarker['Biomarker']['id']?>"/>
				<input type="hidden" name="organ_data_id" value="<?php echo $organData['id']?>"/>
				<input type="hidden" id="organpublication_id" name="pub_id" value=""/>
				<input type="text" id="organpublicationsearch" value="" style="width:90%;"/>
				<span class="hint" style="float:left;margin-top:3px;">Begin typing a publication title. A list of options will appear.<br/>
		Don't see the publication you want? <a href="/<?php echo PROJROOT;?>/publications/import">Import a new publication</a></span>
				<input type="button" class="cancelbutton toggle:addstudypub" value="Cancel" style="float:right;padding:2px;margin:6px;margin-right:0px;"/>
				<input type="submit" name="associate_pub" value="Associate" style="float:right;padding:2px;margin:6px;margin-right:0px;"/>
				
			</form>
			<div class="clr"><!-- clear --></div>
		</div>
		<ul style="margin-left:20px;margin-top:10px;font-size:90%;">
		<?php foreach ($organData['Publication'] as $publication):?>
			<li><div class="studypubsnippet">
					<a href="#"><?php echo $publication['title']?></a> &nbsp;[<a href="/<?php echo PROJROOT;?>/biomarkers/removeOrganDataPub/<?php echo $biomarker['Biomarker']['id']?>/<?php echo $organData['id']?>/<?php echo $publication['id']?>">Remove this association</a>]<br/>
					<span style="color:#555;font-size:90%;">Author:
					<?php echo $publication['author']?>. &nbsp; Published in
					<?php echo $publication['journal']?>, &nbsp;
					<?php echo $publication['published']?></span>
				</div>
			</li>
		<?php endforeach;?>
		</ul>
		
		<h3 style="position:relative;margin-bottom:0px;">Supporting Resource Data &nbsp;(Biomarker-Organ level)
			<div class="editlink">
				<span class="fakelink toggle:addstudyres">+ Add a Resource</span>
			</div>
		</h3>
		<div id="addstudyres" class="addstudyres" style="margin-left:16px;display:none;">
			<form action="/<?php echo PROJROOT;?>/biomarkers/addOrganDataResource" method="POST" style="margin-top:5px;">
				<input type="hidden" name="biomarker_id"  value="<?php echo $biomarker['Biomarker']['id']?>"/>
				<input type="hidden" name="organ_data_id" value="<?php echo $organData['id']?>"/>
				<div style="float:left;width:90px;color:#555;">URL: &nbsp;&nbsp;http://</div>
				<input type="text" style="width:80%;" name="url"/><br/><br/>
				<div style="float:left;width:90px;color:#555;">Description:</div>
				<input type="text" name="desc" style="float:left;width:50%;"/>
				<input type="submit" name="associate_res" value="Associate" style="float:left;padding:2px;margin-right:0px;margin-left:6px;"/>
				<input type="button" class="cancelbutton toggle:addstudyres" value="Cancel" style="float:left;padding:2px;margin:0px;margin-right:0px;margin-left:6px;"/>
				
			</form>
			<div class="clr"><!-- clear --></div>
		</div>
		<ul style="margin-left:20px;margin-top:10px;font-size:90%;">
			<?php foreach ($organData['OrganDataResource'] as $resource):?>
				<li><div class="studyressnippet">
						<a href="http://<?php echo $resource['URL']?>"><?php echo $resource['URL']?></a>&nbsp;&nbsp;[<a href="/<?php echo PROJROOT;?>/biomarkers/removeOrganDataResource/<?php echo $biomarker['Biomarker']['id']?>/<?php echo $organData['id']?>/<?php echo $resource['id']?>">Remove this association</a>]<br/>
						<span style="color:#555;font-size:90%;">
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
<div style="margin-left:20px;margin-top:10px;color:#888;">
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
<h3 class="title" style="">Curation Actions</h3>
<form method="POST" action="/<?php echo PROJROOT;?>/biomarkers/addOrganData">
	<input type="hidden" name="biomarker_id" value="<?php echo $biomarker['Biomarker']['id']?>"/>
	<ul>
		<li style="color:#333;">Associate an Organ:<br/><br/>
		<select name="organ" style="width:100%;background-color:#eee;color:#333;padding:1px;">
			<?php foreach ($organ as $o):?>
				<option value="<?php echo $o['Organ']['id']?>"><?php echo $o['Organ']['name']?></option>
			<? endforeach;?>
		
		</select><br/><br/>
		<input type="submit" style="border:outset 1px #555;padding:2px;" value="Associate"/>
		
		
		</li>
	</ul>
</div>

</div><!-- end supplements -->
</div>
</div>

<script type="text/javascript">

  // Activate Edit-in-place text editors
  window.addEvent('domready', function() {
    new eip($$('.editable'), '/<?php echo PROJROOT;?>/biomarkers/savefield', {action: 'update'});
    new eiplist($$('.editablelist'),'/<?php echo PROJROOT;?>/biomarkers/savefield', {action: 'update'});
  });
  
  // Activate Study "Search" Autocomplete
  new Autocompleter.Local(
      $('study-search'),
      <?php
      	echo "[".$studystring."]";
      ?>
	  ,{
      'postData':{'object':'study','attr':'title'},
      'postVar': 'needle',
      'target' : 'study_id',
      'minLength' : 2,
      'parseChoices': function(el) {
        var value = el.getFirst().innerHTML;
        var id    = el.getFirst().id;
        alert(value);
        el.inputValue = value;
        el.inputId    = id;
        this.addChoiceEvents(el).getFirst().setHTML(this.markQueryValue(value));
      },
      'filterTokens': function(token) {
      	var regex = new RegExp('' + this.queryValue.escapeRegExp(), 'i');
      	return this.tokens.filter(function(token) {
          var d = token.split('|');
          return regex.test(d[0]);
        });
      }  
  });
  
  // Activate all StudyData Associate Publication autocomplete boxes
  $$('.pubsearch').each(function(input){
      // Get the id
      var classes = input.getProperty('class').split(" ");
      for (i=classes.length-1;i>=0;i--) {
        if (classes[i].contains('id:')) {
          var id = classes[i].split(":")[1];
        }
      }
      var idval = (id) ? id : '';
      new Autocompleter.Ajax.Xhtml(
        $('publication'+idval+'search'),
          '/<?php echo PROJROOT;?>/biomarkers/ajax_autocompletePublications', {
          'postData':{'object':'Publication','attr':'Title'},
          'postVar': 'needle',
          'target' : 'publication'+idval+'_id',
          'parseChoices': function(el) {
            var value = el.getFirst().innerHTML;
            var id    = el.getFirst().id;
            el.inputValue = value;
            el.inputId    = id;
            this.addChoiceEvents(el).getFirst().setHTML(this.markQueryValue(value));
          }  
       });
    });
    
  // Activate OrganData Associate Publication autocomplete box
  new Autocompleter.Ajax.Xhtml(
        $('organpublicationsearch'),
          '/<?php echo PROJROOT;?>/biomarkers/ajax_autocompletePublications', {
          'postData':{'object':'Publication','attr':'Title'},
          'postVar': 'needle',
          'target' : 'organpublication_id',
          'parseChoices': function(el) {
            var value = el.getFirst().innerHTML;
            var id    = el.getFirst().id;
            el.inputValue = value;
            el.inputId    = id;
            this.addChoiceEvents(el).getFirst().setHTML(this.markQueryValue(value));
          }
    });
    
   // Activate all Fake Links
   $$('.fakelink').each(function(a){
   	  // Get the id
      var classes = a.getProperty('class').split(" ");
      for (i=classes.length-1;i>=0;i--) {
        if (classes[i].contains('toggle:')) {
          var toggle = classes[i].split(":")[1];
        }
      }
      var toggleval = (toggle) ? toggle : '';
      a.addEvent('click',
        function() {
          if($(toggleval).style.display == 'none') {
            // show
            new Fx.Style(toggleval, 'opacity').set(0);
            $(toggleval).setStyle('display','block');
            $(toggleval).effect('opacity',{duration:400, transition:Fx.Transitions.linear}).start(0,1);
          } else {
            // hide
            $(toggleval).effect('opacity',{
              duration:200, 
              transition:Fx.Transitions.linear,onComplete:function(){
                $(toggleval).setStyle('display','none');
              }
            }).start(1,0);
          }
      });
   });
   
   // Activate all Cancel Buttons 
   $$('.cancelbutton').each(function(a){
   	  // Get the id
      var classes = a.getProperty('class').split(" ");
      for (i=classes.length-1;i>=0;i--) {
        if (classes[i].contains('toggle:')) {
          var toggle = classes[i].split(":")[1];
        }
      }
      var toggleval = (toggle) ? toggle : '';
      a.addEvent('click',
        function() {
          if($(toggleval).style.display == 'none') {
            // show
            new Fx.Style(toggleval, 'opacity').set(0);
            $(toggleval).setStyle('display','block');
            $(toggleval).effect('opacity',{duration:400, transition:Fx.Transitions.linear}).start(0,1);
          } else {
            // hide
            $(toggleval).effect('opacity',{
              duration:200, 
              transition:Fx.Transitions.linear,onComplete:function(){
                $(toggleval).setStyle('display','none');
              }
            }).start(1,0);
          }
      });
   });
  
</script>