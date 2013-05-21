<?php
class ApisController extends AppController {
	
	var $name    = "Apis";
	var $helpers = array('Html','Ajax','Javascript','Pagination');
	var $components = array('Pagination');
	var $uses = 
		array(
			'Biomarker',
			'BiomarkerName',
			'BiomarkerDataset',
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
			'Sensitivity',
			'Term'
		);

	/******************************************************************
	 * BROWSE (INDEX)
	 ******************************************************************/
	function index() {
	}
	
	/******************************************************************
	 * APIs
	 ******************************************************************/
	
	function biomarkers() {

	  $csv = array();

	  // Retrieve information about all of the biomarkers in the system,
	  // recursing only 1 level deep (don't need lots of study, etc data) here
	  $biomarkers = $this->Biomarker->find('all',array(
							   'conditions' => array(),
							   'recursive'  => 1
							   ));

	  // Populate each biomarker with the names of its associated organs and its default name
	  // and build the CSV data structure to return
	  for ($i=0;$i<count($biomarkers);$i++) {
	    $biomarkers[$i]['OrganDatas'] = $this->Biomarker->getOrganDatasFor($biomarkers[$i]['Biomarker']['id']);
	    $biomarkers[$i]['DefaultName']= $this->Biomarker->getDefaultName($biomarkers[$i]);
	    $biomarkers[$i]['HgncName']   = $this->Biomarker->getHgncName($biomarkers[$i]);
	    $biomarkers[$i]['AlternativeNames'] = implode('$$',$this->Biomarker->getAlternativeNames($biomarkers[$i]));

	    foreach ($biomarkers[$i]['OrganDatas'] as $od) {
	      $od_full = $this->OrganData->find('first',array('conditions'=>array('OrganData.id'=>$od['OrganData']['id'])));
	      $csv[]   = array(
	          "Id"               => $biomarkers[$i]['Biomarker']['id']
	          , "Biomarker Name" => $biomarkers[$i]['DefaultName']
			      , "HgncName"       => $biomarkers[$i]['HgncName']
			      , "AlternativeNames" => $biomarkers[$i]['AlternativeNames'] 
			      , "Organ"          => $od['Organ']['name']
			      , "Type"           => $biomarkers[$i]['Biomarker']['type']
			      , "QA State"       => $od_full['OrganData']['qastate']
			      , "Phase"          => $od_full['OrganData']['phase']
			      );
	    }
	  }

	  // Convert to CSV
	  if (count($csv) == 0) { return; } // No Data

	  ob_start();
	  $columns = array_keys($csv[0]);
          echo implode(',',$columns) . "\r\n";
          foreach ($csv as $row) {
            echo "\"" .  implode('","',$row) . "\"" . "\r\n";
          }

	  // Header settings
	  header('Content-Description: File Transfer');
	  header('Content-Type: text/csv');
	  header('Content-Disposition: attachment; filename=EDRN_BMDB_Biomarkers.csv');
	  header('Expires: 0');
	  header('Cache-Control: must-revalidate');
	  header('Pragma: public');
	  ob_end_flush();

	  // Done
	  exit();
	}

	function biomarkers_search() {
		$json = array();

		// List of column names from the database that will be displayed in the table in the SAME order. These will be 
		// used for searching and ordering when forming the query string.
		$aColumns = array('defaultname', 'qastate', 'type', 'panel', 'organs');

		$searchWhere = "";
		if ( $_GET['sSearch'] != "" ) {
			$searchWhere = " WHERE (";
			// Check all the columns for similarity to the entered search.
			for ( $i=0 ; $i<count($aColumns) ; $i++ ) {
				$searchWhere .= $aColumns[$i]." LIKE '%".mysql_real_escape_string( $_GET['sSearch'] )."%' OR ";
			}

			// Also, search over biomarker IDs and aliases.
			$searchWhere .= "id LIKE '%".mysql_real_escape_string($_GET['sSearch'])."%'";
			$searchWhere .= " OR aliases LIKE '%".mysql_real_escape_string($_GET['sSearch'])."%'";
			$searchWhere .= ')';
		} 

		/* Individual column filtering */
		for ( $i=0 ; $i<count($aColumns) ; $i++ ) {
			if ( $_GET['bSearchable_'.$i] == "true" && $_GET['sSearch_'.$i] != '' ) {
				if ( $searchWhere == "" ) {
					$searchWhere = "WHERE ";
				} else {
					$searchWhere .= " AND ";
				}
				$searchWhere .= $aColumns[$i]." LIKE '%".mysql_real_escape_string($_GET['sSearch_'.$i])."%' ";
			}
		}

		$searchOrder = "";
		/* Ordering by column */
		if (isset($_GET['iSortCol_0'])) {
			$searchOrder = " ORDER BY  ";

			// Order the results by a specific column and all the columns to its left. This ensures that the
			// sorting of the results is logical when there are identical values in (a) certain column(s).
			for ($i=0 ; $i<intval( $_GET['iSortingCols'] ) ; $i++) {
				if ($_GET[ 'bSortable_'.intval($_GET['iSortCol_'.$i])] == "true" ) {
					$searchOrder .= $aColumns[intval($_GET['iSortCol_'.$i])]." ".mysql_real_escape_string( $_GET['sSortDir_'.$i] ) .", ";
				}
			}

			// Strip out the extra ", " at the end of the string
			$searchOrder = substr_replace($searchOrder, "", -2);
			// Make sure we aren't trying to pass a blank "ORDER BY"
			if ($searchOrder == "ORDER BY") {
				$searchOrder = "";
			}
		}

		$searchLimit = "";
		/* Limit the number of returned results to the requested result "window". */
		if (isset( $_GET['iDisplayStart'] ) && $_GET['iDisplayLength'] != '-1') {
			$searchLimit = " LIMIT ".mysql_real_escape_string( $_GET['iDisplayStart'] ).", ".
				mysql_real_escape_string( $_GET['iDisplayLength'] );
		}

		// Get the results!
		$biomarkers = $this->Biomarker->runBiomarkerSearch($searchWhere, $searchLimit, $searchOrder);
		// Get the total number of results that would have been returned if we hadn't filtered.
		$filteredTotal = $this->Biomarker->getFilteredTotal();

		$numBiomarkers = count($biomarkers);

		$organConcat = "";
		$organStateConcat = "";
		foreach ($biomarkers as $b) {

			// Save the current row
			$json[] = array(
				"<a href=\"/bmdb/biomarkers/view/{$b['biomarkers_search']['id']}\">".$b['biomarkers_search']['defaultname'].'</a>',
				$b['biomarkers_search']['qastate'],
				$b['biomarkers_search']['type'],
				$b['biomarkers_search']['panel'],
				$b['biomarkers_search']['organs'],
			);
		}

		// Get the total number of biomarkers
		$totalBiomarkersQuery = $this->Biomarker->getBiomarkerCount();

		// Send the output in the expected format!!
		$output = array(
			"sEcho" => intval($_GET['sEcho']),
			// iTotalRecords should be the total number of possible values that can be
			// displayed in the table. In this case, it's the number of biomarkers.
			"iTotalRecords" => $totalBiomarkersQuery[0][0]['numBiomarker'],
			// iTotalDisplayRecords is the number of values that COULD be shown if they
			// weren't filtered out. This only really changes when the user searches
			// for something. Otherwise == numBiomarkers.
			"iTotalDisplayRecords" => $filteredTotal[0][0]['filteredCount'],
			"aaData" => array()
		);

		// Add the row values if we actually have something to show
		if (count($json) != 0) {
			$output['aaData'] = $json;
		}

		echo json_encode($output);
		exit();
	}

	function biomarkers_json() {
		$json = array();

		// List of column names from the database that will be displayed in the table in the SAME order. These will be 
		// used for searching and ordering when forming the query string.
		$aColumns = array('biomarker_names.name', 'biomarkers.qastate', 'biomarkers.type', 'biomarkers.isPanel', 'organs.name');

		$searchWhere = "";
		/* Searching */
		if ( $_GET['sSearch'] != "" ) {
			$searchWhere = " WHERE (";
			// Check all the columns for similarity to the entered search.
			for ( $i=0 ; $i<count($aColumns) ; $i++ ) {
				$searchWhere .= $aColumns[$i]." LIKE '%".mysql_real_escape_string( $_GET['sSearch'] )."%' OR ";
			}

			// Also, search over biomarker IDs for convenience. 
			$searchWhere .= "biomarkers.id LIKE '%".mysql_real_escape_string($_GET['sSearch'])."%'";
			$searchWhere .= ')';
		} else {
			$searchWhere = " WHERE isPrimary=1 ";
		}
		// If aliasing searching is desired in the future this is necessary to speed up queries when the user isn't running
		// a search. Otherwise the overall performance tanks to ~12s load times per page of results. 
		//} else {
		//	$searchWhere = " WHERE isPrimary=1 ";
		//}

		/* Individual column filtering */
		for ( $i=0 ; $i<count($aColumns) ; $i++ ) {
			if ( $_GET['bSearchable_'.$i] == "true" && $_GET['sSearch_'.$i] != '' ) {
				if ( $searchWhere == "" ) {
					$searchWhere = "WHERE ";
				} else {
					$searchWhere .= " AND ";
				}
				$searchWhere .= $aColumns[$i]." LIKE '%".mysql_real_escape_string($_GET['sSearch_'.$i])."%' ";
			}
		}

		$searchOrder = "";
		/* Ordering by column */
		if (isset($_GET['iSortCol_0'])) {
			$searchOrder = "ORDER BY  ";

			// Order the results by a specific column and all the columns to its left. This ensures that the
			// sorting of the results is logical when there are identical values in (a) certain column(s).
			for ($i=0 ; $i<intval( $_GET['iSortingCols'] ) ; $i++) {
				if ($_GET[ 'bSortable_'.intval($_GET['iSortCol_'.$i])] == "true" ) {
					$searchOrder .= $aColumns[intval($_GET['iSortCol_'.$i])]." ".mysql_real_escape_string( $_GET['sSortDir_'.$i] ) .", ";
				}
			}

			// Strip out the extra ", " at the end of the string
			$searchOrder = substr_replace($searchOrder, "", -2);
			// Make sure we aren't trying to pass a blank "ORDER BY"
			if ($searchOrder == "ORDER BY") {
				$searchOrder = "";
			}
		}

		$searchLimit = "";
		/* Limit the number of returned results to the requested result "window". */
		if (isset( $_GET['iDisplayStart'] ) && $_GET['iDisplayLength'] != '-1') {
			$searchLimit = " LIMIT ".mysql_real_escape_string( $_GET['iDisplayStart'] ).", ".
				mysql_real_escape_string( $_GET['iDisplayLength'] );
		}

		// Get the results!
		$biomarkers = $this->Biomarker->runFilteringBiomarkerQuery($searchWhere, $searchLimit, $searchOrder);
		// Get the total number of results that would have been returned if we hadn't filtered.
		$filteredTotal = $this->Biomarker->getFilteredTotal();

		$numBiomarkers = count($biomarkers);
		for ($i=0; $i < $numBiomarkers; $i++) {
			// Get organ data for the current biomarker
			$biomarkers[$i]['OrganDatas'] = $this->Biomarker->getOrganDatasFor($biomarkers[$i]['biomarkers']['id']);

			// Get a list of all the alternative names for the current biomarker. This isn't strictly necessary, but
			// the way we handle retrieving the default and HGNC names dictates that we still grab these. Maybe we should
			// update those so that isn't the case!?!
			$tmp = $this->Biomarker->getBiomarkerNames($biomarkers[$i]['biomarkers']['id']);
			$results = array();
			foreach ($tmp as $row) {
				$withoutAssoc = $row['biomarker_names'];
				array_push($results, $withoutAssoc);
			}

			$biomarkers[$i]['BiomarkerName'] = $results;
			$biomarkers[$i]['DefaultName']= $this->Biomarker->getDefaultName($biomarkers[$i]);
			$biomarkers[$i]['HgncName']   = $this->Biomarker->getHgncName($biomarkers[$i]);

			$organConcat = "";
			$organStateConcat = "";
			foreach ($biomarkers[$i]['OrganDatas'] as $od) {
				// Get the full data listing for the current organ being processed
				$od_full = $this->OrganData->find('first',array('conditions'=>array('OrganData.id'=>$od['OrganData']['id'])));

				// Add the organ name for later display. Make sure we handle multiple organs by generating
				// a comma-seperated string.
				//
				// Generate the link for the current organ.
				$tmpOrganData = "<a href=\"/bmdb/biomarkers/organs/{$biomarkers[$i]['biomarkers']['id']}/";
				$tmpOrganData .= "{$od['OrganData']['id']}\">{$od['Organ']['name']}</a>";
				// Add the current organ name.
				$organConcat = ($organConcat == "" ? $tmpOrganData : $organConcat.", ".$tmpOrganData);

				// Add the current organ's QA State for later display. We handle multiple organs by displaying
				// the QA state per organ as opposed to globally for the biomarker.
				//
				// Sometimes the qastate will be left blank. This should be (will be?) rectified later, but for now we
				// need to handle that case.
				if ($organStateConcat != "Various") {
					if ($od_full['OrganData']['qastate'] != "") {
						$organStateConcat = ($organStateConcat == "" ? $od_full['OrganData']['qastate'] : "Various");
					}
				}
			}

			// Save the current row
			$json[] = array(
				"<a href=\"/bmdb/biomarkers/view/{$biomarkers[$i]['biomarkers']['id']}\">".$biomarkers[$i]['DefaultName'].'</a>',
				$organStateConcat,
				$biomarkers[$i]['biomarkers']['type'],
				($biomarkers[$i]['biomarkers']['isPanel'] == 0 ? "No" : "Yes"),
				$organConcat,
			);
		}

		// Get the total number of biomarkers
		$totalBiomarkersQuery = $this->Biomarker->getBiomarkerCount();

		// Send the output in the expected format!!
		$output = array(
			"sEcho" => intval($_GET['sEcho']),
			// iTotalRecords should be the total number of possible values that can be
			// displayed in the table. In this case, it's the number of biomarkers.
			"iTotalRecords" => $totalBiomarkersQuery[0][0]['numBiomarker'],
			// iTotalDisplayRecords is the number of values that COULD be shown if they
			// weren't filtered out. This only really changes when the user searches
			// for something. Otherwise == numBiomarkers.
			"iTotalDisplayRecords" => $filteredTotal[0][0]['filteredCount'],
			"aaData" => array()
		);

		// Add the row values if we actually have something to show
		if (count($json) != 0) {
			$output['aaData'] = $json;
		}

		echo json_encode($output);
		exit();
	}
}
