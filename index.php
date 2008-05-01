<?php
/**
 * EDRN Biomarker Database
 * Curation Webapp
 * 
 * Author: Andrew F. Hart (andrew.f.hart@jpl.nasa.gov)
 * See AUTHORS file for list of members of the JPL EDRN Project Team
 * 
 * Copyright 2008, by the California Institute of Technology. ALL RIGHTS RESERVED. 
 * United States Government Sponsorship acknowledged. Any commercial use must be 
 * negotiated with the Office of Technology Transfer at the California Institute 
 * of Technology.
 * 
 * This software may be subject to U.S. export control laws and regulations.  
 * By accepting this document, the user agrees to comply with all applicable 
 * U.S. export laws and regulations.  User has the responsibility to obtain 
 * export licenses, or other export authority as may be required before 
 * exporting such information to foreign countries or providing access to 
 * foreign persons.
 * 
******************************************************************************/

	require_once("xpress/app.php");
	
	// Page Header Setup
	$p = new XPressPage(App::NAME." ".App::VERSION,"text/html","UTF-8");
	$p->includeCSS('static/css/frozen.css');
	$p->open();
	$p->view()->LoadTemplate("view/homepage.html");
	$p->view()->Show();
	$p->close();
?>