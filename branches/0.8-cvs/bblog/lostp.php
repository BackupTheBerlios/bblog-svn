<?php

// Author: Xushi ... =)
//
// Will retrieve the secret quesetion from the DB, and
// secret answer from the user, and send it to getp.php
// in order to retrieve the admin password.

	
// the pot of gold..
include 'config.php';



// make sure the page is never cached - we should probally set no-cache headers also.
$bBlog->setmodifytime(time());


	// get question from DB
	$secQuestion = $bBlog->get_var("select value from ".T_CONFIG." where name='SECRET_QUESTION'");
	
?>

<html>
	<header>
		<link rel="stylesheet" type="text/css" title="Main" href="style/admin.css" media="screen" />
	</header>

	<body>
		<div id="header">
			<h1>bBlog</h1>
			<h2>Password Recovery</h2>
		</div>


		<div style="width: 500px; margin-left: auto; margin-right: auto; margin-top: 80px;">
		<form action="get-email-pass.php" method="post">
			<table border="0" class='list' cellpadding="4" cellspacing="0">
				<tr bgcolor="#FFFFF">
					<td width="33%">Username:</td>
		  			<td width="200"><input type="text" name="username" value="" /></td>
				</tr>
				<tr bgcolor="#FFFFF">
					<td width="33%">Question:</td>
		  			<td width="200"><?php echo $secQuestion; ?></td>
		  		</tr>
				<tr bgcolor="#FFFFF">
					<td width="33%">Answer:</td>
		  			<td width="200"><input type="password" name="pass" value="" /></td>
				</tr>
			</table>
			<p><input type="submit" name="submit" value="Submit" />
		</form>
		</div>

		<div id="footer">
			<a href="http://www.bBlog.com" target="_blank">
			bBlog 0.8</a> &copy; 2005 <a href="mailto:eaden@eadz.co.nz">Eaden McKee</a> &amp; <a href="index.php?b=about" target="_blank">Many Others</a>
		</div>
	</body>
</html>