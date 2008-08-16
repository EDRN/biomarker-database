<?php
class PublicationsController extends AppController {
	
	var $helpers = array('Html','Ajax','Javascript');
	
	public function index() {
		$this->checkSession("/publications");
		$this->set('publications', $this->Publication->findAll());		
	}
	public function view($id=null) {
		$this->checkSession("/publications/view/{$id}");
		$this->Publication->id = $id;
		$this->set('publication', $this->Publication->read());
		
	}
	public function import() {
		$this->checkSession("/publications/import");
	}
	public function import_pubmed() {
		$this->checkSession("/publications/import");
		/**
		 * PUBMED DATA IMPORT
		 */
		$data =& $this->params['form'];
		$this->Publication->create(array(
			'title'=>$data['Title'],
			'journal'=>$data['Journal'],
			'author' =>$data['Author'],
			'published'=>$data['Year'],
			'pubmed_id'=>$data['ID']
		));
		$this->Publication->save();
		echo "<div class=\"success\">The Publication was successfully imported.</div>";
		exit();
	}
	
	function goto() {
		$data = &$this->params['form'];
		if ($data['id']) {
			$this->redirect("/publications/view/{$data['id']}");
		} else {
			$this->redirect("/publications");
		}
	}
	
	function ajax_retrieveInfo() {
		/**
		 * PUBMED DATA RETRIEVAL
		 */
		$data =& $this->params['form'];
		if (isset($data['pmid'])){
			$docs = '';
			
			if ($xml = simplexml_load_file('http://eutils.ncbi.nlm.nih.gov/entrez/eutils/esearch.fcgi?db=pubmed&retmax=0&usehistory=y&term=' . urlencode($data['pmid']) . '[uid]')){
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
			
			$actionurl = "/".PROJROOT."/publications/import_pubmed/{$pubmed['ID']}";
			if (isset($data['update'])) {
				$update_div = $data['update'];
			} else {
				$update_div = "pubmedresult";
			}
			if (isset($data['updateID'])) {
				$update_id = "{$data['updateID']}";			
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
		} else { echo "An Error Occurred. Please try again later.";exit();}
	}
}
?>