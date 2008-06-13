<?php
/**
 * 	EDRN Biomarker Database
 *  Curation Webapp
 * 
 *  Author: Andrew F. Hart (andrew.f.hart@jpl.nasa.gov)
 *  
 *  Copyright (c) 2008, California Institute of Technology. 
 *  ALL RIGHTS RESERVED. U.S. Government sponsorship acknowledged.
 * 
 */
	require_once("../base/fpdf.php");
	require_once("../base/PDF.class.php");
	
	require_once("../../xpress/app.php");
	
	// Process GET Parameters
	if (isset($_GET['id']) && $_GET['id'] > 0) {
		$objectID = $_GET['id'];
	}
	if (!$objectID) {
		die("Could not serve PDF. Missing 'id' in GET parameters.");
	}
	
	// Retrieve the requested object
	$o = BiomarkerFactory::Retrieve($objectID);
	
	//Create the document
	$pdf = new PDF();
	
	// Set up the page
	$pdf->addPage();
	$pdf->SetLeftMargin(15);
	$pdf->SetFont('Arial','',14);
	
	// Add Content
	$pdf->SetFont('Arial','',12);
	$pdf->Write(4,$o->getTitle());
	$pdf->Ln();
	$pdf->Write(4,$o->getBiomarkerID());
	$pdf->Ln(15);
	
	$pdf->SetFont('Arial','B','18');
	$pdf->Write(5,$o->getTitle());
	//$pdf->SetFont('Arial','I',17);
	$pdf->Ln(12);
	$pdf->SetFont('Arial','IU','14');
	$pdf->Write(5,'Description:');
	$pdf->Ln(8);
	$pdf->SetLeftMargin(18);
	$pdf->SetFont('Arial','',14);
	$pdf->Write(5,$o->getDescription());
	
	$pdf->SetLeftMargin(15);
	$pdf->Ln(15);
	$pdf->SetFont('Arial','IU','14');
	$pdf->Write(5,'Attributes:');
	$pdf->Ln(8);
	$pdf->SetLeftMargin(18);
	$widths = array(40,135);
	$header = array("Attribute","Value");
	$data   = array(
				array("Short Name:",$o->getShortName()),
				array("Identifier:",$o->getBiomarkerID()),
				array("Security:",$o->getSecurity()),
				array("QA State:",$o->getQAState()),
				array("Type:",$o->getType()));
	
	$pdf->FancyTable($header,$data,$widths);
	$pdf->Ln(15);
	$pdf->SetFontSize(12);
	$pdf->SetDrawColor(0,0,0);
	$pdf->SetTextColor(128,128,128);
	$pdf->MultiCell(175,8,"This document was auto-generated on ".date("Y-m-d \a\\t h:i:s",time()),1,'C');
	
	// Output the page
	$pdf->Output();
?>