<?php

class PloneHeader {
	public static function generate($pathPrefix = ''){
		return ('
		<!-- Begin Plone junk -->

                <style type="text/css"><!-- @import url('.$pathPrefix.'plone/ploneStyles6030.css); --></style>
                <link rel="alternate stylesheet"
                          type="text/css" media="screen"
                          href="'.$pathPrefix.'plone/ploneStyles3082.css"
                          title="Small Text" />
                <link rel="alternate stylesheet"
                          type="text/css" media="screen"
                          href="'.$pathPrefix.'plone/ploneStyles6030.css"
                          title="Large Text" />
                   
                <style type="text/css"><!-- @import url('.$pathPrefix.'plone/ploneStyles3081.css); --></style>
                <style type="text/css" media="all"><!-- @import url('.$pathPrefix.'plone/ploneStyles2825.css); --></style>
                    
    	<!-- Internet Explorer CSS Fixes -->
    	<!--[if IE]>
    	    <style type="text/css" media="all">@import url('.$pathPrefix.'plone/IEFixes.css);</style>
    	<![endif]-->

		<!-- End Plone junk -->');
	}
}

?>