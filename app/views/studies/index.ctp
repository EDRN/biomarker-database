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
<div class="menu">
	<div class="mainContent">
		<h2 class="title">EDRN Biomarker Database</h2>
	</div>
	<!-- Breadcrumbs Area -->
	<div id="breadcrumbs">
	<a href="/<?php echo PROJROOT;?>/">Home</a> / Studies
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
	<form action="/<?php echo PROJROOT;?>/studies/goto" method="POST">
		<input type="hidden" id="study_id" name="id" value=""/>
		<input type="text" id="study-search" value=""/>
		<input type="submit" value="Search"/>
		<div class="clr"><!--  --></div>
	</form>
	</div>
	<a href="/<?php echo PROJROOT;?>/studies/create">Create a Non-EDRN Study</a>

</div>
<h2>Study Directory:</h2>
<div class="hint" style="margin-top:-22px;margin-bottom:10px;color:#666;">
&nbsp;&nbsp;Browse the directory listing, or search by Title using the box on the right.
</div>

<br/>

<? echo $this->renderElement('pagination'); // Render the pagination element ?> 
<table id="studyelements" cellspacing="0" cellpadding="0">
<?php
$pagination->setPaging($paging); // Initialize the pagination variables
$th = array (
            $pagination->sortBy('Title'),
            $pagination->sortBy('FHCRC_ID','EDRN Study ID'),
            $pagination->sortBy('collaborativeGroups','Organ Group'),
			'Abstract Clip'
); // Generate the pagination sort links
echo $html->tableHeaders($th); // Create the table headers with sort links if desired


  foreach ($studies as $study) {
	$valueToDisplay = (($study['Study']['studyAbstract'] != '') 
		? $study['Study']['studyAbstract']
		: (($study['Study']['studyObjective'] != '')
			? $study['Study']['studyObjective']
			: (($study['Study']['studySpecificAims'] != '')
				? $study['Study']['studySpecificAims']
				: (($study['Study']['studyResultsOutcome'])
					? $study['Study']['studyResultsOutcome']
					: '<em>No Information Provided</em>'
					)
				)
			)
		);
    $tr = array (
        $html->link($study['Study']['title'],"/studies/view/{$study['Study']['id']}"),
        $study['Study']['FHCRC_ID'],
        $study['Study']['collaborativeGroups'],
		substr($valueToDisplay,0,300)
        );
    echo $html->tableCells($tr,array('class'=>'altRow'),array('class'=>'evenRow'),true);
  }
?>
</table> 
<script>
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
</script>

