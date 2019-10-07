<?php
    require_once('db_newconnection.php'); //require lassen und umbauen!
?>

<!DOCTYPE html>
<html>
<head>

<title>Login Page</title>

    <link href="css/bootstrap.css" rel="stylesheet">

</head>
<body>

<div class="form-group">
    <h1>Login</h1>
    <div class="form">
        <form action="index.php" method="post">

            <div class="form-group">
            <input type="text" placeholder="Email" name="email" required/>
            </div>

            <div class="form-group">
            <input type="password" placeholder="Passwort" name="password" required/>
            </div>

            <button class="btn btn-primary" name="login" type="submit">Einloggen</button>

            <p class="message">Noch nicht registriert? <a href="register.php">Werde jetzt Mitglied</a></p>
        </form>
    </div>

<br>
<br><br>

		<?php
			if(isset($_POST['login']))
			{
				$email=mysqli_escape_string($tunnel, $_POST['email']); //für andere wiederhohlen
				//$password=$_POST['password'];
				$password = mysqli_escape_string($tunnel, ($_POST['password']));
				//$hash = hash('sha256', $password);
				$saltquery = "select salt from users where email = '$email'";
				$iterations = 1000;

				$query_run_salt = mysqli_query($tunnel,$saltquery);
				//$row = mysqli_fetch_array($query_run_salt,MYSQLI_ASSOC);




				while($row = mysqli_fetch_object($query_run_salt)){
                	//$salt = $row['salt'];
                	$salt = $row->salt;
                }

       			$hash =  hash_pbkdf2("sha256", $password, $salt, $iterations, 50);

				$query = "select * from users where email='$email' and passwort='$hash' ";
				//echo $query;
				$query_run = mysqli_query($tunnel,$query);
				//echo mysql_num_rows($query_run);
				if($query_run)
				{
					if(mysqli_num_rows($query_run)>0)
					{
					$row = mysqli_fetch_array($query_run,MYSQLI_ASSOC);

					$_SESSION['name'] = $email;
					//$_SESSION['password'] = $password; muss ich doch nicht an die session übergeben oder?

					header( "Location: helloworld.php");
					}
					else
					{
						echo '<script type="text/javascript">alert("No such User exists. Invalid Credentials")</script>';
					}
				}
				else
				{
					echo '<script type="text/javascript">alert("Database Error")</script>';
				}
			}
			else
			{
			}
			mysqli_close($tunnel);
		?>

</body>
</html>