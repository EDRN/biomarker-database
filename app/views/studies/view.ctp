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
<div class="menu">
	<div class="mainContent">
	<h2 class="title">EDRN Biomarker Database</h2>
	</div>
	<!-- Breadcrumbs Area -->
	<div id="breadcrumbs">
		<span style="color:#ddd;">You are here: &nbsp;</span>
		<a href="/<?php echo PROJROOT;?>/">Home</a> :: 
		<a href="/<?php echo PROJROOT;?>/studies/">Studies</a> ::
		<span><?php echo $study['Study']['title']?> </span>
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
		  <li class="activeLink"><a href="/<?php echo PROJROOT;?>/studies/view/<?php echo $study['Study']['id']?>">Basics</a></li>
		  <li class=""><a href="/<?php echo PROJROOT;?>/studies/publications/<?php echo $study['Study']['id']?>">Publications</a></li>
		  <li class=""><a href="/<?php echo PROJROOT;?>/studies/resources/<?php echo $study['Study']['id']?>">Resources</a></li>
		</ul>
		<div class="clr"><!--  --></div>
	</div>
</div>
<div id="outer_wrapper">
<div id="main_section">
<div id="content">
<h2><?php echo $study['Study']['title']?></h2>
<h5 id="urn">urn:edrn:study:<?php echo $study['Study']['FHCRC_ID']?></h5>

		<table class="editable_info" cellspacing="0" cellpadding="0">
			<tr>
				<td class="label" style="width:100px;">Title:</td>
				<td><span id="title" class="editable object:study id:<?php echo $study['Study']['id']?> attr:title"><?php echo printor($study['Study']['title'],'click to edit');?></span></td>
			</tr>

			<tr>
				<td class="label">Status:</td>
				<td><span id="status" class="editablelist object:study id:<?php echo $study['Study']['id']?> attr:status opts:Unregistered|Registered"><?php echo $study['Study']['biomarkerStudyType']?></span></td>
			</tr>
			<tr>
				<td class="label">Identifier (URN):</td>
				<td><span id="urn" style="color:#888;">urn:edrn:study:<?php echo $study['Study']['FHCRC_ID']?></span></td>
			</tr>
			<tr>
				<td class="label">Biomarker Population Characteristics</td>
				<td><span id="bpc" class="editablelist object:study id:<?php echo $study['Study']['id']?> attr:bioPopChar opts:Case_Control|Longitudinal|Randomized"><?php echo printor($study['Study']['bioPopChar'],'click to select');?></span></td>
			</tr>
			<tr>
				<td class="label">B.P.C. Description:</td>

				<td><span id="bpcdesc" class="editable textarea object:study id:<?php echo $study['Study']['id']?> attr:BPCDescription"><?php echo printor($study['Study']['BPCDescription'],'click to edit');?></span></td>
			</tr>
			<tr>
				<td class="label">Study Design:</td>
				<td><span id="studydesign" class="editablelist object:study id:<?php echo $study['Study']['id']?> attr:design opts:Retrospective|Prospective_Analysis|Cross_Sectional"><?php echo printor($study['Study']['design'],'click to select');?></span></td>
			</tr>
			<tr>
				<td class="label">Design Description:</td>

				<td><span id="designdesc" class="editable textarea object:study id:<?php echo $study['Study']['id']?> attr:designDescription"><?php echo printor($study['Study']['designDescription'],'click to edit');?></span></td>
			</tr>
			<?php if($study['Study']['isEDRN'] == 1):?>
			<tr>
				<td class="label">Abstract:</td>
				<td style="text-align:justify;padding-left:18px;padding-right:18px;font-family:times,serif;font-size:110%;"><span id="studyAbstract" class=""><?php echo printor($study['Study']['studyAbstract'],'<em>No Data Available</em>');?></span></td>
			</tr>
			<tr>
				<td class="label">Objective:</td>
				<td style="text-align:justify;padding-left:18px;padding-right:18px;font-family:times,serif;font-size:110%;"><span id="studyObjective" class=""><?php echo printor($study['Study']['studyObjective'],'<em>No Data Available</em>');?></span></td>
			</tr>
			<tr>
				<td class="label">Specific Aims:</td>
				<td style="text-align:justify;padding-left:18px;padding-right:18px;font-family:times,serif;font-size:110%;"><span id="studySpecificAims" class=""><?php echo printor($study['Study']['studySpecificAims'],'<em>No Data Available</em>');?></span></td>
			</tr>
			<tr>
				<td class="label">Results Outcome:</td>
				<td style="text-align:justify;padding-left:18px;padding-right:18px;font-family:times,serif;font-size:110%;"><span id="studyResultsOutcome" class=""><?php echo printor($study['Study']['studyResultsOutcome'],'<em>No Data Available</em>');?></span></td>
			</tr>
			<tr>
			    <td class="label">Participating Sites:</td>
			    <td style="padding-left:18px;padding-right:18px;font-family:times,serif;font-size:110%;">
			    	<ul>
			    	<?php foreach ($sites as $site) : ?>
			    	  <li style="color:#000;"><?php echo "{$site['sites']['name']} ({$site['sites']['site_id']})"?></li>
			    	<?php endforeach;?>
			    	</ul>
			    </td>
			</tr>
			<?php endif; /* isEDRN==1 */?>
			<?php if ($study['Study']['isEDRN'] != 1):?>
			<tr>
				<td class="label">Abstract:</td>
				<td style="text-align:justify;padding-left:18px;padding-right:18px;font-family:times,serif;font-size:110%;"><span id="studyAbstract" class="editable textarea object:study id:<?php echo $study['Study']['id']?> attr:studyAbstract"><?php echo printor($study['Study']['studyAbstract'],'click to edit');?></span></td>
			</tr>
			<tr>
				<td class="label">Objective:</td>
				<td style="text-align:justify;padding-left:18px;padding-right:18px;font-family:times,serif;font-size:110%;"><span id="studyObjective" class="editable textarea object:study id:<?php echo $study['Study']['id']?> attr:studyObjective"><?php echo printor($study['Study']['studyObjective'],'click to edit');?></span></td>
			</tr>
			<tr>
				<td class="label">Specific Aims:</td>
				<td style="text-align:justify;padding-left:18px;padding-right:18px;font-family:times,serif;font-size:110%;"><span id="studySpecificAims" class="editable textarea object:study id:<?php echo $study['Study']['id']?> attr:studySpecificAims"><?php echo printor($study['Study']['studySpecificAims'],'click to edit');?></span></td>
			</tr>
			<tr>
				<td class="label">Results Outcome:</td>
				<td style="text-align:justify;padding-left:18px;padding-right:18px;font-family:times,serif;font-size:110%;"><span id="studyResultsOutcome" class="editable textarea object:study id:<?php echo $study['Study']['id']?> attr:studyResultsOutcome"><?php echo printor($study['Study']['studyResultsOutcome'],'click to edit');?></span></td>
			</tr>
			    
			<?php endif; /* isEDRN !=1 */?>
		</table>
		
</div><!-- end content -->
<div id="supplements">
<div class="box">
<h3 class="title">Actions</h3>
	<ul>
		<li><a href="#">Download as .PDF</a></li>
	</ul>
</div>

</div><!-- end supplements -->
</div><!-- end main_section -->
<p>&nbsp;</p>
</div><!-- end outer_wrapper -->

<script type="text/javascript">

  // Activate Edit-in-place text editors
  window.addEvent('domready', function() {
    new eip($$('.editable'), '/<?php echo PROJROOT;?>/studies/savefield', {action: 'update'});
    new eiplist($$('.editablelist'),'/<?php echo PROJROOT;?>/studies/savefield', {action: 'update'});
  });
  
</script>