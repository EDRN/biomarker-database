<div class="menu">
	<!-- Breadcrumbs Area -->
	<div id="breadcrumbs"/>
		<ul><li>Home</li></ul>
	</div><!-- End Breadcrumbs -->
</div>
<div style="position:relative;min-height:300px;">
	<div style="background-color:#fff;position:absolute;left:5px;height:100%;width:32%;">
		<div style="padding-left:5px;padding-right:5px;">
			<h2 style="margin-top:15px;color:#993231;border-bottom:dotted 1px #456;padding-bottom:2px;padding-top:5px;">Welcome!</h2>
			<p>The EDRN Biomarker Database is a repository for the collection and annotation of the
			full spectrum of cancer biomarker research data.</p>
			<p>The information stored in the database is first curated, and then peer-reviewed. When
			the quality of the data has been assured, it will be made available to the public through
			the EDRN Public Portal.</p>
			<p>Due to the fact that the data has not yet been publicly released, users must log-in in 
			order to browse and edit the database.</p> 
		</div>
	</div>
	<div style="background-color:#fff;position:absolute;left:33%;height:100%;margin-top:10px;border-left:solid 1px #999;border-right:solid 1px #999;width:32%;">
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
		</div>
		<div class="clr"><!--  --></div>
	</div>
	<div style="background-color:#fff;position:absolute;left:66%;height:100%;width:31%;margin-top:10px;">
		<h4>Latest Changes...</h4>
		<ul style="list-style-type:square;color:#500003;">
			<li><a href="/<?php echo PROJROOT;?>/auditors/weeklySummary">All activity this week</a></li>
			<li><span style="color:#aaa;">Activity for a particular biomarker</span></li>
			<li><span style="color:#aaa;">Activity for a particular user</span></li>
		</ul>
		<h4>Security...</h4>
		<ul style="list-style-type:square;color:#500003;">
			<li><a href="/<?php echo PROJROOT;?>/acls/manage">Bulk manage data security settings</a></li>
		</ul>
	</div>
</div>