<?php
        include "db_newconnection.php";

        $ordiestring = "<p><strong>PHP Info: </strong>Abfrage war nicht möglich.</p>";

            //Wertezuordnung der Inhalte des Formulars

            $email = mysqli_escape_string($tunnel, strtolower($_POST["email"]));
            $passwort = ($_POST["passwort"]);
            $passwortwh = ($_POST["passwortwh"]);



            $loginattempt = 0;
            $iterations = 10000;


            //Eingabevalidierung, ob es sich um eine gülte Email Adresse hatte
             if(!filter_var($email, FILTER_VALIDATE_EMAIL)){

                echo ("Bitte geben Sie eine gültige Email Adresse ein.");

                } else {

                 //Validierung, ob Passwörter übereinstimmen
                if ($passwort == $passwortwh) {

                    //Validierung, ob Passwort leer ist übereinstimmen
                    if ($_POST["passwort"] == NULL) {

                        echo "passwort ist leer";

                    } else {

                    //Validierung, ob Passwort den Kriterien entspricht
                    $validePasswort = passwordValidation($passwort);

                     if ($validePasswort===false){

                        echo 'Das Passwort entspricht nicht den Sicherheitsbestimmungen!<br>
                                                                                    Das Passwor muss aus folgenden Bestandteilen bestehen:<br>
                                                                                    - mindestens 20 Buchstaben<br>
                                                                                    - Groß und Kleinbuchstaben<br>
                                                                                    - Zahlen<br>
                                                                                    - Es sollte nach Möglichkeit auch mindestens ein Sonderzeichen enthalten!';

                        } else {

                            //Validierung, ob Passwort häufig vorkommt
                            $control_password = checkCommonPasswords($passwort, $tunnel);

                                if($control_password != 0){

                                echo 'Das von Ihnen verwendete Passwort wird häufig verwendet, bitte verwenden Sie ein anderes!';

                                }
                                    else {

                                    //Validierung, ob sich die angegebene Email Adresse schon in der Datenbank befindet

                                    $controlEmail = checkEmailExist($email, $tunnel);


                                        if ($controlEmail != 0) {

                                            echo "<p>Email <strong>$email</strong> existiert bereits! Versuchen sie eine andere...</p>";

                                        } else {

                                        //Erzeugung des Hashes und Speichern in die Datenbank

                                        createNewUser($passwort, $email, $loginattempt, $iterations, $tunnel);

                                            echo "<p>Ihr Benutzer wurde erfolgreich angelegt, melden Sie sich jetzt an.</p>";

                                            }
                            }
                        }
                    }
                } else {

                    echo "Achtung! Passwörter stimmen nicht überein";

                }


         }
            mysqli_close($tunnel);

            function generateRandomSalt($length = 50) {

                $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

                $charactersLength = strlen($characters);

                $randomString = '';

                for ($i = 0; $i < $length; $i++) {
                    $randomString .= $characters[rand(0, $charactersLength - 1)];
                }

                return $randomString;
            }

            function passwordValidation($passwort){

                $uppercase = preg_match('@[A-Z]@', $passwort);

                $lowercase = preg_match('@[a-z]@', $passwort);

                $number    = preg_match('@[0-9]@', $passwort);

                $valide;

                if (!$uppercase || !$lowercase || !$number || strlen($passwort) < 20) {

                $valide = false;

                } else {$valide = true;}

                return $valide;

                echo $valide;
            }



            function checkCommonPasswords($passwort, $tunnel){

                 $controlPassword = 0;

                 $commonpasswordquery = "select id from commonpasswords where password = '$passwort'";

                 $result = mysqli_query($tunnel, $commonpasswordquery) or die($ordiestring);

                 while ($row = mysqli_fetch_object($result)) {
                 $controlPassword++;}

                 return $controlPassword;

            }

            function checkEmailExist($email, $tunnel){
            $control = 0;
                                                $sql = "SELECT email FROM users WHERE email = '$email'";

                                                $result = mysqli_query($tunnel, $sql) or die($ordiestring);

                                                while ($row = mysqli_fetch_object($result)) {
                                                    $control++;
                                                }

                                                return $control;
            }

            function createNewUser($passwort, $email, $loginattempt, $iterations, $tunnel){

            //Generierung des Salts
            $salt = generateRandomSalt();

            //Generierung des Hashes mit PBKDF2
            $hash =  hash_pbkdf2("sha3-512", $passwort, $salt, $iterations, 128);

            $sql = "INSERT INTO users (email, salt, passwort, loginattempt) VALUES
                                                              ('" . $email . "', '" . $salt . "', '" . $hash . "', '" . $loginattempt . "');";

                                                        $result = mysqli_query($tunnel, $sql);
            }


?>
