<?php echo $html->css('frozenbrowser');?>
<div class="menu">
	<div class="mainContent">
		<h2 class="title">EDRN Biomarker Database</h2>
	</div>
	<!-- Breadcrumbs Area -->
	<div id="breadcrumbs">
		<a href="/<?php echo PROJROOT;?>/">Home</a> / Login
	</div><!-- End Breadcrumbs -->
</div>

<h2>Please Log In</h2>

<?php if ($error):?>
<div class="error" style="margin-left:20px;">
        Invalid Login. Please try again...
</div>
<?php endif; ?>
<p>&nbsp;</p>
<form action="/<?php echo PROJROOT;?>/users/login" method="POST" style="margin-left:20px;"/>
	<table>
	  <tr><td>Username:</td><td><input type="text"     name="username"/></td></tr>
	  <tr><td>Password:</td><td><input type="password" name="password"/></td></tr>
	  <tr><td colspan="2"><input type="submit" value="Log In"/></td></tr>
	</table>
</form>
<p>&nbsp;</p>