<?php
    App::uses('Biomarker', 'Model');
	// Include required CSS and JavaScript 
	echo $this->Html->css('bmdb-objects');
	echo $this->Html->css('eip');
	echo $this->Html->css('biomarkers');
    // In Cake 1.3+, use $this->Html->script instead of $javascript->link
	echo $this->Html->script('mootools-release-1.11');
	echo $this->Html->script('eip');
	echo $this->Html->script('jquery/jquery-1.8.2.min');
    echo $this->Html->script('jquery/plugins/dataTables/jquery.dataTables.min');
    echo $this->Html->css('dataTables/dataTables.css');
    echo $this->Html->css('bmdb-browser');
?>

<div class="menu">
	<!-- Breadcrumbs Area -->
	<div id="breadcrumbs">
		<span>You are here: &nbsp;</span>
		<a href="/">Home</a> :: 
		<a href="/biomarkers/">Biomarkers</a> ::
		<span><?php echo $biomarkerName?> <?php echo ((($biomarker['Biomarker']['isPanel']) == 1) ? ' (Panel)':'');?></span>
	</div><!-- End Breadcrumbs -->
</div>
<div id="outer_wrapper">
<div id="main_section">
<div id="content">

		<h2 class="biomarkerName">
			<span id="name" class="editable object:biomarker id:<?php echo $biomarker['Biomarker']['id']?> attr:name">
				<?php echo $biomarkerName?>
			</span> 
			<?php echo ((($biomarker['Biomarker']['isPanel']) == 1) ? '(Panel)':'');?>
		</h2>
		<h5 id="urn">urn:edrn:biomarker:<?php echo $biomarker['Biomarker']['id']?></h5>
		<h5>Created: <?php echo $biomarker['Biomarker']['created']?>. &nbsp;Last Modified: 
			<?php echo $biomarker['Biomarker']['modified']?>
		</h5>
			
		<div id="smalllinks">
			<ul>
			  <li class="activeLink"><a href="/biomarkers/view/<?php echo $biomarker['Biomarker']['id']?>">Basics</a></li>
			  <li class=""><a href="/biomarkers/organs/<?php echo $biomarker['Biomarker']['id']?>">Organs</a></li>
			  <li class=""><a href="/biomarkers/studies/<?php echo $biomarker['Biomarker']['id']?>">Studies</a></li>
			  <li class=""><a href="/biomarkers/publications/<?php echo $biomarker['Biomarker']['id']?>">Publications</a></li>
			  <li class=""><a href="/biomarkers/resources/<?php echo $biomarker['Biomarker']['id']?>">Resources</a></li>
			</ul>
			<div class="clr"><!--  --></div>
		</div>
		<div class="innercontent">
		<div class="lefttext" style="width:60%;">
			<span id="description" class="editable textarea object:biomarker id:<?php echo $biomarker['Biomarker']['id']?> attr:description">
				<?php Biomarker::printor($biomarker['Biomarker']['description'],'No Description Available Yet. Click here to add.');?>
			</span>
			<?php if ($biomarker['Biomarker']['isPanel']):?>
				<h2>Panel Details:</h2>
				<div style="position:relative;">
					<div id="togglePanelTools" class="editlink">
						<span class="fakelink toggle:panelSelection">+ Show/Hide Selector</span>
					</div>
				</div>
				<div id="panelSelection" style="display:none;">
					<div id="selectionPanelHeader">
						Available Biomarkers
						<div id="selectionPanelRightHeader">
							Panel Biomarkers
						</div>
					</div>

					<form action="/biomarkers/editPanel" method="POST">
						<input type="hidden" name="biomarker_id" value="<?php echo $biomarker['Biomarker']['id']?>"/>
						<input type="hidden" name="values" id="panel_biomarker_values" value=""/>
						<select id="allBiomarkers" name="allBiomarkers" size="20"  multiple="MULTIPLE">


<?php
# 2014-09-25
# Function Added by Ashish Mahabal to sort biomarker names in the panels
# Issue: When biomarkers are moved to the right, they are appended rather
# than automatically sorted
# Later, if they are brough back to the left, they are appended there too.

function array_sort($array, $on)
{
    $sortable_array = array();

    if (count($array) > 0){
        for($a = 0; $a < count($array); ++$a){
                foreach ($array[$a] as $k => $v){
                    if ($k == $on){
                        $sortable_array[$v] = $array[$a];
                        }       # endif;
                        }       # endforeach
        }       # endfor
        }       # endif

        ksort($sortable_array);
    return $sortable_array;
}

$savailableMarkers = array_sort($availableMarkers, 'name');
$spanelMarkers = array_sort($panelMarkers, 'defaultName');
?>

<?php
foreach($savailableMarkers as $a => $b){
	echo "<option id='opt-'",$b['id']," value=", $b['id'],">", $b['name'], "</option>";
	}
?>
						</select>
						<select id="panelBiomarkers" name="panelBiomarkers" size="20" multiple="MULTIPLE">
						<?php foreach ($spanelMarkers as $a => $b): ?>
							<option id="opt-<?php echo $b['id']?>" value="<?php echo $b['id']?>"><?php echo $b['defaultName']?></option>
						<?php endforeach;?>
						</select>
						<div class="clear"></div>
						<div id="panelSelectionButtons">
						  <input id="moveright" type="button" value="Move Selected Right" />&nbsp;
						  <input id="save"      type="submit" value="Save Changes"/>&nbsp;
						  <input id="moveleft"  type="button" value="Move Selected Left" />
						</div> 
					</form>
				</div> 
				<ul id="panelBiomarkerList">
				  <?php foreach($panelMarkers as $b):?>
				  	<li id="panelBiomarkerListItem">
				  		<a href="/biomarkers/view/<?php echo $b['id']?>">
				  			<?php echo $b['defaultName']?>
				  		</a>
				  	</li>
				  <?php endforeach ?>
				</ul>
			
			<?php endif; /* end if isPanel= 1 */?>
			<hr id="curatorNotesDivider" />
			<span id="curatorNotes" class="editable textarea object:biomarker id:<?php echo $biomarker['Biomarker']['id']?> attr:curatorNotes">
				<?php Biomarker::printor($biomarker['Biomarker']['curatorNotes'],'Click here to add curation notes.');?>
			</span>
		</div>
		<div id="rightcol" style="width:35%;">
		<!-- BASIC ATTRIBUTES -->
		<h4>Attributes:</h4>
		<table id="attributesTable" cellspacing="0" cellpadding="3">
			<tr>
				<td class="label">Security:</td>
				<td><em>
					<span id="security" class="editablelist object:biomarker id:<?php echo $biomarker['Biomarker']['id']?> attr:security opts:Public|Private">
						<?php Biomarker::printor($biomarker['Biomarker']['security'],'click to select')?>
					</span>
					</em>
				</td>
			</tr>
			<tr>
				<td class="label">QA State:</td>
				<td>
					<span id="qastate">
						<em>
							<span id="qastate" class="editablelist object:biomarker id:<?php echo $biomarker['Biomarker']['id']?> attr:qastate opts:New|Under_Review|Curated|Accepted|Rejected">
								<?php Biomarker::printor($biomarker['Biomarker']['qastate'],'click to select');?>
							</span>
						</em>
					</span>
				</td>
			</tr>
			<tr>
				<td class="label">Type:</td>
				<td>
					<em>
					<span id="type" class="editablelist object:biomarker id:<?php echo $biomarker['Biomarker']['id']?> attr:type opts:Gene|Protein|Genetic|Genomic|Epigenetic|Proteomic|Glycomic|Metabolomic">
						<?php Biomarker::printor($biomarker['Biomarker']['type'],'click to select')?>
					</span>
					</em>
				</td>
			</tr>
		</table>
		<h4>Alternative Names</h4>
		<table id="altNamesTable" cellspacing="0" cellpadding="3">
			<tr id="header">
			  <th>HGNC</th>
			  <th>Default</th>
			  <th>Name</th>
			</tr>
			<?php foreach ($biomarker['BiomarkerName'] as $alias):?>
			<tr>
			  <td class="centerText">
				<input name="hgnc" value="<?php echo $alias['id']?>" class="hgnc" type="radio" <?php if($alias['isHgnc'] == 1) { echo 'checked="checked"';}?>/>
			  </td>
			  <td class="centerText">
				<input name="alias" value="<?php echo $alias['id']?>" class="alias" type="radio" <?php if($alias['isPrimary'] == 1) { echo 'checked="checked"';}?>/>
			  </td>
			  <td>
			  	<?php echo $alias['name']?>&nbsp;&nbsp;
			  	<?php if ($alias['isPrimary'] != 1) {
                    $PROJROOT = '';
                    echo '<a class="removealias" title="remove this alias" href="/biomarkers/removeAlias/';
                    echo $alias['id'];
                    echo '">x</a>';
			  	}?>
			  </td>
			</tr>
			<?php endforeach;?>
			<tr>
			  <td id="addAltName" colspan="3">Add Alternative Name:</td>
			</tr>
			<tr>
			  <td colspan="3">
			  	<form action="/biomarkers/addAlias" method="POST">
			  		<input type="hidden" name="biomarker_id" value="<?php echo $biomarker['Biomarker']['id']?>"/>
			  		<input type="text" name="altname"/>&nbsp;&nbsp;
			  		<input type="submit" value="Add"/>
			  	</form>
			  </td>
			</tr>
		</table>
		<h4>Panel Attributes</h4>
		<table cellspacing="0" cellpadding="0">
			<tr>
			  <td id="panelToggle">
			  	<form action="/biomarkers/addAlias" method="POST">
			  		<input type="hidden" name="biomarker_id" value="<?php echo $biomarker['Biomarker']['id']?>"/>
			  		<input id="ispanel" type="checkbox" <?php echo (($biomarker['Biomarker']['isPanel'] == 1) ? 'checked="checked" ' : '');?> name="<?php echo $biomarker['Biomarker']['id']?>"/> This Biomarker is a Panel
			  	</form>
			  </td>
			</tr>
			<tr>
			  <td id="panelMembershipHeader">Belongs to Panel(s):</td>
			</tr>
			<tr id="panelMembershipInfo">
			  <td>
			    <ul>
			      <?php foreach ($panelMembership as $p):?>
			      <li><a href="/biomarkers/view/<?php echo $p['id']?>"><?php echo $p['name']?></a></li>
			      <?php endforeach;?>
			    </ul>
			  </td>
			</tr>
		</table>

		</div>
		<div class="clr"><!-- clear --></div>
		
		</div>
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
	<ul>
	    <li><a href="/acls/edit/biomarker/<?php echo $biomarker['Biomarker']['id']?>">Set Security</a></li>
	    <li><a href="/biomarkers/data/<?php echo $biomarker['Biomarker']['id']?>">Associate Datasets</a></li>
		<li><a href="/biomarkers/delete/<?php echo $biomarker['Biomarker']['id']?>" onclick="return confirm('All data for this Biomarker will be permanently deleted. Continue?');">Delete This Biomarker</a></li>
	</ul>
</div>

</div><!-- end supplements -->
</div><!-- end main_section -->
<p>&nbsp;</p>
</div><!-- end outer_wrapper -->
<script type="text/javascript">

  window.addEvent('domready', function() {
  	
    new eip($$('.editable'), '/biomarkers/savefield', {action: 'update'});
    new eiplist($$('.editablelist'),'/biomarkers/savefield', {action: 'update'});
    
    $$('.alias').each(function(x){
    	x.addEvent('click',function() {
    		window.location.href = '/biomarkers/setPrimaryName/'+x.value;
    	});
    });
    
    $$('.hgnc').each(function(x) {
    	x.addEvent('click',function() {
    		window.location.href = '/biomarkers/setHgncName/'+x.value;
    	});
    });
    
    $('ispanel').addEvent('click',function() {
    	if (this.checked) {
    		window.location.href = '/biomarkers/setPanel/'+this.name+'/yes';
    	} else {
    		if (confirm("Really remove this relationship? No data will be deleted, but the panel-biomarker relationship will be undone.")) {
    			window.location.href = '/biomarkers/setPanel/'+this.name+'/no';
    		} else {
    			this.checked = true;
    		}
    	}
    });
    
    function addOption(which,value,text) { 	 
	         var o = new Element('option',{'id': 'opt-'+value, 'value': value}); 	 
	         o.innerHTML = text; 	 
	         o.inject(which); 	 
	     } 	 
	  	 
     function removeOption(which,value) { 	 
         sel = $(which); 	 
         for(i=0;i<sel.options.length;i++){ 	 
                 if(sel.options[i].value == value) { 	 
                         sel.options[i] = null; 	 
                         break; 	 
                 } 	 
         } 	 
     } 	 
	  	 
     $('moveright').addEvent('click',function() { 	 
         var all = $('allBiomarkers'); 	 
         for (i=0;i<all.options.length;i++) { 	 
  	 
           if(all.options[i].selected) { 	 
             value= all.options[i].value; 	 
             text = all.options[i].innerHTML; 	 
             removeOption('allBiomarkers',value); 	 
             addOption('panelBiomarkers',value,text); 	 
             i=-1; // reset to account for changed indices 	 
           } 	 
         } 	 
     }); 	 
	  	 
	  	 
     $('moveleft').addEvent('click',function() { 	 
         var panel = $('panelBiomarkers'); 	 
         for (i=0;i<panel.options.length;i++) { 	 
           if(panel.options[i].selected) { 	 
             value= panel.options[i].value; 	 
             text = panel.options[i].innerHTML; 	 
             removeOption('panelBiomarkers',value); 	 
             addOption('allBiomarkers',value,text); 	 
             i=-1; // reset to account for changed indices 	 
           } 	 
         } 	 
     }); 	 
	  	 
     $('save').addEvent('click',function() { 	 
         var str   = 'ignore'; 	 
         var panel = $('panelBiomarkers'); 	 
         for (i=0;i<panel.options.length;i++) { 	 
                 str += ','+panel.options[i].value; 	 
         } 	 
         $('panel_biomarker_values').value = str; 	 
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
    
    /* 
    	$$('.removealias').each(function(x) {
    		x.addEvent('click',function() {
    			return confirm('Really delete this alternative name?');
    		})
    	});
    */
    
  });

/*
# Added by Ashish on 2014-10-24
# In response to issue CA-1288
# Marked in Jira as BMDB beta 0.9.11
# The change involves replacing "< / script >" with about 40 lines
# (currently everything to the end of file)
# The change adds a search box on individual biomarker pages
# so that one does not have to go back.
*/

/*
Ashish - 2014-11-09
# Removing the $ variable conflict by using the jQuery.noConflict() function
*/

var $j = jQuery.noConflict();
$j(document).ready(function() {
        $j("#biomarkerelements").dataTable({
                "bProcessing": true,
                "bServerSide": true,
                "bDeferRender": true,
                "sAjaxSource": "/apis/biomarkers_search",
                "oLanguage": {
                        "oPaginate": {
                                "sNext": "",
                                "sPrevious": ""
                        }
                },
                "aoColumns": [
                        {"sWidth": "50%"},
                        {"sWidth": "15%"},
                        {"sWidth": "15%"},
                        {"sWidth": "10%"},
                        {"sWidth": "15%"},
                ],
		"aLengthMenu": [[-1, 10, 50], ["All", 10, 50]],
                "iDisplayLength": -1,
        });
});

</script>


<h2>Biomarker Directory:</h2>
<div class="hint" style="margin-top:-22px;margin-bottom:10px;color:#666;">
&nbsp;&nbsp;Browse the directory listing, or search any field using the search box on the right 👉
</div>

<br/>

<table id="biomarkerelements" class="dataTable" cellspacing="0" cellpadding="0">
<thead>
        <tr><th>Name</th><th>QA State</th><th>Type</th><th>Panel</th><th>Associated Organs</th></tr>
</thead>
<tbody>
</tbody>
</table>


<p>&nbsp;</p>
<p style="border-bottom:solid 2px #666;">&nbsp;</p>

