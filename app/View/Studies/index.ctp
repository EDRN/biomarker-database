<?php
	// Include required CSS and JavaScript 
	echo $this->Html->css('bmdb-objects');
	echo $this->Html->css('eip');
	echo $this->Html->script('mootools-release-1.11');
	echo $this->Html->script('eip');

	echo $this->Html->css('bmdb-browser');

	echo $this->Html->script('jquery/jquery-1.8.2.min.js');
	echo $this->Html->script('jquery/jquery-ui/jquery-ui-1.10.3.custom.js');
	echo $this->Html->css('jquery-ui/jquery-ui-1.10.3.custom.min.css');
?>
<div class="menu">
	<!-- Breadcrumbs Area -->
	<div id="breadcrumbs">
	<a href="/">Home</a> / Studies
	</div><!-- End Breadcrumbs -->

</div>
<div class="searcher">
	<div>
	<form action="/studies/redirection" method="POST">
		<input type="hidden" id="study_id" name="id" value=""/>
		<input type="text" id="study-search" value=""/>
		<input type="submit" value="Search"/>
		<div class="clr"><!--  --></div>
	</form>
	</div>
	<a href="/studies/create">Create a Non-EDRN Study</a>

</div>
<h2>Study Directory:</h2>
<div class="hint" style="margin-top:-22px;margin-bottom:10px;color:#666;">
&nbsp;&nbsp;Browse the directory listing, or search by Title using the box on the right.
</div>

<br/>

<?php echo $this->element('pagination'); // Render the pagination element ?>
<table id="studyelements" cellspacing="0" cellpadding="0">
<?php
$this->Pagination->setPaging($paging); // Initialize the pagination variables
$th = array (
            $this->Pagination->sortBy('Title'),
            $this->Pagination->sortBy('FHCRC_ID','EDRN Study ID'),
            $this->Pagination->sortBy('collaborativeGroups','Organ Group'),
			'Abstract Clip'
); // Generate the pagination sort links
echo $this->Html->tableHeaders($th); // Create the table headers with sort links if desired


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
        $this->Html->link($study['Study']['title'],"/studies/view/{$study['Study']['id']}"),
        $study['Study']['FHCRC_ID'],
        $study['Study']['collaborativeGroups'],
		substr($valueToDisplay,0,300)
        );
    echo $this->Html->tableCells($tr,array('class'=>'altRow'),array('class'=>'evenRow'),true);
  }
?>
</table> 
<script>

$('#study-search').autocomplete({
	source: <?php echo "[" . $studystring . "]" ?>,
	select: function(event, ui) {
		var studyName = ui.item.value.split('|')[0];
		var studyId = ui.item.value.split('|')[1];
		$(this).siblings('#study_id').val(studyId);

		ui.item.label = studyName;
		ui.item.value = studyName;
	}
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

</script>

