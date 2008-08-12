<?php echo $html->css('frozenbrowser');?>
<div class="menu">
	<div class="mainContent">
		<h2 class="title">EDRN Biomarker Database</h2>
	</div>
	<!-- Breadcrumbs Area -->
	<div id="breadcrumbs">
	<a href="/<?php echo PROJROOT;?>/">Home</a> /
	<a href="/<?php echo PROJROOT;?>/studies">Studies</a> /
	<a href="/<?php echo PROJROOT;?>/studies/create">Create Non-EDRN Study</a> / 
	Error
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

<h2>An Error Occurred</h2>
<div class="error" style="margin-left:20px;">

You must specify a name for the Study. 
<a href="/<?php echo PROJROOT;?>/studies/create">Click here to try again</a>
</div>
<p>&nbsp;</p>