<div class="menu">
	<!-- Breadcrumbs Area -->
	<div id="breadcrumbs"/>
		<ul><li>Home</li></ul>
	</div><!-- End Breadcrumbs -->
</div>
<div style="position:relative;min-height:335px;">
	<div style="background-color:#fff;position:absolute;left:0px;height:100%;width:40%;">
		<div style="padding-left:5px;padding-right:5px;">
			<h2 style="margin-top:15px;color:#993231;border-bottom:dotted 1px #456;padding-bottom:2px;padding-top:5px;">Welcome!</h2>
			<div style="border:solid 2px #cde;padding:5px;line-height:18px;">
			<img src="/<?php echo PROJROOT?>/img/question-mark.png" style="float:left;padding-right:5px;padding-top:4px"/>
			<div style="width:85%;float:left;">
			<p style="margin-top:0px;margin-bottom:0px;line-height:25px;font-size:90%;float:left;">
				If you are interested in accessing information about biomarkers under investigation within the
				EDRN, please 
				<a href="http://cancer.gov/edrn" style="text-decoration:underline;">Visit the EDRN Public Portal</a>
			</p> 
			</div>
			<br class="clr"/>
			</div>
			<p>The EDRN Biomarker Database is a repository for the collection and annotation of the
			full spectrum of EDRN cancer biomarker research data.</p>
			<p>The information in the database is curated and peer-reviewed for quality control. When
			the quality has been assured, the data will be available to the public through
			the 
			<a href="http://cancer.gov/edrn">EDRN Public Portal</a>.</p>
			
			<p>For specific questions
				about this website, please email the webmaster at 
				<a href="mailto:hkincaid@jpl.nasa.gov">hkincaid@jpl.nasa.gov</a>	
			</p>
			
		</div>
	</div>
	<div style="background-color:#fff;position:absolute;left:41%;height:100%;margin-top:10px;border-left:solid 1px #999;border-right:solid 1px #999;width:29%;">
		<div style="padding-left:10px;padding-right:10px;">
			<h4>Biomarkers</h4>
			<ul style="list-style-type:square;color:#500003;">
			  <li><a href="/<?php echo PROJROOT;?>/biomarkers/">Browse Biomarkers</a></li>
			  <!--<li><a href="#">Browse Biomarker Panels</a></li>-->
		
			  <li><a href="/<?php echo PROJROOT;?>/biomarkers/create">Create a New Biomarker <!--/ Panel--></a></li>
			</ul>
			<h4>Studies</h4>
			<ul style="list-style-type:square;color:#500003;">
			  <li><a href="/<?php echo PROJROOT;?>/studies/">Browse Studies</a></li>
		
			  <li><a href="/<?php echo PROJROOT;?>/studies/create">Create non-EDRN Study</a></li>
			</ul>
			<h4>Publications</h4>
			<ul style="list-style-type:square;color:#500003;">
			  <li><a href="/<?php echo PROJROOT;?>/publications/">Browse Publications</a></li>
			  <li><a href="/<?php echo PROJROOT;?>/publications/import">Import from Pub-Med</a></li>
			</ul>
			<h4>Terms / Glossary</h4>
			<ul style="list-style-type:square;color:#500003;">
			  <li><a href="/<?php echo PROJROOT;?>/terms/">Browse Terms</a></li>
			  <li><a href="/<?php echo PROJROOT;?>/terms/define">Define a Term</a></li>
			</ul>
		</div>
		<div class="clr"><!--  --></div>
	</div>
	<div style="background-color:#fff;position:absolute;left:71%;height:100%;width:29%;margin-top:10px;">
		<h4>Latest Changes...</h4>
		<ul style="list-style-type:square;color:#500003;">
			<li><a href="/<?php echo PROJROOT;?>/auditors/weeklySummary">All activity this week</a></li>
			<li><a href="/<?php echo PROJROOT;?>/auditors/previousMonth">All activity this month</a></li>
			<li><span style="color:#aaa;">Activity for a particular biomarker</span></li>
			<li><span style="color:#aaa;">Activity for a particular user</span></li>
		</ul>
		<h4>Security...</h4>
		<ul style="list-style-type:square;color:#500003;">
			<li><a href="/<?php echo PROJROOT;?>/acls/manage">Bulk manage data security settings</a></li>
		</ul>
	</div>
</div>