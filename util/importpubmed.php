<?php
	require_once("../model/ModelProperties.inc.php");
	
	$xp = new XPress();
	
	if (isset($_GET['pmid'])){
		$docs = '';
		
		if ($xml = simplexml_load_file('http://eutils.ncbi.nlm.nih.gov/entrez/eutils/esearch.fcgi?db=pubmed&retmax=0&usehistory=y&term=' . urlencode($_GET['pmid']) . '[uid]')){
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
	}
		
	if (isset($_POST['step']) && $_POST['step'] == 'import'){
		// Create a new publication object
		$pub = new objPublication($XPress);
		$pub->create($_POST['ID']);
		$pub->setTitle($_POST['Title'],false);
		$pub->setAuthor($_POST['Author'],false);
		$pub->setJournal($_POST['Journal'],false);
		$pub->setVolume($_POST['Volume'],false);
		$pub->setIssue($_POST['Issue'],false);
		$pub->setYear($_POST['Year'],false);
		$pub->save();
		
		cwsp_page::httpRedirect("./importpubmed.php?step=done");
	}
	
	$importForm = <<<ENDIMPORTFORM
		<div style="padding-left:9px;">
		Please enter the PubMed ID number of the publication you wish to import:<br/><br/>
		<form action="importpubmed.php" method="GET">
			<input type="hidden" name="step" value="showinfo"/>
			PubMed ID: 
			<input type="text" name="pmid"/>
			<input type="submit" value="Retrieve Info"/>
		</form>
		</div>
ENDIMPORTFORM;

	$displayForm = <<<ENDDISPLAYFORM
		<div style="padding-left:9px;">
		<form action="importpubmed.php" method="POST">
		<input type="hidden" name="step" value="import"/>
		<input type="hidden" name="ID" value="{$pubmed['ID']}"/>
		<input type="hidden" name="Title" value="{$pubmed['Title']}"/>
		<input type="hidden" name="Author" value="{$pubmed['Author']}"/>
		<input type="hidden" name="Journal" value="{$pubmed['Journal']}"/>
		<input type="hidden" name="Volume" value="{$pubmed['Volume']}"/>
		<input type="hidden" name="Issue" value="{$pubmed['Issue']}"/>
		<input type="hidden" name="Year" value="{$pubmed['Year']}"/>

		The following information was retrieved from PubMed:<br/><br/>
		<table>
	  		<tbody>
		    	<tr><th>PubMed ID:</th><td>{$pubmed['ID']}</td></tr>
		    	<tr><th>Title:</th><td>{$pubmed['Title']}</td></tr>
		    	<tr><th>Author:</th><td>{$pubmed['Author']}</td></tr>
		    	<tr><th>Journal:</th><td>{$pubmed['Journal']}</td></tr>
		    	<tr><th>Volume:</th><td>{$pubmed['Volume']}</td></tr>
		    	<tr><th>Issue:</th><td>{$pubmed['Issue']}</td></tr>
		    	<tr><th>Year:</th><td>{$pubmed['Year']}</td></tr>
				<tr><td><input type="submit" value="Import Publication"/></td>
					<td><a href="importpubmed.php"/>Import other publication</a></td></tr>
	  		</tbody>
		</form>
		</table>
		</div>
ENDDISPLAYFORM;

	$successForm = <<<ENDSUCCESSFORM
		<div style="padding-left:9px;">
			<div style="background-color:lightgreen;border:solid 1px green;text-align:center;padding:8px;">
				<h3>Publication imported successfully</h3>
			</div>
			<a href="./importpubmed.php">Import another publication</a>
		</div>
ENDSUCCESSFORM;

	// Page Header Setup
	$p = new cwsp_page("EDRN - Biomarker Database v0.4 Beta");
	$p->includeJS('../js/scriptaculous-js-1.7.0/lib/prototype.js');
	$p->includeJS('../js/scriptaculous-js-1.7.0/src/scriptaculous.js');
	$p->includeJS('../js/textInputs.js');
	$p->includeJS('../model/AjaxHandler.js');
	$p->includeCSS('../css/whiteflour.css');
	$p->includeCSS('../css/cwspTI.css');
	$p->drawHeader();
	
	require_once("../assets/skins/edrn/prologue.php");
?>
<div class="main">
	<div class="mainContent">
	<h2 class="title">Import Publication</h2>
<?php
	if (!isset($_GET['step'])){
		echo $importForm;
	} else {
		switch ($_GET['step']){
			case "showinfo":
					echo $displayForm;
				break;
			case "done":
					echo $successForm;
			default:
				break;
		}
	}
?>
		<div class="actions">
		<ul>
			  <li><a href="../index.php">Return Home</a></li>
		</ul>
	</div>
	</div>
</div>

<?php
	require_once("../assets/skins/edrn/epilogue.php");
	$p->drawFooter();
?>