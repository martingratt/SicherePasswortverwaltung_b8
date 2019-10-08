<?php
        include "db_newconnection.php";

        $ordiestring = "<p><strong>PHP Info: </strong>Abfrage war nicht möglich.</p>";
            //alles klein
            $email = mysqli_escape_string($tunnel, strtolower($_POST["email"]));
            //verschlüsselung
            $passwort = mysqli_escape_string($tunnel, $_POST["passwort"]);
            $passwortwh = mysqli_escape_string($tunnel, $_POST["passwortwh"]);
            $salt = openssl_random_pseudo_bytes(16);
            $iterations = 10000;
            $hash =  hash_pbkdf2("sha3-512", $passwort, $salt, $iterations, 50);

             if(!filter_var($email, FILTER_VALIDATE_EMAIL)){

                echo ("Bitte geben Sie eine gültige Email Adresse ein.");

                } else {

                if ($passwort == $passwortwh) {
                    //$hash = hash('sha256', $passwort);
                    //Vergleich ob alle Datensätze ausgefüllt wurden
                    if ($_POST["passwort"] == NULL) {
                        echo "passwort ist leer";
                    } else {
                        if (!preg_match('/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/', $passwort)) {
                            echo 'Das Passwort entspricht nicht den Sicherheitsbestimmungen!<br>
                              Das Passwor muss aus folgenden Bestandteilen bestehen:<br>
                              - mindestens 8 Buchstaben<br>
                              - Groß und Kleinbuchstaben<br>
                              - Zahlen<br>
                              - Es sollte nach Möglichkeit auch mindestens ein Sonderzeichen enthalten!';
                        } else {

                            $control = 0;
                            $sql = "SELECT email FROM users WHERE email = '$email'";
                            $result = mysqli_query($tunnel, $sql) or die($ordiestring);
                            while ($row = mysqli_fetch_object($result)) {
                                $control++;
                            }
                            if ($control != 0) {
                                $errorUserNameExists = true;
                                echo "<p>Email <strong>$email</strong> existiert bereits! Versuchen sie eine andere...</p>";
                            } else {
                                $sql = "INSERT INTO users (email, salt, passwort) VALUES
                                      ('" . $email . "', '" . $salt . "', '" . $hash . "');";
                                $result = mysqli_query($tunnel, $sql);
                                echo "<p>Ihr Benutzer wurde erfolgreich angelegt, melden Sie sich jetzt an.</p>";
                            }
                        }
                    }
                } else {
                    echo "Achtung! Passwörter stimmen nicht überein";
                }

                //else {                    echo "Diese Email "                }
         }
            mysqli_close($tunnel);

            function getSalt() {
                 $charset = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789/\\][{}\'";:?.>,<!@#$%^&*()-_=+|';
                 $randStringLen = 64;

                 $randString = "";
                 for ($i = 0; $i < $randStringLen; $i++) {
                     $randString .= $charset[mt_rand(0, strlen($charset) - 1)];
                 }

                 return $randString;
            }
?>
