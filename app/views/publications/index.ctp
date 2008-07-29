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
	<a href="/<?php echo PROJROOT;?>/">Home</a> / Publications
	</div><!-- End Breadcrumbs -->

</div>
<div class="searcher">
	<div>
	<form action="/<?php echo PROJROOT;?>/publications/goto" method="POST">
		<input type="hidden" id="publication_id" name="id" value=""/>
		<input type="text" id="publication-search" value=""/>
		<input type="submit" value="Search"/><br/>
		<a href="/<?php echo PROJROOT;?>/publications/import">Import a Publication from PubMed</a>
		<div class="clr"><!--  --></div>
	</form>
	</div>

</div>
<h2>Publication Directory:</h2>
<div class="hint" style="margin-top:-22px;margin-bottom:10px;color:#666;">
&nbsp;&nbsp;Browse the directory listing, or search by Title using the box on the right.
</div>
<!--
<div class="navigation">
	<a class="page current" href="#">1</a><a class="page " href="#">2</a><a class="page " href="#">3</a>
	<span>Note... these page navigation buttons do not work.</span>
</div>
-->
<br/>

<table id="elements" cellspacing="0" cellpadding="0">
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
    <td> <?php echo $html->link($publication['Publication']['title'],"/".PROJROOT."/publications/view/{$publication['Publication']['id']}");?> </a></td>
    <td> <?php echo $publication['Publication']['journal']?></td>
    <td> <?php echo $publication['Publication']['author']?></td>
    <td> <?php echo $publication['Publication']['published']?></td>
  </tr>
  <?php } else { ?>
  <tr style="background-color:#f4f4f4;">
    <td> <?php echo $html->link($publication['Publication']['title'],"/".PROJROOT."/publications/view/{$publication['Publication']['id']}");?> </a></td>
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
// Activate Study "Search" Autocomplete
  new Autocompleter.Local(
      $('publication-search'),
      <?php
      	echo "[".$publicationstring."]";
      ?>
	  ,{
      'postData':{'object':'study','attr':'title'},
      'postVar': 'needle',
      'target' : 'publication_id',
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