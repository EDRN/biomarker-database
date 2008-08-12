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
<!--
<div class="navigation">
	<a class="page current" href="#">1</a><a class="page " href="#">2</a><a class="page " href="#">3</a>
	<span>Note... these page navigation buttons do not work.</span>
</div>
-->
<br/>

<table id="elements" cellspacing="0" cellpadding="0">
  <tr>
    <th>Name (sort:
    	<a href="/<?php echo PROJROOT;?>/biomarkers/index/sort/name/ascending">up</a>|<a href="/<?php echo PROJROOT;?>/biomarkers/index/sort/name/descending">down</a>)</th>
    <th>QA State</th>
    <th>Phase</th>
    <th>Created</th>
    <th>Type</th>
  </tr>
  <?php $count = 0;?>
  <?php foreach ($biomarkers as $biomarker): ?>
  <?php if ($count++ % 2 == 0) {?>
  <tr>
    <td> <?php echo $html->link($biomarker['Biomarker']['name'],"/biomarkers/view/{$biomarker['Biomarker']['id']}");?> </a></td>
    <td><?php printor($biomarker['Biomarker']['qastate'],'Unknown');?></td>
    <td><?php printor($biomarker['Biomarker']['phase'],'Unknown');?></td>
    <td><?php echo $biomarker['Biomarker']['created']?></td>
    <td><?php printor($biomarker['Biomarker']['type'],'Unknown')?></td>
  </tr>
  <?php } else { ?>
  <tr style="background-color:#f4f4f4;">
    <td> <?php echo $html->link($biomarker['Biomarker']['name'],"/biomarkers/view/{$biomarker['Biomarker']['id']}");?> </a></td>
    <td><?php printor($biomarker['Biomarker']['qastate'],'Unknown');?></td>
    <td><?php printor($biomarker['Biomarker']['phase'],'Unknown');?></td>
    <td><?php echo $biomarker['Biomarker']['created']?></td>
    <td><?php printor($biomarker['Biomarker']['type'],'Unknown')?></td>
  </tr>
  <?php } ?>
  <?php endforeach;?>
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