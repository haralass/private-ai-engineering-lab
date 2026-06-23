<html>
<head>
	<title>EPL342 project test page</title>
</head>
<body>

	<table cellSpacing=0 cellPadding=5 width="100%" border=0>
	<tr>
		<td vAlign=top width=170><img height=91 alt=UCY src="images/ucy.jpg" width=94>
			<h5>
				<a href="http://www.ucy.ac.cy/">University of Cyprus</a><BR/>
				<a href="http://www.cs.ucy.ac.cy/">Dept. of Computer Science</a>
			</h5>
		</td>
		<td vAlign=center align=middle><h2>Welcome to the EPL342 project test page</h2></td>
	</tr>
    </table>
	<hr>
    
    Please fill out the form below to register as a new user:
	<form action="register_user_processor.php" method="post">
	e-mail: <input type="text" name="dbName"><br>
    Username: <input type="text" name="userName"><br>
    Password: <input type="password" name="pswd"><br>
	Address: <input type="text" maxlength="100" name="address"><br>
	Confirm password: <input type="password" name="pswd2"><br>
    <input type="submit" name="register"> 
    </form>
</body>

</html>
