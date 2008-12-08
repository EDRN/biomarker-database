<?php echo $html->css('bmdb-browser');?>
<div class="menu">
	<!-- Breadcrumbs Area -->
	<div id="breadcrumbs">
	<a href="/<?php echo PROJROOT;?>/">Home</a> / 
	<a href="/<?php echo PROJROOT;?>/studies/">Studies</a> /
	Create a Non-EDRN Study 

	</div><!-- End Breadcrumbs -->
</div>

<h2>Create a Non-EDRN Study:</h2>
<br/>
<form method="POST" action="/<?php echo PROJROOT;?>/studies/createStudy">
<table style="margin-left:25px;">
  <tr>
    <td>Study Title:</td>
    <td><input type="text" name="title"/></td>
  	<td><input type="submit" value="Create"/></td>
  </tr>
</table>
</form>
<p>&nbsp;</p>