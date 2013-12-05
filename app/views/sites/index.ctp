<?php
	// Include required CSS and JavaScript 
	echo $html->css('bmdb-objects');
	echo $html->css('eip');
	echo $javascript->link('mootools-release-1.11');
	echo $javascript->link('eip');

	echo $html->css('bmdb-browser');
	echo $html->css('bmdb-sites');

	echo $javascript->link('jquery/jquery-1.8.2.min.js');
	echo $javascript->link('jquery/jquery-ui/jquery-ui-1.10.3.custom.js');
	echo $html->css('jquery-ui/jquery-ui-1.10.3.custom.min.css');
?>
<div class="menu">
	<!-- Breadcrumbs Area -->
	<div id="breadcrumbs">
	<a href="/<?php echo PROJROOT;?>/">Home</a> / Sites
	</div><!-- End Breadcrumbs -->
</div>
<div class="searcher">
	<div>
	<form action="/<?php echo PROJROOT;?>/sites/redirection" method="POST">
		<input type="hidden" id="site_id" name="id" value=""/>
		<input type="text" id="site_search" value=""/>
		<input type="submit" value="Search"/>
		<div class="clr"></div>
	</form>
	</div>
	<a href="/<?php echo PROJROOT;?>/sites/create">Create a new Site</a>
</div>
<h2>Site Directory:</h2>	

<? echo $this->renderElement('pagination'); ?>
<table id="studyelements">
<?php
	$pagination->setPaging($paging);
	
	$th = array(
		$pagination->sortBy('Site_Id'),
		$pagination->sortBy('Name')
	);
	echo $html->tableHeaders($th);

	foreach ($sites as $site) {
		$tr = array(
			$site['Site']['site_id'],
			$html->link($site['Site']['name'], "/sites/view/{$site['Site']['id']}"),
		);
	
		echo $html->tableCells($tr, array('class'=>'altRow'), array('class'=>'evenRow'), true);
	}
?>
</table>
<script>
$('#site_search').autocomplete({
	source: <?php echo "[" . $sitesString . "]" ?>,
	select: function(event, ui) {
		var siteName = ui.item.value.split('|')[0];
		var siteId = ui.item.value.split('|')[1];
		$(this).siblings('#site_id').val(siteId);

		ui.item.label = siteName;
		ui.item.value = siteName;
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
