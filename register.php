
<html>
<head>
    <title>
        register
    </title>

    <link href="css/bootstrap.css" rel="stylesheet">

</head>
<body>

<h1>Registrierung</h1>


    <?php
    $errorUserNameExists = false;
    if (isset($_POST["submit"])){
        //werte prüfen - evtl. einfügen wenn alles passt
        //falls was nicht passt - error setzen
        require_once('insert_new_user.php');
    }
    ?>


<form action="register.php" type="submit" method="post">


                <div class="form-group">
                <p><input class="form-control" type="text" placeholder="Email" name="email" required/></p>
                </div>

                <div class="form-group">
                <input class="form-control" type="password" placeholder="Passwort" name="passwort" required/>
                </div>

                <div class="form-group">
                <input class="form-control" type="password" placeholder="Passwort wiederhohlen" name="passwortwh" required/>
                </div>

                <button class="btn btn-primary" value="Registrierern" name="submit">Registieren</button>

                <p class="message">Schon registriert? <a href="index.php">Jetzt anmelden</a></p>
            </form>



</form>

</body>

</html>