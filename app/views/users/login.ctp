<?php echo $html->css('bmdb-browser');?>
<div class="menu">
	<!-- Breadcrumbs Area -->
	<div id="breadcrumbs">
		<a href="/<?php echo PROJROOT;?>/">Home</a> / Login
	</div><!-- End Breadcrumbs -->
</div>
<br/>
<div style="padding-left:8px;">
<h2>Please Log In</h2>
You must be logged in to continue...
<br/><br/>
<?php if ($error):?>
<div class="error" style="margin-left:20px;">
        Invalid Login. Please try again...
</div>

<?php endif; ?>
<form action="/<?php echo PROJROOT;?>/users/login" method="POST" style="margin-left:20px;"/>
	<table>
	  <tr><td>Username:</td><td><input type="text" id="loginusername" name="username"/></td></tr>
	  <tr><td>Password:</td><td><input type="password" name="password"/></td></tr>
	  <tr><td colspan="2"><input type="submit" value="Log In"/></td></tr>
	</table>
</form>
<p>&nbsp;</p>
</div>
<script type="text/javascript">
	// Automatically give the focus to the username text field
	document.getElementById('loginusername').focus();
</script>
