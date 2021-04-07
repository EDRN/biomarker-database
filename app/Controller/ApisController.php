<?php
class ApisController extends AppController {
	
	var $name    = "Apis";
	var $helpers = array('Html','Js','Pagination');
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
			'Site',
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
      $this->checkSession('/apis/biomarkers');

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
		$aColumns = array('primary_name', 'qastate', 'type', 'isPanel', 'organs');

		$searchWhere = "";
		if ( $_GET['sSearch'] != "" ) {
			$searchWhere = " WHERE (";
			// Check all the columns for similarity to the entered search.
			for ( $i=0 ; $i<count($aColumns) ; $i++ ) {
				$searchWhere .= $aColumns[$i]." LIKE '%".mysql_escape_mimic( $_GET['sSearch'] )."%' OR ";
			}

			// Also, search over biomarker IDs and aliases.
			$searchWhere .= "id LIKE '%".mysql_escape_mimic($_GET['sSearch'])."%'";
			$searchWhere .= " OR names LIKE '%".mysql_escape_mimic($_GET['sSearch'])."%'";
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
				$searchWhere .= $aColumns[$i]." LIKE '%".mysql_escape_mimic($_GET['sSearch_'.$i])."%' ";
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
					$searchOrder .= $aColumns[intval($_GET['iSortCol_'.$i])]." ".mysql_escape_mimic( $_GET['sSortDir_'.$i] ) .", ";
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
			$searchLimit = " LIMIT ".mysql_escape_mimic( $_GET['iDisplayStart'] ).", ".
				mysql_escape_mimic( $_GET['iDisplayLength'] );
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
				"<a href=\"/biomarkers/view/{$b['search_view']['id']}\">".$b['search_view']['primary_name'].'</a>',
				$b['search_view']['qastate'],
				$b['search_view']['type'],
				($b['search_view']['isPanel'] == 1) ? "Yes" : "No",
				$b['search_view']['organs'],
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

	function sites_search() {
		$json = array();

		// List of column names from the database that will be displayed in the table in the SAME order. These will be 
		// used for searching and ordering when forming the query string.
		$aColumns = array('site_id', 'name');

		$searchWhere = "";
		if ( $_GET['sSearch'] != "" ) {
			//$searchWhere = " WHERE (";
			$searchWhere = " WHERE ";
			// Check all the columns for similarity to the entered search.
			for ( $i=0 ; $i<count($aColumns) ; $i++ ) {
				$searchWhere .= $aColumns[$i]." LIKE '%".mysql_escape_mimic( $_GET['sSearch'] )."%' OR ";
			}
			$searchWhere = substr_replace($searchWhere, "", -3);
		} 

		// Individual column filtering 
		for ( $i=0 ; $i<count($aColumns) ; $i++ ) {
			if ( $_GET['bSearchable_'.$i] == "true" && $_GET['sSearch_'.$i] != '' ) {
				if ( $searchWhere == "" ) {
					$searchWhere = "WHERE ";
				} else {
					$searchWhere .= " AND ";
				}
				$searchWhere .= $aColumns[$i]." LIKE '%".mysql_escape_mimic($_GET['sSearch_'.$i])."%' ";
			}
		}

		$searchOrder = "";
		//* Ordering by column
		if (isset($_GET['iSortCol_0'])) {
			$searchOrder = " ORDER BY  ";

			// Order the results by a specific column and all the columns to its left. This ensures that the
			// sorting of the results is logical when there are identical values in (a) certain column(s).
			for ($i=0 ; $i<intval( $_GET['iSortingCols'] ) ; $i++) {
				if ($_GET[ 'bSortable_'.intval($_GET['iSortCol_'.$i])] == "true" ) {
					$searchOrder .= $aColumns[intval($_GET['iSortCol_'.$i])]." ".mysql_escape_mimic( $_GET['sSortDir_'.$i] ) .", ";
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
		//* Limit the number of returned results to the requested result "window".
		if (isset( $_GET['iDisplayStart'] ) && $_GET['iDisplayLength'] != '-1') {
			$searchLimit = " LIMIT ".mysql_escape_mimic( $_GET['iDisplayStart'] ).", ".
				mysql_escape_mimic( $_GET['iDisplayLength'] );
		}

		$sites = $this->Site->runSiteSearch($searchWhere, $searchLimit, $searchOrder);
		// Get the total number of results that would have been returned if we hadn't filtered.
		$filteredTotal = $this->Site->getFilteredTotal();

		$numBiomarkers = count($sites);

		$organConcat = "";
		$organStateConcat = "";
		foreach ($sites as $site) {

			// Save the current row
			$json[] = array(
				"<a href=\"/sites/view/{$site['sites']['id']}\">".$site['sites']['site_id']."</a>",
				"<a href=\"/sites/view/{$site['sites']['id']}\">".$site['sites']['name']."</a>",
			);
		}

		// Get the total number of biomarkers
		$totalSitesQuery = $this->Site->getSiteCount();

		// Send the output in the expected format!!
		$output = array(
			"sEcho" => intval($_GET['sEcho']),
			// iTotalRecords should be the total number of possible values that can be
			// displayed in the table. In this case, it's the number of biomarkers.
			"iTotalRecords" => $totalSitesQuery[0][0]['numSite'],
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
