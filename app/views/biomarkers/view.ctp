<?php
	// Include required CSS and JavaScript 
	echo $html->css('bmdb-objects');
	echo $html->css('eip');
	echo $javascript->link('mootools-release-1.11');
	echo $javascript->link('eip');
?>

<div class="menu">
	<!-- Breadcrumbs Area -->
	<div id="breadcrumbs">
		<span>You are here: &nbsp;</span>
		<a href="/<?php echo PROJROOT;?>/">Home</a> :: 
		<a href="/<?php echo PROJROOT;?>/biomarkers/">Biomarkers</a> ::
		<span><?php echo $biomarkerName?> <?php echo ((($biomarker['Biomarker']['isPanel']) == 1) ? ' (Panel)':'');?></span>
	</div><!-- End Breadcrumbs -->
</div>
<div id="outer_wrapper">
<div id="main_section">
<div id="content">

		<h2 class="biomarkerName"><span id="name" class="editable object:biomarker id:<?php echo $biomarker['Biomarker']['id']?> attr:name"><?php echo $biomarkerName?></span> <?php echo ((($biomarker['Biomarker']['isPanel']) == 1) ? '(Panel)':'');?></h2>
		<h5 id="urn">urn:edrn:biomarker:<?php echo $biomarker['Biomarker']['id']?></h5>
		<h5>Created: <?php echo $biomarker['Biomarker']['created']?>. &nbsp;Last Modified: 
			<?php echo $biomarker['Biomarker']['modified']?></h5>
			
		<div id="smalllinks">
			<ul>
			  <li class="activeLink"><a href="<?php echo PROJROOT;?>//biomarkers/view/<?php echo $biomarker['Biomarker']['id']?>">Basics</a></li>
			  <li class=""><a href="/<?php echo PROJROOT;?>/biomarkers/organs/<?php echo $biomarker['Biomarker']['id']?>">Organs</a></li>
			  <li class=""><a href="/<?php echo PROJROOT;?>/biomarkers/studies/<?php echo $biomarker['Biomarker']['id']?>">Studies</a></li>
			  <li class=""><a href="/<?php echo PROJROOT;?>/biomarkers/publications/<?php echo $biomarker['Biomarker']['id']?>">Publications</a></li>
			  <li class=""><a href="/<?php echo PROJROOT;?>/biomarkers/resources/<?php echo $biomarker['Biomarker']['id']?>">Resources</a></li>
			</ul>
			<div class="clr"><!--  --></div>
		</div>
		<div class="innercontent">
		<div class="lefttext">
			<span id="description" class="editable textarea object:biomarker id:<?php echo $biomarker['Biomarker']['id']?> attr:description"><?php Biomarker::printor($biomarker['Biomarker']['description'],'No Description Available Yet. Click here to add.');?></span>
			<?php if ($biomarker['Biomarker']['isPanel']):?>
				<h2>Panel Details:</h2>
				<div style="position:relative;">
					<div id="togglePanelTools" class="editlink" style="top:-15px;right:0">
						<span class="fakelink toggle:panelSelection">+ Show/Hide Selector</span>
					</div>
				</div>
				<div id="panelSelection" style="display:none;">
					<div style="position:relative;margin:5px;margin-bottom:0px;font-weight:bold;color:#733;">
						Available Biomarkers
						<div style="position:absolute;right:0px;top:0px;">
							Panel Biomarkers
						</div>
					</div>
					<form action="/<?php echo PROJROOT;?>/biomarkers/editPanel" method="POST">
						<input type="hidden" name="biomarker_id" value="<?php echo $biomarker['Biomarker']['id']?>"/>
						<input type="hidden" name="values" id="panel_biomarker_values" value=""/>
						<select id="allBiomarkers" name="allBiomarkers" size="20"  multiple="MULTIPLE">
						<?php foreach ($availableMarkers as $b): ?>
							<option id="opt-<?php echo $b['id'];?>" value="<?php echo $b['id'];?>"><?php echo $b['name'];?></option>
						<?php endforeach;?>
						
						</select>
						<select id="panelBiomarkers" name="panelBiomarkers" size="20" multiple="MULTIPLE" style="float:right;">
						<?php foreach ($panelMarkers as $b): ?>
							<option id="opt-<?php echo $b['id']?>" value="<?php echo $b['id']?>"><?php echo $b['defaultName']?></option>
						<?php endforeach;?>
						</select>
						<div style="clear:both;"><!-- clear --></div>
						<div id="panelSelectionButtons">
						  <input id="moveright" type="button" value="Move Selected Right" style="margin-left:auto;"/>&nbsp;
						  <input id="save"      type="submit" value="Save Changes"/>&nbsp;
						  <input id="moveleft"  type="button" value="Move Selected Left" style="margin-ight:auto;"/>
						</div> 
					</form>
				</div> 
				<ul style="margin-top:5px;list-style-type:square;color:#888;">
				  <?php foreach($panelMarkers as $b):?>
				  	<li style="margin:15px;"><a href="/<?php echo PROJROOT;?>/biomarkers/view/<?php echo $b['id']?>"><?php echo $b['defaultName']?></a></li>
				  <?php endforeach ?>
				</ul>
			
			<?php endif; /* end if isPanel= 1 */?>
		</div>
		<div id="rightcol">
		<!-- BASIC ATTRIBUTES -->
		<h4>Attributes:</h4>
		<table cellspacing="0" cellpadding="3" style="padding-bottom:2px;">
			<tr>
				<td class="label">Security:</td>
				<td><em><span id="security" class="editablelist object:biomarker id:<?php echo $biomarker['Biomarker']['id']?> attr:security opts:Public|Private"><?php Biomarker::printor($biomarker['Biomarker']['security'],'click to select')?></span></em></td>
			</tr>
			<tr>
				<td class="label">QA State:</td>
				<td><span id="qastate"><em><span id="qastate" class="editablelist object:biomarker id:<?php echo $biomarker['Biomarker']['id']?> attr:qastate opts:New|Under_Review|Accepted|Rejected"><?php Biomarker::printor($biomarker['Biomarker']['qastate'],'click to select');?></span></em></td>
			</tr>
			<tr>
				<td class="label">Type:</td>
				<td><em><span id="type" class="editablelist object:biomarker id:<?php echo $biomarker['Biomarker']['id']?> attr:type opts:Gene|Protein|Genetic|Genomic|Epigenetic|Proteomic|Glycomic|Metabolomic"><?php Biomarker::printor($biomarker['Biomarker']['type'],'click to select')?></span></em></td>
			</tr>
		</table>
		<h4>Alternative Names</h4>
		<table cellspacing="0" cellpadding="3" style="padding-bottom:2px;">
			<tr>
			  <th style="text-align:left;width:50px;">Default</th><th style="text-align:left;">Name</th>
			</tr>
			<?php foreach ($biomarker['BiomarkerName'] as $alias):?>
			<tr>
			  <td style="text-align:center;"><input name="alias" value="<?php echo $alias['id']?>" class="alias" type="radio" <?php if($alias['isPrimary'] == 1) { echo 'checked="checked"';}?>/></td>
			  <td><?php echo $alias['name']?>&nbsp;&nbsp;<?php if ($alias['isPrimary'] != 1) { echo '<a class="removealias" title="remove this alias" href="/'.PROJROOT."/biomarkers/removeAlias/{$alias['id']}\">x</a>";}?></td>
			</tr>
			<?php endforeach;?>
			<tr>
			  <td colspan="2" style="border-top:dotted 1px #999;padding-top:3px;">Add Alternative Name:</td>
			</tr>
			<tr>
			  <td colspan="2">
			  	<form action="/<?php echo PROJROOT?>/biomarkers/addAlias" method="POST">
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
			  <td style="padding-bottom:8px;">
			  	<form action="/<?php echo PROJROOT?>/biomarkers/addAlias" method="POST">
			  		<input type="hidden" name="biomarker_id" value="<?php echo $biomarker['Biomarker']['id']?>"/>
			  		<input id="ispanel" type="checkbox" <?php echo (($biomarker['Biomarker']['isPanel'] == 1) ? 'checked="checked" ' : '');?> name="<?php echo $biomarker['Biomarker']['id']?>"/> This Biomarker is a Panel
			  	</form>
			  </td>
			</tr>
			<tr>
			  <td style="border-top:solid 2px #999;padding-top:3px;">Belongs to Panel(s):</td>
			</tr>
			<tr>
			  <td style="font-size:120%;padding-left:8px;">
			    <ul style="margin-top:5px;list-style-type:square;color:#888;margin-bottom:8px;">
			      <?php foreach ($panelMembership as $p):?>
			      <li><a href="/<?php echo PROJROOT;?>/biomarkers/view/<?php echo $p['id']?>"><?php echo $p['name']?></a></li>
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
<h3 class="title" style="">Curation Actions</h3>
	<ul>
	    <li><a href="/<?php echo PROJROOT;?>/acls/edit/biomarker/<?php echo $biomarker['Biomarker']['id']?>">Set Security</a></li>
		<li><a href="/<?php echo PROJROOT;?>/biomarkers/delete/<?php echo $biomarker['Biomarker']['id']?>" onclick="return confirm('All data for this Biomarker will be permanently deleted. Continue?');">Delete This Biomarker</a></li>
	</ul>
</div>

</div><!-- end supplements -->
</div><!-- end main_section -->
<p>&nbsp;</p>
</div><!-- end outer_wrapper -->
<script type="text/javascript">

  window.addEvent('domready', function() {
  	
    new eip($$('.editable'), '/<?php echo PROJROOT;?>/biomarkers/savefield', {action: 'update'});
    new eiplist($$('.editablelist'),'/<?php echo PROJROOT;?>/biomarkers/savefield', {action: 'update'});
    
    $$('.alias').each(function(x){
    	x.addEvent('click',function() {
    		window.location.href = '/<?php echo PROJROOT?>/biomarkers/setPrimaryName/'+x.value;
    	});
    });
    
    $('ispanel').addEvent('click',function() {
    	if (this.checked) {
    		window.location.href = '/<?php echo PROJROOT?>/biomarkers/setPanel/'+this.name+'/yes';
    	} else {
    		if (confirm("Really remove this relationship? No data will be deleted, but the panel-biomarker relationship will be undone.")) {
    			window.location.href = '/<?php echo PROJROOT?>/biomarkers/setPanel/'+this.name+'/no';
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
  
</script>