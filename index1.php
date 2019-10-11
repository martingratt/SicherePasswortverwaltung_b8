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

			    ///Wertezuordnung der Inhalte des Formulars

				$email=mysqli_escape_string($tunnel, $_POST['email']);
				$password = mysqli_escape_string($tunnel, ($_POST['password']));


				$iterations = 10000;
				$loginattempt = 0;
				$salt = 0;
				$max_loginattempts = 3;

				$salt = getSalt($email, $tunnel);

                $loginattempt = getLoginattempts($email, $tunnel);


       			$hash =  getHash($password, $salt, $iterations);


				$query = "select * from users where email='$email' and passwort='$hash' ";

				$query_run = mysqli_query($tunnel,$query);


				//$check = checkEmailPassword($email, $password, $tunnel);

				if($query_run)
				{

				if($loginattempt>=$max_loginattempts) {
				echo "Es wurden zu viele Loginversuche auf diese Email-Adresse angewandt, bitte kontaktieren Sie den Administrator";
				}

				else {

				if(mysqli_num_rows($query_run)>0)
                					{

                					resetLoginattempts($email, $tunnel, $loginattempt);

                					header( "Location: helloworld.php");
                					}
                					else
                					{

                					    increaseLoginattempts($email, $tunnel, $loginattempt);

                						echo '<script type="text/javascript">alert("No such User exists. Invalid Credentials")</script>';

                					}

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

			function resetLoginattempts($email, $tunnel, $loginattempt) {
                           $query_reset_loginattempt = "UPDATE users SET loginattempt = '0' WHERE email='$email' ";

                           $query_run = mysqli_query($tunnel,$query_reset_loginattempt);

                           echo "Erfolgreicher login";

                           echo $loginattempt;
                        }

            function getLoginattempts($email, $tunnel) {
                                       $loginattempt = 0;

                                       $loginattemptquery = "select loginattempt from users where email = '$email'";

                                       $query_run_loginattempt = mysqli_query($tunnel,$loginattemptquery);

                                       while($row = mysqli_fetch_object($query_run_loginattempt)){
                                       $loginattempt = $row->loginattempt;
                                       }

                                       return $loginattempt;
                                    }

            function increaseLoginattempts($email, $tunnel, $loginattempt) {
                                       $loginattempt = $loginattempt + 1;

                                       $query_increase_loginattempt = "UPDATE users SET loginattempt = '$loginattempt' WHERE email='$email' ";

                                       $query_run = mysqli_query($tunnel,$query_increase_loginattempt);

                                       echo $loginattempt;
                                    }

            function getSalt($email, $tunnel) {

                                       $saltquery = "select salt from users where email = '$email'";

                                       $query_run_salt = mysqli_query($tunnel,$saltquery);

                                       while($row = mysqli_fetch_object($query_run_salt)){

                                       $salt = $row->salt;
                                       return $salt;}
                                       }

            function getHash($password, $salt, $iterations){

            $hash = hash_pbkdf2("sha3-512", $password, $salt, $iterations, 128);

            return $hash;
            }

            function checkEmailPassword($email, $password, $tunnel){

            $control = 0;

            $query = "select * from users where email='$email' and passwort='$hash' ";

            $result = mysqli_query($tunnel,$query);

            while ($row = mysqli_fetch_object($result)) {
                             $control++;}

                             return $control;

            }

		?>

</body>
</html>