<?php
	// Include required CSS and JavaScript 
	echo $this->Html->css('bmdb-objects');
	echo $this->Html->css('eip');
	echo $this->Html->script('mootools-release-1.11');
	echo $this->Html->script('eip');

	echo $this->Html->css('autocomplete');
	echo $this->Html->script('autocomplete/Observer');
	echo $this->Html->script('autocomplete/Autocompleter');
	echo $this->Html->css('bmdb-browser');
?>
<div class="menu">
	<!-- Breadcrumbs Area -->
	<div id="breadcrumbs">
	<a href="/">Home</a> / Publications
	</div><!-- End Breadcrumbs -->

</div>
<div class="searcher">
	<div>
	<form action="/publications/redirection" method="POST">
		<input type="hidden" id="publication_id" name="id" value=""/>
		<input type="text" id="publication-search" value="Begin typing title keywords here..."/>
		<input type="submit" value="Search"/><br/>
		<a href="/publications/import">Import a Publication from PubMed</a>
		<div class="clr"><!--  --></div>
	</form>
	</div>

</div>
<h2>Publication Directory:</h2>
<div class="hint" style="margin-top:-22px;margin-bottom:10px;color:#666;">
&nbsp;&nbsp;Browse the directory listing, or search by Title using the box on the right.
</div>

<br/>

<table id="publicationelements" cellspacing="0" cellpadding="0">
  <tr>
    <th>Title</th>
    <th>Journal</th>
    <th>Author</th>
    <th>Published</th>
  </tr>
  <?php $count = 0;?>
  <?php foreach ($publications as $publication): ?>
  <?php if ($count++ % 2 == 0) {?>
  <tr>
    <td> <?php echo $this->Html->link($publication['Publication']['title'],"/publications/view/{$publication['Publication']['id']}");?> </a></td>
    <td> <?php echo $publication['Publication']['journal']?></td>
    <td> <?php echo $publication['Publication']['author']?></td>
    <td> <?php echo $publication['Publication']['published']?></td>
  </tr>
  <?php } else { ?>
  <tr style="background-color:#f4f4f4;">
    <td> <?php echo $this->Html->link($publication['Publication']['title'],"/publications/view/{$publication['Publication']['id']}");?> </a></td>
    <td> <?php echo $publication['Publication']['journal']?></td>
    <td> <?php echo $publication['Publication']['author']?></td>
    <td> <?php echo $publication['Publication']['published']?></td>
  </tr>
  <?php } ?>
  <?php endforeach;?>
</table>
<p>&nbsp;</p>
<p style="border-bottom:solid 2px #666;">&nbsp;</p>


<script>
  // Activate Publication Search autocomplete box
  new Autocompleter.Ajax.Xhtml(
   $('publication-search'),
     '/biomarkers/ajax_autocompletePublications', {
     'postData':{'object':'Publication','attr':'Title'},
     'postVar': 'needle',
     'target' : 'publication_id',
     'parseChoices': function(el) {
       var value = el.getFirst().innerHTML;
       var id    = el.getFirst().id;
       el.inputValue = value;
       el.inputId    = id;
       this.addChoiceEvents(el).getFirst().setHTML(this.markQueryValue(value));
     }
   });
</script>