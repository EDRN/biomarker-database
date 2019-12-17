<?php echo $this->Html->css('bmdb-broswer');?>
<div class="menu">
	<!-- Breadcrumbs Area -->
	<div id="breadcrumbs">
	<a href="/">Home</a> / 
	<a href="/sites/">Sites</a> /
	Create a Non-EDRN Site 
	</div>
</div>

<h2>Create a Non-EDRN Site:</h2>
<br/>
<form method="POST" action="/sites/createSite">
<table style="margin-left:25px;">
  <tr>
    <td>Site Name:</td>
    <td><input type="text" name="name"/></td>
  	<td><input type="submit" value="Create"/></td>
  </tr>
</table>
</form>
<p>&nbsp;</p>
