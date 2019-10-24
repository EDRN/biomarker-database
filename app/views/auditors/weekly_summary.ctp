<?php
	// Include required CSS and JavaScript 
	echo $html->css('bmdb-objects');
	echo $html->css('eip');
	// Deprecated in Cake 1.3:
	//echo $javascript->link('mootools-release-1.11');
	//echo $javascript->link('eip');
	// use this instead:
	echo $html->script('mootools-release-1.11');
	echo $html->script('eip');
?>

<div class="menu">
	<!-- Breadcrumbs Area -->
	<div id="breadcrumbs">
		<span style="color:#ddd;">You are here: &nbsp;</span>
		<a href="/<?php echo PROJROOT;?>/">Home</a> :: 
		<span>Latest Changes this week</span>
	</div><!-- End Breadcrumbs -->
</div>
<div id="outer_wrapper">
<div id="main_section">
<div id="content">
<h2>Latest Database Changes This Week...</h2>
<div style="color:#555;font-size:90%;padding-left:5px;padding-top:15px;">
	The following table contains a log of the latest changes made to the database...
</div>
<div>

	<table style="border:solid 1px #ccc;padding:15px;margin:15px;">
		<tbody>
			<tr>
			  <th style="text-align:left;border-bottom:solid 1px #888;">Timestamp</th>
			  <th style="text-align:left;border-bottom:solid 1px #888;">User</th>
			  <th style="text-align:left;border-bottom:solid 1px #888;">Message</th>
			</tr>
			<?php foreach ($audits as $audit):?>
				<tr style="font-size:90%;background-color:#eee;">
				  <td style="width:160px;padding:5px;"><?php echo $audit['Auditor']['timestamp']?></td>
				  <td style="width:90px;"><?php echo $audit['Auditor']['username']?></td>
				  <td><?php echo $audit['Auditor']['message']?></td>
				</tr>
			
			<?php endforeach;?>
		
		</tbody>
	</table>

</div>
</div>
</div>
</div>