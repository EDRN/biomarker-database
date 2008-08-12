<div class="menu">
	<div class="mainContent">
		<h2 class="title">EDRN Biomarker Database</h2>
	</div>
	<!-- Breadcrumbs Area -->
	<div id="breadcrumbs"/>

		Home
		<div class="userdetails">
			<?php if (isset($_SESSION['username'])) {
				echo "Logged in as: {$_SESSION['username']}. &nbsp;";
				echo "<a href=\"/".PROJROOT."/users/logout\">Log Out</a>";
			} else {
				echo "Not Logged In. &nbsp; ";
				echo "<a href=\"/".PROJROOT."/users/login\">Log In</a>";
			}?>
		</div>
	</div><!-- End Breadcrumbs -->
</div>
<div style="background-color:#fff;padding-left:66%;position:relative;min-height:300px;border-bottom:solid 2px #666;">
<div style="background-color:#fff;position:absolute;left:0;height:100%;width:33%;">
	<div style="padding:5px;">
	<h4>Biomarkers</h4></div>
	<ul style="list-style-type:square;color:#500003;">
	  <li><a href="/<?php echo PROJROOT;?>/biomarkers/">Browse Biomarkers</a></li>
	  <!--<li><a href="#">Browse Biomarker Panels</a></li>-->

	  <li><a href="/<?php echo PROJROOT;?>/biomarkers/create">Create a New Biomarker <!--/ Panel--></a></li>
	</ul>
	</div>
<div style="background-color:#fff;position:absolute;left:33%;height:100%;border-left:solid 1px #999;border-right:solid 1px #bbb;width:33%;">
	<div style="padding:5px;">
	<h4>Studies</h4></div>
	<ul style="list-style-type:square;color:#500003;">
	  <li><a href="/<?php echo PROJROOT;?>/studies/">Browse Studies</a></li>

	  <li><a href="/<?php echo PROJROOT;?>/studies/create">Create non-EDRN Study</a></li>
	</ul>
	</div>
<div style="float:left;border-left:solid 1px #aaa;">
	<div style="padding:5px;">
	<h4>Publications</h4></div>
	<ul style="list-style-type:square;color:#500003;">
	  <li><a href="/<?php echo PROJROOT;?>/publications/">Browse Publications</a></li>

	  <li><a href="/<?php echo PROJROOT;?>/publications/import">Import from Pub-Med</a></li>
	</ul>
	</div>
<div class="clr"><!--  --></div>
</div>