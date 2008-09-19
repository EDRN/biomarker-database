<?php echo $html->css('frozenobject');?>
<?php echo $html->css('eip');?>
<?php echo $javascript->link('mootools-release-1.11');?>
<?php echo $javascript->link('eip');?>
<?php
	function printor($value,$alt) {
		if ($value == "") {
			echo $alt;
		} else {
			echo $value;
		}
	}
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
		<span><?php echo $biomarkerName?> </span>
		<div class="userdetails">
			<?php if (isset($_SESSION['username'])) {
				echo "Logged in as: {$_SESSION['username']}. &nbsp;";
				echo "<a href=\"/".PROJROOT."/users/logout\">Log Out</a>";
			} else {
				echo "Not Logged In. &nbsp; ";
				echo "<a href=\"/".PROJROOT."/users/login\">Log In</a>";
			}?>
		</div>
	</div><!-- End Breadcrumbs -->
		
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
</div>
<div id="outer_wrapper">
<div id="main_section">
<div id="content">

		<h2><span id="name" class="editable object:biomarker id:<?php echo $biomarker['Biomarker']['id']?> attr:name"><?php echo $biomarkerName?></span></h2>
		<h5 id="urn">urn:edrn:biomarker:<?php echo $biomarker['Biomarker']['id']?></h5>
		<h5>Created: <?php echo $biomarker['Biomarker']['created']?>. &nbsp;Last Modified: 
			<?php echo $biomarker['Biomarker']['modified']?></h5>
		<div class="innercontent">
		<div class="lefttext">
			<span id="description" class="editable textarea object:biomarker id:<?php echo $biomarker['Biomarker']['id']?> attr:description"><?php printor($biomarker['Biomarker']['description'],'No Description Available Yet. Click here to add.');?></span>
			
		</div>
		<div id="rightcol">
		<!-- BASIC ATTRIBUTES -->
		<h4>Attributes:</h4>
		<table>
			<tr>
				<td class="label">Security:</td>
				<td><em><span id="security" class="editablelist object:biomarker id:<?php echo $biomarker['Biomarker']['id']?> attr:security opts:Public|Private"><?php printor($biomarker['Biomarker']['security'],'click to select')?></span></em></td>
			</tr>
			<tr>
				<td class="label">QA State:</td>
				<td><span id="qastate"><em><span id="qastate" class="editablelist object:biomarker id:<?php echo $biomarker['Biomarker']['id']?> attr:qastate opts:New|Under_Review|Accepted|Rejected"><?php printor($biomarker['Biomarker']['qastate'],'click to select');?></span></em></td>
			</tr>
			<tr>
				<td class="label">Type:</td>
				<td><em><span id="type" class="editablelist object:biomarker id:<?php echo $biomarker['Biomarker']['id']?> attr:type opts:Gene|Protein|Genetic|Genomic|Epigenetic|Proteomic|Glycomic|Metabolomic"><?php printor($biomarker['Biomarker']['type'],'click to select')?></span></em></td>
			</tr>
		</table>
		<h4>Alternative Names</h4>
		<table>
			<tr>
			  <th style="text-align:left;width:50px;text-decoration:underline;">Default?</th><th style="text-align:left;text-decoration:underline;">Name</th>
			</tr>
			<?php foreach ($biomarker['BiomarkerName'] as $alias):?>
			<tr>
			  <td><input name="alias" value="<?php echo $alias['id']?>" class="alias" type="radio" <?php if($alias['isPrimary'] == 1) { echo 'checked="checked"';}?>/></td>
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
    
    $$('.removealias').each(function(x) {
    	x.addEvent('click',function() {
    		return confirm('Really delete this alternative name?');
    	})
    });
    
  });
  
</script>