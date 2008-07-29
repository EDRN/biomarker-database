<?php echo $html->css('frozenobject');?>
<?php echo $html->css('eip');?>
<?php echo $html->css('autocomplete');?>
<?php echo $javascript->link('mootools-release-1.11');?>
<?php echo $javascript->link('eip');?>
<?php echo $javascript->link('autocomplete/Observer');?>
<?php echo $javascript->link('autocomplete/Autocompleter');?>
<?php 
	function printor($value,$alt,$no_zero = false) {
		if ($no_zero && $value == 0) {
			echo $alt;
			return;
		}

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
	<a href="/<?php echo PROJROOT;?>/">Home</a> /
	<a href="/<?php echo PROJROOT;?>/publications"> Publications</a> /
	Import From PubMed
	</div><!-- End Breadcrumbs -->

</div>
<h2>Import Publication:</h2>
<p style="margin-left:20px;margin-right:50px;">
All that is required to import a PubMed publication into the EDRN Biomarker Database is the PubMed ID of the 
publication you wish to import. This page communicates with the PubMed repository, requesting and retrieving
the <em>Title, Author, Journal,</em> and <em>Date of Publication</em> automatically. 
</p>
<p>&nbsp;</p>
<table style="margin-left:15px;">
  <tr>
  	<td>PubMed ID: &nbsp;</td>
  	<td>&nbsp;</td>
  	<td><form action="/<?php echo PROJROOT;?>/publications/ajax_retrieveInfo" method="POST">
  			<input type="text" name="pmid"/>&nbsp;&nbsp;
  			<input class="pubmed_search" type="button" value="Request"/>
  		</form>
  	</td>
  </tr>
  <tr><td colspan="3"><span class="hint">Note that it may take some time for a result to appear below...</span></td></tr>
</table>
<p>&nbsp;</p>
<div style="margin-left:15px;" id="pubmedresult">

</div>
<p>&nbsp;</p>


<script type="text/javascript">

  $$('.pubmed_search').each(function(button){
      button.addEvent('click',function(){
        var form = this.getParent();
        form.send({
          'update':'pubmedresult',
          'onComplete':activateImportButton
        });
      });
    });
    
  function activateImportButton() {
    // Activate all PubMed "Import Publication" buttons
    $$('.importPubMed').each(function(input){
      
      // Get the updateid
      var classes = input.getProperty('class').split(" ");
      for (i=classes.length-1;i>=0;i--) {
        if (classes[i].contains('updateid:')) {
          var updateid = classes[i].split(":")[1];
        }
      }

      var whichone = (updateid)? updateid : '';
      
      input.addEvent('click',function() {
        var tform = this.getParent();
        var udiv = $('pubmedresult'+whichone); 
        tform.send({
          update: 'pubmedresult'+whichone,
        });
      });
    });
  }

</script>