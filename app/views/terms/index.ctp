<?php
	// Include required CSS and JavaScript
	echo $html->css('bmdb-objects');
	
	echo $javascript->link('jquery/jquery-1.3.2.min');
	echo $javascript->link('jquery/plugins/dataTables/jquery.dataTables.min');
	
	echo $html->css('dataTables/dataTables.css');
	echo $html->css('bmdb-browser');
?>
<div class="menu">
	<!-- Breadcrumbs Area -->
	<div id="breadcrumbs">
	<a href="/<?php echo PROJROOT;?>/">Home</a> / Terms
	</div><!-- End Breadcrumbs -->

</div>

<h2>Term Directory:</h2>
<div class="hint" style="margin-top:-22px;margin-bottom:10px;color:#666;">
&nbsp;&nbsp;Browse the directory listing, or search for a term using the box on the right.
</div>

<br/>

<table id="termelements" class="dataTable" cellspacing="0" cellpadding="0">
  <thead>
  <tr>
    <th>Term</th>
    <th>Definition</th>
  </tr>
  </thead>
  <tbody>
  <?php $count = 0;?>
  <?php foreach ($terms as $term): ?>
  <?php if ($count++ % 2 == 0) {?>
  <tr>
    <td> <?php echo $html->link($term['Term']['label'],"/terms/view/{$term['Term']['id']}");?></td>
    <td> <?php echo $term['Term']['definition']?></td>
  </tr>
  <?php } else { ?>
  <tr style="background-color:#f4f4f4;">
    <td> <?php echo $html->link($term['Term']['label'],"/terms/view/{$term['Term']['id']}");?> </a></td>
    <td> <?php echo $term['Term']['definition']?></td>
  </tr>
  <?php } ?>
  <?php endforeach;?>
  </tbody>
</table>
<p>&nbsp;</p>
<p style="border-bottom:solid 2px #666;">&nbsp;</p>


<script>
  // Activate Term Search autocomplete box
  new Autocompleter.Ajax.Xhtml(
   $('term-search'),
     '/<?php echo PROJROOT;?>/terms/ajax_autocompleteTerms', {
     'postData':{'object':'Term','attr':'Label'},
     'postVar': 'needle',
     'target' : 'term_id',
     'parseChoices': function(el) {
       var value = el.getFirst().innerHTML;
       var id    = el.getFirst().id;
       el.inputValue = value;
       el.inputId    = id;
       this.addChoiceEvents(el).getFirst().setHTML(this.markQueryValue(value));
     }
   });
</script>

<script type="text/javascript">

$(document).ready(function() {
	// Turn the table into a sortable, searchable table
	$("#termelements").dataTable();
	// Give the search box the initial focus
	$("#termelements_filter > input").focus();

});
</script>