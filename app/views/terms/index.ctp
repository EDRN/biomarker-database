<?php
	// Include required CSS and JavaScript
	echo $html->css('bmdb-objects');
	echo $html->css('eip');
	echo $javascript->link('mootools-release-1.11');
	echo $javascript->link('eip');

	echo $html->css('autocomplete');
	echo $javascript->link('autocomplete/Observer');
	echo $javascript->link('autocomplete/Autocompleter');
	echo $html->css('bmdb-browser');
?>
<div class="menu">
	<!-- Breadcrumbs Area -->
	<div id="breadcrumbs">
	<a href="/<?php echo PROJROOT;?>/">Home</a> / Terms
	</div><!-- End Breadcrumbs -->

</div>
<div class="searcher">
	<div>
	<form action="/<?php echo PROJROOT;?>/terms/redirection" method="POST">
		<input type="hidden" id="term_id" name="id" value=""/>
		<input type="text" id="term-search" value="Begin typing a term here..."/>
		<input type="submit" value="Search"/><br/>
		<a href="/<?php echo PROJROOT;?>/terms/define">Define a new Term</a>
		<div class="clr"><!--  --></div>
	</form>
	</div>
</div>
<h2>Term Directory:</h2>
<div class="hint" style="margin-top:-22px;margin-bottom:10px;color:#666;">
&nbsp;&nbsp;Browse the directory listing, or search for a term using the box on the right.
</div>

<br/>

<table id="termelements" cellspacing="0" cellpadding="0">
  <tr>
    <th>Term</th>
    <th>Definition</th>
  </tr>
  <?php $count = 0;?>
  <?php foreach ($terms as $term): ?>
  <?php if ($count++ % 2 == 0) {?>
  <tr>
    <td> <?php echo $html->link($term['Term']['label'],"/terms/view/{$term['Term']['id']}");?> </a></td>
    <td> <?php echo $term['Term']['label']?></td>
    <td> <?php echo $term['Term']['definition']?></td>
  </tr>
  <?php } else { ?>
  <tr style="background-color:#f4f4f4;">
    <td> <?php echo $html->link($term['Term']['label'],"/terms/view/{$term['Term']['id']}");?> </a></td>
    <td> <?php echo $term['Term']['label']?></td>
    <td> <?php echo $term['Term']['definition']?></td>
  </tr>
  <?php } ?>
  <?php endforeach;?>
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