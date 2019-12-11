<?php
class PublicationsController extends AppController {
	var $name    = "Publications";
	var $helpers = array('Html','Js');
	var $uses    = array('Publication');
	
	public function index() {
		$this->checkSession("/publications");
		$this->set('publications', $this->Publication->find('all'));
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
		$data =& $this->request->data;
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
	
	function redirection() {
		$data = &$this->request->data;
		if ($data['id']) {
			$this->redirect("/publications/view/{$data['id']}");
		} else {
			$this->redirect("/publications");
		}
	}
	
	function ajax_retrieveInfo() {
		/**
		 * EXISTS?
		 * If the publication has previously been imported, simply display an error
		 * instead of re-fetching the information.
		 */
		$data =& $this->request->data;
		if (!isset($data['pmid']) || empty($data['pmid'])) {
			echo "<div class=\"error\">Please provide a PubMed ID first!<br/>"
				."</div>";
			exit();
		}
		$result = $this->Publication->find('first',array(
				'conditions' => array('Publication.pubmed_id'=>$data['pmid'])));
		if ($result) {
			echo "<div class=\"error\"><strong>The Selected Publication Has Already Been Imported:</strong><br/>"
				."\"{$result['Publication']['title']}\", "
				."{$result['Publication']['author']}, "
				."{$result['Publication']['journal']} <br/>"
				."Link: <a href=\"/publications/view/{$result['Publication']['id']}\">/publications/view/{$result['Publication']['id']}</a> "
				."</div>";
			exit();
		}

		
		/**
		 * PUBMED DATA RETRIEVAL
		 */
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
			
			$actionurl = "/publications/import_pubmed/{$pubmed['ID']}";
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
			
			/**
			 * VALID DATA RETURNED?
			 * If the 'title' field is empty, we can assume that no publication matched
			 * the provided id. In this case, return an error to the user indicating no match.
			 */
			if (empty($pubmed['Title'])) {
				echo "<div class=\"error\">No results for id: {$data['pmid']}. Please check your input.<br/>"
				."</div>";
			exit();
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