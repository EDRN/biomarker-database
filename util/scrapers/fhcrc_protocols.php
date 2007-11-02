<?php
	require_once("../definitions.inc.php");
	require_once("cwsp.inc.php");
	require_once("utilities/scrape/components.php");
	
	// Initial URL base
	$targetBase = 
		"http://www.compass.fhcrc.org/edrnnci/bin/protocol/protocol.asp?t=detail&pid=";
	
	// Grab the operational parameters
	$start = $_GET['start'];
	$end   = $_GET['end'];
	
	// Print the HTML opener
	$p = new cwsp_page("FHCRC - Protocols");
	$css = <<< ENDCSS
		<style type="text/css">
			.data {
				font-size:.8em;
				background-color:#eee;
				border:dotted 3px #ddd;
				padding:3px;
			}
			.rdf  {
				font-size:0.8em;
				background-color:#eef;
				border:dotted 3px #dde;
				padding:3px;
			}
			h4 {
				font-size:1.1em;
				font-weight:bold;
				font-family:verdana,sans-serif;
				color: #999;
				border-top:solid 1px #ddd;
				margin-top:10px;
				margin-bottom:0px;
			}
			h5 {
				font-size:0.9em;
				font-weight:bold;
				font-family:verdana,sans-serif;
				color:#bbb;
				border-top:solid 1px #ddd;
				margin-top:5px;
				margin-bottom:5px;
			}
		</style>	
ENDCSS;
	$p->insertRaw($css);
	$p->drawHeader();
	
	// Initiate Processing from $start to $end
	for ( $i = $start; $i <= $end; $i++ ) {
		echo "Fetching: {$targetBase}{$i}<br/>";
		// Fetch the target and save the result in a string
		ob_start();
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $targetBase.$i);
		$data = curl_exec($ch);
		$string = ob_get_contents();
		ob_end_clean();
		
		// Extract the TABLE data
		$protocolID = $i;	
		$dom = new DOMDocument();
		@$dom->loadHTML($string);
		$tables = $dom->getElementsByTagName("table");
	
		// Protocol Description table (index: 3)
		$keys = array(
			new key("Protocol ID"),
			new key("Protocol Name"),
			new key("Study Design"),
			new key("Abstract"));
		$kc = new keyCollection($keys);
		$t1 = new tall_table($tables->item(3),$kc,true);
		
		echo "<h5>Status:</h5>";
		$t1->buildMap();
		
		// Is the data good?
		// protocol name is the only piece of information required
		// to create a bmdb Study object, so check to make sure that
		// we got some data
		if ($t1->map['Protocol Name'] == ''){echo "No Data. Skipping.<br/>"; continue;}
		
		// Save the data
		require_once("/Applications/MAMP/htdocs/edrn_bmdb3/model/study.php");
		$s = study::Create($t1->map['Protocol Name']);
		$s->set_FHCRC_ID($protocolID);
		$s->set_DMCC_ID($t1->map['Protocol ID']);
		$s->set_Abstract($t1->map['Abstract']);
		$s->set_Design($t1->map['Study Design']);
		$isEDRN = ($t1->map['Protocol ID'] == $protocolID)? "REGISTERED" : "UNREGISTERED";
		$s->set_BiomarkerStudyType($isEDRN);
		study::Save(&$s);
		echo "SAVED <br/>";
		echo "<br/><h4>RDF Dump:</h4><div class=\"rdf\">"
			. nl2br(htmlspecialchars($s->toRDF("bmdb","http://tension.jpl.nasa.gov/edrn_bmdb3")))
			. "</div><br/>";
		echo '<h4>Associative Array:</h4><div class="data">';
		$vo_study = $s->getVO();
		print_r( $vo_study->toAssocArray() );
		echo '</div><br/>';
		
		echo '<hr/>';
		
	}
	
	// Close off the page
	$p->drawFooter();
?>