<?php echo $html->css('frozenobject');?>
<?php echo $html->css('eip');?>
<?php echo $html->css('autocomplete');?>
<?php echo $javascript->link('mootools-release-1.11');?>
<?php echo $javascript->link('eip');?>
<?php echo $javascript->link('autocomplete/Observer');?>
<?php echo $javascript->link('autocomplete/Autocompleter');?>
<?php echo $html->css('frozenbrowser');?>
<?php 
	function printor($value,$alt) {
		if ($value == "") {
			echo $alt;
		} else {
			echo $value;
		}
	}

?>
<?php echo $html->css('frozenbrowser');?>
<div class="menu">
	<div class="mainContent">
		<h2 class="title">EDRN Biomarker Database</h2>
	</div>
	<!-- Breadcrumbs Area -->
	<div id="breadcrumbs">
	<a href="/<?php echo PROJROOT;?>/">Home</a> / Biomarkers
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

</div>
<div class="searcher">
	<div>
	<form action="/<?php echo PROJROOT;?>/biomarkers/goto" method="POST">
		<input type="hidden" id="biomarker_id" name="id" value=""/>
		<input type="text" id="biomarker-search" value=""/>
		<input type="submit" value="Search"/>
		<div class="clr"><!--  --></div>
	</form>
	</div>
	<a href="/<?php echo PROJROOT;?>/biomarkers/create">Create a New Biomarker</a>

</div>
<h2>Biomarker Directory:</h2>
<div class="hint" style="margin-top:-22px;margin-bottom:10px;color:#666;">
&nbsp;&nbsp;Browse the directory listing, or search by Title using the box on the right.
</div>

<br/>

<? echo $this->renderElement('pagination'); // Render the pagination element ?> 
<table id="biomarkerelements" cellspacing="0" cellpadding="0">
<?php

$pagination->setPaging($paging); // Initialize the pagination variables
$th = array (
            $pagination->sortBy('Name'),
            $pagination->sortBy('qastate','QA State'),
            $pagination->sortBy('Type'),
			$pagination->sortBy('isPanel','Panel'),
			'Associated Organs'
); // Generate the pagination sort links
echo $html->tableHeaders($th); // Create the table headers with sort links if desired
foreach ($biomarkers as $biomarker) {
  	// Build 'organsForBiomarker' list
	$odatas = array();
	foreach ($biomarker['OrganDatas'] as $od) {
		$odatas[] = "<a href=\"/".PROJROOT."/biomarkers/organs/{$biomarker['Biomarker']['id']}/{$od['OrganData']['id']}\">{$od['Organ']['name']}</a>"; 
	}
	$organsForBiomarker = implode(", ",$odatas);
	if ($organsForBiomarker == "") { $organsForBiomarker = "<em style=\"color:#888;\">Unknown</em>";}
	$biomarkerName   = $biomarker['Names']['name'];
    $tr = array (
        $html->link($biomarkerName,"/biomarkers/view/{$biomarker['Biomarker']['id']}"),
        (($biomarker['Biomarker']['qastate'] == "")? "<em style=\"color:#888;\">Unknown</em>" : $biomarker['Biomarker']['qastate']),
        (($biomarker['Biomarker']['type'] == "")? "<em style=\"color:#888;\">Unknown</em>" : $biomarker['Biomarker']['type']),
		(($biomarker['Biomarker']['isPanel'] == 0) ? "No" : "Yes"),
		$organsForBiomarker
        );
    echo $html->tableCells($tr,array('class'=>'altRow'),array('class'=>'evenRow'),true);
}
?>
</table> 

<p>&nbsp;</p>
<p style="border-bottom:solid 2px #666;">&nbsp;</p>


<script>
// Activate Study "Search" Autocomplete
  new Autocompleter.Local(
      $('biomarker-search'),
      <?php
      	echo "[".$biomarkerstring."]";
      ?>
	  ,{
      'postData':{'object':'study','attr':'title'},
      'postVar': 'needle',
      'target' : 'biomarker_id',
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
</script>