<?php
class ActionsController extends AppController {
	
	var $name    = "Actions";
	var $helpers = array('Html','Ajax','Javascript','Pagination');
	var $layout  = "actions";
	
	var $uses = 
		array(
			'Action',
			'Acl',
			'Biomarker',
			'BiomarkerName',
			'Auditor',
			'LdapUser',
			'Organ',
			'OrganData',
			'Study',
			'StudyData',
			'BiomarkerStudyData',
			'Publication',
			'StudyDataResource',
			'BiomarkerStudyDataResource',
			'OrganDataResource',
			'BiomarkerResource',
			'Sensitivity'
		);
			
	private function highlight($needle,$haystack) {
		return str_replace($needle,"<span class=\"highlighted\">{$needle}</span>",$haystack);
	}
	
	// Manages relationships between "Publication" objects and other objects
	function publication($id,$relationship,$foreignObjectType,$foreignObjectId) {
		// Ensure that a user is properly logged in
		$this->checkSession("/");
		// Determine action to take based on the relationship provided
		switch (strtolower($relationship)) {
			// Add a relationship between the publication and the specified foreign object type
			case "relatedto":
				// Determine action to take based on the foreign object type provided
				switch (strtolower($foreignObjectType)) {
					case "biomarker": 
						$this->Biomarker->habtmAdd('Publication',$foreignObjectId,$id);
						break;
					case "biomarkerorgan":
						$this->OrganData->habtmAdd('Publication',$foreignObjectId,$id);
						break;
					default:
						die("No relationship defined between Publication 
							 and {$foreignObjectType} objects");
				}
				echo "Publication successfully added. 
							<a style=\"color:blue;\"
							   href=\"#\" 
							   onclick=\"parent.location.reload();\">
							   Click Here to Continue</a>";
				exit();
			default:
				die("Undefined object relationship: `{$relationship}` between 
					 publication {$id} and {$foreignObjectType} {$foreignObjectId}");
		}
	}

	// Displays the "Publication Search" page, allowing the user to select a publication
	function addPublication($foreignObjectType,$foreignObjectId) {
		$this->set('foreignObjectType',$foreignObjectType);
		$this->set('foreignObjectId',$foreignObjectId);
		
		
	}
	
	// Dynamic (AJAX) listing of publications matching a given search term (sent via POST)
	function list_publications($foreignObject,$foreignObjectId) {
		define("MAXRESULTS",10);
		$term = strip_tags(substr($_POST['search_term'],0, 100));
		$term = mysql_escape_string($term);
		
		$sql = "SELECT `ID`,`title`,`pubmed_id`
					FROM `Publications`
					WHERE `title`  like '%$term%'
					OR `pubmed_id` like '%$term%'
					ORDER BY title asc";
		
		$cakeResult = $this->Action->query($sql);
		$results    = array();
		foreach ($cakeResult as $cr) {
			$results[] = array( "id"       =>$cr['Publications']['ID'],
								"pubmed_id"=>$cr['Publications']['pubmed_id'],
								"title"    =>$cr['Publications']['title']);
		}
		
		$string = '';
		
		if (count($results) > MAXRESULTS) {
			$string .= "<div class=\"warn\">More than ".MAXRESULTS." matches. Please narrow your search.</div>";
		} else if (count($results) > 0) {
			  $string .= "<ul>";
			  foreach ($results as $row) {
			    $string .= "<li><b>".$this->highlight($term,$row['pubmed_id'])."</b> - ";
			    $string .= "<a href=\"/".PROJROOT."/actions/publication/{$row['id']}/relatedTo/{$foreignObject}/{$foreignObjectId}\">".$this->highlight($term,$row['title'])."</a>";
			    $string .= "</li>";
			  }
			  $string .= "</ul>";
		} else {
			  $string = "<div class=\"err\">No matches found. Try your search again, or "
						."<span class=\"display_import fakelink\" onclick=\"display_import();}\">"
			  			."Import a PubMed Publication</a></div>";
			  
		} 
		echo $string;
		exit();
	}
}
?>