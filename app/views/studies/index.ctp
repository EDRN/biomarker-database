<?php
	// Include required CSS and JavaScript 
	echo $html->css('bmdb-objects');
	echo $html->css('eip');
	echo $html->script('mootools-release-1.11');
	echo $html->script('eip');

	echo $html->css('bmdb-browser');

	echo $html->script('jquery/jquery-1.8.2.min.js');
	echo $html->script('jquery/jquery-ui/jquery-ui-1.10.3.custom.js');
	echo $html->css('jquery-ui/jquery-ui-1.10.3.custom.min.css');
?>
<div class="menu">
	<!-- Breadcrumbs Area -->
	<div id="breadcrumbs">
	<a href="/<?php echo PROJROOT;?>/">Home</a> / Studies
	</div><!-- End Breadcrumbs -->

</div>
<div class="searcher">
	<div>
	<form action="/<?php echo PROJROOT;?>/studies/redirection" method="POST">
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

<?php echo $this->element('pagination'); // Render the pagination element ?>
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

