<?php echo $this->Html->css('bmdb-browser');?>
<div class="menu">
	<!-- Breadcrumbs Area -->
	<div id="breadcrumbs">
	<a href="/">Home</a> / Biomarkers
	</div><!-- End Breadcrumbs -->
</div>

<h2>Create a New Biomarker:</h2>
<br/>
<form method="POST" action="/biomarkers/createBiomarker">
<table style="margin-left:25px;">
  <tr>
    <td>Biomarker Long Name:</td>
    <td><input type="text" name="name"/></td>
  	<td><input type="submit" value="Create"/></td>
  </tr>
</table>
</form>
<p>&nbsp;</p>