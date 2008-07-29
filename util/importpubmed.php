<?php
	require_once("../xpress/app.php");
	
	
	
	/**
	 * PUBMED DATA RETRIEVAL
	 */
	if (isset($_POST['pmid'])){
		$docs = '';
		

		
		if ($xml = simplexml_load_file('http://eutils.ncbi.nlm.nih.gov/entrez/eutils/esearch.fcgi?db=pubmed&retmax=0&usehistory=y&term=' . urlencode($_POST['pmid']) . '[uid]')){
		  if ($xml = simplexml_load_file("http://eutils.ncbi.nlm.nih.gov/entrez/eutils/esummary.fcgi?db=pubmed&retmode=xml&query_key={$xml->QueryKey}&WebEnv={$xml->WebEnv}&retstart=0&retmax=1")){
		    $docs = $xml->DocSum;
  		  }
		}
			
		$pubmed = array();
		$pubmed['ID']		= $docs->Id;
		$pubmed['Title']	= rtrim(trim($docs->Item[5],"["),"]");
		$pubmed['Author'] 	= $docs->Item[4];
		$pubmed['Journal'] 	= $docs->Item[2];
		$pubmed['Volume']	= $docs->Item[6];
		$pubmed['Issue']	= $docs->Item[7];
		$pubmed['Year']		= $docs->Item[0];
		
		$actionurl = APP::HTTP_ROOT ."/util/importpubmed.php";
		if (isset($_POST['update'])) {
			$update_div = $_POST['update'];
		} else {
			$update_div = "pubmedresult";
		}
		if (isset($_POST['updateID'])) {
			$update_id = "{$_POST['updateID']}";			
		} else {
			$update_id = "";
		}
		
		$displayForm = <<<ENDDISPLAYFORM
<div>
<form action="{$actionurl}" method="POST">
<input type="hidden" name="step" value="import"/>
<input id="pubmedID{$update_id}" type="hidden" name="ID" value="{$pubmed['ID']}"/>
<input id="pubmedTitle{$update_id}" type="hidden" name="Title" value="{$pubmed['Title']}"/>
<input id="pubmedAuthor{$update_id}" type="hidden" name="Author" value="{$pubmed['Author']}"/>
<input id="pubmedJournal{$update_id}" type="hidden" name="Journal" value="{$pubmed['Journal']}"/>
<input id="pubmedVolume{$update_id}" type="hidden" name="Volume" value="{$pubmed['Volume']}"/>
<input id="pubmedIssue{$update_id}" type="hidden" name="Issue" value="{$pubmed['Issue']}"/>
<input id="pubmedYear{$update_id}" type="hidden" name="Year" value="{$pubmed['Year']}"/>

The following information was retrieved from PubMed:<br/><br/>
<table style="font-size:90%;">
 		<tbody>
    	<tr><th style="text-align:left;">PubMed ID:</th><td>{$pubmed['ID']}</td></tr>
    	<tr><th style="text-align:left;">Title:</th><td>{$pubmed['Title']}</td></tr>
    	<tr><th style="text-align:left;">Author:</th><td>{$pubmed['Author']}</td></tr>
    	<tr><th style="text-align:left;">Journal:</th><td>{$pubmed['Journal']}</td></tr>
    	<tr><th style="text-align:left;">Volume:</th><td>{$pubmed['Volume']}</td></tr>
    	<tr><th style="text-align:left;">Issue:</th><td>{$pubmed['Issue']}</td></tr>
    	<tr><th style="text-align:left;">Year:</th><td>{$pubmed['Year']}</td></tr>
		<tr><td>&nbsp;</td>
			<td>&nbsp;</td>
		</tr>
 		</tbody>
</table>
<input type="button" class="importPubMed updateid:{$update_id}" value="Import Publication"/>
</form>
</div>
ENDDISPLAYFORM;
		echo $displayForm;
		exit();
	}
		
	/**
	 * PUBLICATION IMPORT
	 */
	if (isset($_POST['step']) && $_POST['step'] == 'import'){
		// Create a new publication object
		
		$pub = PublicationFactory::Create($_POST['ID']);
		$pub->setPubMedID($_POST['ID']);
		$pub->setTitle($_POST['Title']);
		$pub->setAuthor($_POST['Author']);
		$pub->setJournal($_POST['Journal']);
		$pub->setVolume($_POST['Volume']);
		$pub->setIssue($_POST['Issue']);
		$pub->setYear($_POST['Year']);
		$pub->setIsPubMed("1");
		
		echo "<div style=\"background-color:#cec;border:solid 1px #8c8;padding:1px;\">"
			."<strong>Successfully Imported:</strong><br/><em>{$pub->getPubMedID()} - {$pub->getTitle()}</em>.<br/> "
			."It is now accessible through the \"Local\" tab.</div>";
		exit();
	}

	

?>
