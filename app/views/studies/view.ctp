<?php
	// Include required CSS and JavaScript 
	echo $html->css('bmdb-objects');
	echo $html->css('eip');
	echo $html->script('mootools-release-1.11');
	echo $html->script('eip');

	echo $html->css('autocomplete');
	//echo $html->script('autocomplete/Observer');
	//echo $html->script('autocomplete/Autocompleter');

	echo $html->script('jquery/jquery-1.8.2.min.js');
	echo $html->script('jquery/jquery-ui/jquery-ui-1.10.3.custom.js');
	echo $html->css('jquery-ui/jquery-ui-1.10.3.custom.min.css');
?>
<div class="menu">
	<!-- Breadcrumbs Area -->
	<div id="breadcrumbs">
		<span>You are here: &nbsp;</span>
		<a href="/<?php echo PROJROOT;?>/">Home</a> :: 
		<a href="/<?php echo PROJROOT;?>/studies/">Studies</a> ::
		<span><?php echo $study['Study']['title']?> </span>
	</div><!-- End Breadcrumbs -->

</div>
<div id="outer_wrapper">
<div id="main_section">
<div id="content">
<h2><?php echo $study['Study']['title']?></h2>
<h5 id="urn">urn:edrn:study:<?php echo $study['Study']['FHCRC_ID']?></h5>
	<div id="smalllinks">
		<ul>
		  <li class="activeLink"><a href="/<?php echo PROJROOT;?>/studies/view/<?php echo $study['Study']['id']?>">Basics</a></li>
		  <li class=""><a href="/<?php echo PROJROOT;?>/studies/publications/<?php echo $study['Study']['id']?>">Publications</a></li>
		  <li class=""><a href="/<?php echo PROJROOT;?>/studies/resources/<?php echo $study['Study']['id']?>">Resources</a></li>
		</ul>
		<div class="clr"><!--  --></div>
	</div>
		<table class="editable_info" cellspacing="0" cellpadding="0">
			<tr>
				<td class="label" style="width:100px;">Title:</td>
				<td><span id="title" class="editable object:study id:<?php echo $study['Study']['id']?> attr:title"><?php echo Biomarker::printor($study['Study']['title'],'click to edit');?></span></td>
			</tr>
			<tr>
				<td class="label" style="width:100px;">Abbreviated Name</td>
				<td><span id="altName" class="editable object:study id:<?php echo $study['Study']['id']?> attr:altName"><?php echo Biomarker::printor($study['Study']['altName'],'click to edit');?></span></td>
			</tr>
			<tr>
				<td class="label">Identifier (URN):</td>
				<td><span id="urn" style="color:#888;">urn:edrn:study:<?php echo $study['Study']['FHCRC_ID']?></span></td>
			</tr>
			<?php if($study['Study']['isEDRN'] == 1):?>
			<tr>
				<td class="label">Abstract:</td>
				<td style="text-align:justify;padding-left:18px;padding-right:18px;font-family:times,serif;font-size:110%;"><span id="studyAbstract" class=""><?php echo Biomarker::printor($study['Study']['studyAbstract'],'<em>No Data Available</em>');?></span></td>
			</tr>
			<tr>
				<td class="label">Specific Aims:</td>
				<td style="text-align:justify;padding-left:18px;padding-right:18px;font-family:times,serif;font-size:110%;"><span id="studySpecificAims" class=""><?php echo Biomarker::printor($study['Study']['studySpecificAims'],'<em>No Data Available</em>');?></span></td>
			</tr>
			<?php endif; /* isEDRN==1 */?>
			<?php if ($study['Study']['isEDRN'] != 1):?>
			<tr>
				<td class="label">Abstract:</td>
				<td style="text-align:justify;padding-left:18px;padding-right:18px;font-family:times,serif;font-size:110%;"><span id="studyAbstract" class="editable textarea object:study id:<?php echo $study['Study']['id']?> attr:studyAbstract"><?php echo Biomarker::printor($study['Study']['studyAbstract'],'click to edit');?></span></td>
			</tr>
			<tr>
				<td class="label">Specific Aims:</td>
				<td style="text-align:justify;padding-left:18px;padding-right:18px;font-family:times,serif;font-size:110%;"><span id="studySpecificAims" class="editable textarea object:study id:<?php echo $study['Study']['id']?> attr:studySpecificAims"><?php echo Biomarker::printor($study['Study']['studySpecificAims'],'click to edit');?></span></td>
			</tr>
			<?php endif; /* isEDRN !=1 */?>
			<tr>
			    <td class="label">Participating Sites:</td>
			    <td style="padding-left:18px;padding-right:18px;font-family:times,serif;font-size:110%;">
				<table style="width:100%;">
				  <tr>
				    <td style="border:none;width:65%;">
				      <table>
					<?php foreach ($sites as $site) : ?>
					  <tr>
					  <td style="color:#000;border:none;padding:0;margin:0;"><?php echo "{$site['sites']['name']}"?></td>
					  <td style="border:none;padding:0;padding-left:10px;margin:0;"><a style="color:#d55;font-size:90%;" alt="Remove" href="/<?php echo PROJROOT?>/studies/removeSite/<?php echo "{$site['sites']['site_id']}"?>/<?php echo "{$study['Study']['FHCRC_ID']}"?>/<?php echo "{$study['Study']['id']}"?>">x Remove</a></td>
					  </tr>
					<?php endforeach;?>
				      </table>
				    </td>
				    <td style="border:none;width:35%;">
				      <div style="float:right;vertical-align:top;">
				        <span class="fakelink toggle:site-add">+ Add Site</span>
				      </div>
				    </td>
				  </tr>
				</table>
				<div id="site-add" class="fadeOut">
				  <form action="/<?php echo PROJROOT?>/studies/addSite" method="POST">
				    <input type="hidden" name="study_id" value="<?php echo $study['Study']['FHCRC_ID']?>"/>
				    <input type="hidden" name="redirect_id" value="<?php echo $study['Study']['id']?>"/>
				    <input type="hidden" name="site_id" value=""/>
				    <input type="text" id="site-search" value=""/>
				    <input type="submit" value="Add"/>
				    <input type="button" value="Cancel" onclick="hide_input('#site-add');"/>
				  </form>
				</div>
			    </td>
			</tr>
			<tr>
			    <td class="label">Participating Researchers:</td>
			    <td style="padding-left:18px;padding-right:18px;padding-top:0;padding-bottom:0;font-family:times,serif;font-size:110%;">
				<table style="width:100%;">
				  <tr>
				    <td style="border:none;width:35%;">
				      <table>
					<?php foreach ($people as $person) : ?>
					  <tr>
					  <td style="color:#000;border:none;padding:0;margin:0;"><?php echo "{$person['people']['givenname']} {$person['people']['surname']}"?></td>
					  <td style="border:none;padding:0;padding-left:10px;margin:0;"><a style="color:#d55;font-size:90%;" alt="Remove" href="/<?php echo PROJROOT?>/studies/removeResearcher/<?php echo "{$person['person_study']['person_id']}"?>/<?php echo "{$person['person_study']['study_id']}"?>">x Remove</a></td>
					  </tr>
					<?php endforeach;?>
				      </table>
				    </td>
				    <td style="border:none;width:65%;">
				      <div style="float:right;vertical-align:top;">
				        <span class="fakelink toggle:person-add">+ Add Researcher</span>
				      </div>
				    </td>
				  </tr>
				</table>
				<div id="person-add" class="fadeOut">
				  <form action="/<?php echo PROJROOT?>/studies/addResearcher" method="POST">
				    <input type="hidden" name="study_id" value="<?php echo $study['Study']['id']?>"/>
				    <input type="hidden" name="person_id" value=""/>
				    <input type="text" id="researcher-search" value=""/>
				    <input type="submit" value="Add"/>
				    <input type="button" value="Cancel" onclick="hide_input('#person-add');"/>
				  </form>
				</div>
			    </td>
			</tr>
		</table>
		
</div><!-- end content -->
<div id="supplements">
<div class="box">
<h3 class="title">Actions</h3>
	<ul>
		<li><a href="#">Download as .PDF</a></li>
	</ul>
</div>

</div><!-- end supplements -->
</div><!-- end main_section -->
<p>&nbsp;</p>
</div><!-- end outer_wrapper -->

<script type="text/javascript">
  jQuery.noConflict();

  // Activate Edit-in-place text editors
  window.addEvent('domready', function() {
    new eip($$('.editable'), '/<?php echo PROJROOT;?>/studies/savefield', {action: 'update'});
    new eiplist($$('.editablelist'),'/<?php echo PROJROOT;?>/studies/savefield', {action: 'update'});
  });

// Activate all Fake Links
jQuery('.fakelink').each(function(index){
	var classes = jQuery(this).attr('class').split(/\s+/);

	for (i=classes.length-1;i>=0;i--) {
		if (classes[i].contains('toggle:')) {
			var toggle = classes[i].split(":")[1];
		}
	}
	var toggleval = (toggle) ? toggle : '';

	jQuery(this).click(function() {
		var toggleTarget = '#' + toggleval;

		if (jQuery(toggleTarget).hasClass("fadeOut")) {
			// show
			jQuery(toggleTarget).removeClass("fadeOut").addClass("fadeIn");
		} else {
			// hide
			jQuery(toggleTarget).removeClass("fadeIn").addClass("fadeOut");
		}
	});
});

  jQuery('#site-search').each(function() {
    jQuery(this).autocomplete({
      source: '/<?php echo PROJROOT;?>/studies/getSitesList',
      select: function(event, ul) {
        var person = ul.item.value.split('|')[0];
        var id = ul.item.value.split('|')[1];
        jQuery(this).siblings("[name='site_id']").val(id);
        ul.item.label = person;
        ul.item.value = person;
      }
    });
  });

  jQuery('#researcher-search').each(function() {
    jQuery(this).autocomplete({
      source: '/<?php echo PROJROOT;?>/studies/getResearcherList',
      select: function(event, ul) {
        var person = ul.item.value.split('|')[0];
        var id = ul.item.value.split('|')[1];
        jQuery(this).siblings("[name='person_id']").val(id);
        ul.item.label = person;
        ul.item.value = person;
      }
    });
  });

  jQuery.ui.autocomplete.prototype._renderItem = function(ul, item) {
    // Strip out the info we want
    var newLabel = item.label.split("|")[0];
  
    // Highlight the substring
    var re = new RegExp('(' + this.term + ')', 'i');
    var highlightedLabel = newLabel.replace(re, "<span style='font-weight:bold;color:#93d1ed;'>$1</span>");
    return jQuery("<li></li>")
                 .data("item.autocomplete", newLabel)
                 .append("<a>" + highlightedLabel + "</a>")
                 .appendTo(ul);
  };

  function hide_input(target) {
    jQuery(target).removeClass("fadeIn").addClass("fadeOut");
  }
</script>
