<?php echo $html->css('bmdb-broswer');?>
<div class="menu">
	<!-- Breadcrumbs Area -->
	<div id="breadcrumbs">
	<a href="/<?php echo PROJROOT;?>/">Home</a> / 
	<a href="/<?php echo PROJROOT;?>/sites/">Sites</a> /
	Create a Non-EDRN Site 
	</div>
</div>

<h2>Create a Non-EDRN Site:</h2>
<br/>
<form method="POST" action="/<?php echo PROJROOT;?>/sites/createSite">
<table style="margin-left:25px;">
  <tr>
    <td>Site Name:</td>
    <td><input type="text" name="name"/></td>
  	<td><input type="submit" value="Create"/></td>
  </tr>
</table>
</form>
<p>&nbsp;</p>
