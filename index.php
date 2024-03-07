<!DOCTYPE html>
<html>
<head>
	
	<title> DevLog </title>
	<meta charset="UTF-8">
	<link id="css-link" href="styles.css" rel="stylesheet" type="text/css" />

</head>


<!--  TO DO, Add a 'Dark Mode' Button to Swap CSS files -->

<body>

	<?php

        $DB_CONNECT = mysqli_connect("localhost", "DevLog", "BjWQHm+46gN7", "DevLog");
        // Set character encoding for database communication
        mysqli_set_charset($DB_CONNECT, "utf8");

	?>

	<button onclick="toggleCSS()" style="float: right;"> 🜂 </button>

<script>
	function toggleCSS() {
		var cssLink = document.getElementById("css-link");

		if (cssLink.getAttribute("href") === "styles.css") {
			cssLink.setAttribute("href", "dark-theme.css"); 
		} else {
			cssLink.setAttribute("href", "styles.css");
		}
	}
</script>

	<h1> DevLog </h1>




	<!-- ADD CLIENT FORM -->
<div class="add_client" id="add_client">
	<h2>Add Client</h2>
	<form action="add_client.php" method="POST">
		Name: <input type="text" name="name"><br>
		Email: <input type="email" name="email"><br>
		Office: <input type="text" name="office"><br>

		<input type="submit" name="submit" value="Submit">
		<input type="button" name="cancel" value="Cancel" 
			onclick="window.location.href='index.php';">
	</form>
</div>


	<!-- ADD DEVICE FORM -->

<!-- Fix to Allow update to same ID -->

<div class="add_device" id="add_device">
	<h2>Add Device</h2>
	<form action="add_device.php" method="POST">
		ID: <input type="text" name="id"><br>
		Brand: <input type="text" name="brand"><br>
		Type: <input type="text" name="type"><br>
		Problem: <textarea name="problem"></textarea><br>
		Date of Problem: <input type="date" name="date_of_problem"><br>
		<!-- Status: <input type="checkbox" name="status" value="1"
		<?php if(isset($_POST['status']) && $_POST['status'] == '1') echo 'checked'; ?>> 
	    -->
		<br>
		Client Email: <input type="text" name="client_email"><br>

		<input type="submit" name="submit" value="Submit">
		<input type="button" name="cancel" value="Cancel" 
			onclick="window.location.href='index.php';">
	</form>
</div>


	<!-- ADD TECHNICIAN FORM -->
<div class="add_technician" id="add_technician">
	<h2>Add Labour to Device </h2>
	<form action="add_technician.php" method="POST">
		Technician Name: <input type="text" name="name"><br>
		Labour Performed: <textarea name="labour_performed"></textarea><br>
		Date Start: <input type="date" name="date_start"><br>
		Date Finished: <input type="date" name="date_finished"><br>
		Device ID: <input type="text" name="device_id"><br>

		<input type="submit" name="submit" value="Submit">
		<input type="button" name="cancel" value="Cancel" 
			onclick="window.location.href='index.php';">
	</form>
</div>

	<!-- VIEW CLIENTS TABLE -->
<div class="view_client" id="view_client">
	<h2>View Clients</h2>
	<table>
		<thead>
			<tr>
				<th>Name</th>
				<th>Email</th>
				<th>Office</th>
			</tr>
		</thead>
		<tbody>
			<!-- PHP code to retrieve and display data from the Client table -->
			<?php
				// Your database connection code goes here

				$QUERY = "SELECT * FROM Client";
				$RESULT = mysqli_query($DB_CONNECT, $QUERY);

				while ($ROW = mysqli_fetch_assoc($RESULT)) {
					echo "<tr>";
					echo "<td>".$ROW['name']."</td>";
					echo "<td>".$ROW['email']."</td>";
					echo "<td>".$ROW['office']."</td>";

					echo "<td><button onclick=\"window.location.href='remove_client.php?email=".
					$ROW['email']."';\">Remove</button></td>";

					echo "</tr>";
				}	// end of while loop
			?>
		</tbody>
	</table>
</div>





	<!-- VIEW DEVICES TABLE -->

<!-- TO DO: Add a "Modify Button" that when pressed,
opens a JavaScript alert styled with the respective input fields
in which to add or modify a column. -->

<div class="view_devices" id="view_devices">
	<h2>View Devices</h2>
	<table>
		<thead>
			<tr>
				<th>ID</th>
				<th>Brand</th>
				<th>Type</th>
				<th>Problem</th>
				<th>Date of Problem</th>
				<th> Client Info </th>
				<th>Status</th>
				<th> Labour Performed </th>
			</tr>
		</thead>
		<tbody>

			<!-- PHP code to retrieve and display data from the Device table -->
			<?php
				// Your database connection code goes here

				// Device Query
				$QUERY = "SELECT * FROM Device";

				// If a technician worked on a device,
				// display the specifications.
				$TECH_QUERY = "SELECT * FROM Technician";

				// Device Query Result
				$RESULT = mysqli_query($DB_CONNECT, $QUERY);
				if (!$RESULT) {
					die("Device query failed: " . mysqli_error($DB_CONNECT));
				}

				while ($DEVICE = mysqli_fetch_assoc($RESULT)) {

					echo "<tr>";
					echo "<td><b>".$DEVICE['ID']."</b></td>";
					echo "<td>".$DEVICE['brand']."</td>";
					echo "<td>".$DEVICE['type']."</td>";
					echo "<td>".$DEVICE['problem']."</td>";
					echo "<td>".$DEVICE['date_of_problem']."</td>";
					echo "<td>".$DEVICE['client_email']."</td>";
					echo "<td>".$DEVICE['status']."</td>";

					$TECH_RESULT = mysqli_query($DB_CONNECT, $TECH_QUERY);
					if (!$TECH_RESULT) {
						die("Technician query failed: " . mysqli_error($DB_CONNECT));
					}

					$labour_performed = ""; $technician = "";
					while ($TECH_DATA = mysqli_fetch_assoc($TECH_RESULT)) {
						if ($TECH_DATA['device_id'] == $DEVICE['ID']) {
							$labour_performed .= $TECH_DATA['labour_performed']."<br>";
							$technician .= $TECH_DATA['name'];
						}
					}

					if (!empty($labour_performed)) {
						echo "<td>"  . "<b>" . $technician . ":</b>\t" 
						. $labour_performed . "</td>";
					
					} else {
						echo "<td>No labour performed</td>";
					}

					// Toggle Fix Button
					echo "<td><form method='POST' action='fix_device.php'>
					<input type='hidden' name='device_id' value='".$DEVICE['ID'].
					"'><input type='submit' value='Status'></form></td>";
					
					// Edit Button
					// echo "<td> <button onclick=\"openEditAlert(" . $DEVICE['ID'] . ")\">Edit</button>
					// </td>";


					// Remove Button
					echo "<td><button onclick=\"window.location.href='remove_device.php?id=".
					$DEVICE['ID']."';\">Remove</button></td>";
					
					echo "</tr>";
				}
			?>

		</tbody>
	</table>

</div>


<div class="footer" id="manual">
	<footer>
		<p><a href="#manual" onclick="showInstructions();">User's Manual</a></p>
	</footer>
</div>

<script>
    function showInstructions() {
alert("User's Manual - DevLog PHP Database\n\n" +
"Introduction:\n" +
"DevLog (Device Logs) is a PHP application that allows you to manage clients, devices, and technicians' labor in a database.\n" +
"This user's manual will provide you with an overview of the features and functionality of DevLog.\n\n" +
"Adding a Client:\n" +
"To add a new client, follow these steps:\n" +
"a. Enter the client's name, email, and office details in the \"Add Client\" form.\n" +
"b. Click the \"Submit\" button to add the client to the database.\n" +
"c. If you want to cancel the operation, click the \"Cancel\" button.\n\n" +
"Adding a Device:\n" +
"To add a new device, follow these steps:\n" +
"a. Fill in the ID, brand, type, problem description, date of the problem,\n" +
"   and client email in the \"Add Device\" form.\n" +
"b. Click the \"Submit\" button to add the device to the database.\n" +
"c. If you want to cancel the operation, click the \"Cancel\" button.\n\n" +
"Adding Labour to a Device:\n" +
"To add labour details to a device, follow these steps:\n" +
"a. Enter the technician's name, performed labour description, start date, finish date,\n" +
"   and device ID in the \"Add Labour to Device\" form.\n" +
"b. Click the \"Submit\" button to add the labour details to the database.\n" +
"c. If you want to cancel the operation, click the \"Cancel\" button.\n\n" +
"Viewing Clients:\n" +
"The \"View Clients\" table displays a list of all clients in the database.\n" +
"The table includes columns for the client's name, email, and office.\n" +
"To remove a client, click the \"Remove\" button next to the respective client's entry.\n\n" +
"Viewing Devices:\n" +
"The \"View Devices\" table provides an overview of all devices in the database.\n" +
"The table includes columns for ID, brand, type, problem description, date of the problem,\n" +
"status, labour performed, and client information.\n" +
"The \"Toggle Status\" button allows you to change the status of a device.\n" +
"To remove a device, click the \"Remove\" button next to the respective device's entry.\n\n" +
"Note: Make sure to set up the database connection by modifying the database connection parameters in the PHP code.\n" +
"The ' 🜂 ' on the top right toggles between light and dark modes respectively" +
"\nThat concludes the user's manual for the DevLog PHP Database." +
"\n\n\n\t\t\t\t\t\t\t 🐧");
    }
</script>




</body>

</html>

