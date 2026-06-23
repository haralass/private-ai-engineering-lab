<html>
<head>
	<title>Driver Registration Page</title>
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
		<td vAlign=center align=middle><h2>Driver Registration</h2></td>
	</tr>
    </table>
	<hr>
    
    Please fill out the form below to register as a new driver:
    <!-- Action points to the processing script -->
	<form action="register_driver_processor.php" method="post" enctype="multipart/form-data">
	e-mail: <input type="email" name="dbName" required><br>
    Username: <input type="text" name="userName" required><br>
    Password: <input type="password" name="pswd" required><br>
	Address: <input type="text" maxlength="100" name="address" required><br>
    Date of Birth: <input type="date" name="date_of_birth" required><br>
	Password: <input type="password" name="pswd" required><br>
	Confirm password: <input type="password" name="pswd2" required><br>

	<!-- New File Upload Field -->
    ID details:</br>
	id number: <input type="text" name="id_number" required><br>
	id date of publish: <input type="date" name="id_date_of_publish" required><br>
	id expiry date: <input type="date" name="id_date_expiry_date" required><br>
    ID file (PDF/Image): <input type="file" name="id_file" accept=".pdf,.jpg,.jpeg,.png" required><br>

	Driver License details:</br>
	Driver License number: <input type="text" name="license_number" required><br>
	Driver License date of publish: <input type="date" name="license_date_of_publish" required><br>
	Driver License expiry date: <input type="date" name="license_expiry_date" required><br>
    Driver License (PDF/Image): <input type="file" name="driver_license_file" accept=".pdf,.jpg,.jpeg,.png" required><br>

	Police Clearance Certificate details:</br>
	Police Clearance Certificate number: <input type="text" name="police_clearance_number" required><br>
	Police Clearance Certificate date of publish: <input type="date" name="police_clearance_date_of_publish" required><br>
	Police Clearance Certificate expiry date: <input type="date" name="police_clearance_expiry_date" required><br>
    Police Clearance Certificate (PDF/Image): <input type="file" name="police_clearance_file" accept=".pdf,.jpg,.jpeg,.png" required><br>

	Medical Certificate (PDF/Image):</br>
	Medical Certificate number: <input type="text" name="medical_certificate_number" required><br>
	Medical Certificate date of publish: <input type="date" name="medical_certificate_date_of_publish" required><br>
	Medical Certificate expiry date: <input type="date" name="medical_certificate_expiry_date" required><br>
    Medical Certificate (PDF/Image): <input type="file" name="medical_certificate_file" accept=".pdf,.jpg,.jpeg,.png" required><br>

	Mental Health Certificate (PDF/Image):</br>
	Mental Health Certificate number: <input type="text" name="mental_health_certificate_number" required><br>
	Mental Health Certificate date of publish: <input type="date" name="mental_health_certificate_date_of_publish" required><br>
	Mental Health Certificate expiry date: <input type="date" name="mental_health_certificate_expiry_date" required><br>
    Mental Health Certificate (PDF/Image): <input type="file" name="mental_health_certificate_file" accept=".pdf,.jpg,.jpeg,.png" required><br>

    <input type="submit" name="register_driver" value="Register as Driver">
    </form>
</body>

</html>