<?php

				$servername = "localhost";
				$username = "decoffice";
				$password = "Airbus@380_decoffice";
				$dbname = "meter_reading";

				// Create connection
				$conn = mysqli_connect($servername, $username, $password, $dbname);
				// Check connection
				if (!$conn) {
					die("Connection failed: " . mysqli_connect_error());
				}

?>