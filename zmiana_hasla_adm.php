<?php
session_start();
if (!isset($_SESSION['admin'])||$_SESSION['admin']!=true){
    header('Location: mati');
    exit();
}

?>
<!DOCTYPE HTML>
<html lang="pl">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
    <title> Zmiana hała </title>
    <link rel="stylesheet" href="css/bootstrap.min.css" >
    <link rel="stylesheet" href="css.css" type="text/css"/>

    <!--[if lt IE 9]>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.min.js"></script>
    <![endif]-->
</head>
<body>
<div id="login">
    <form action="zmiana_adm.php" method="post">
        <label>Aktualne hasło:  <input type="password" name="pass_old"/><br/></label>
        <label>Nowe hasło:  <input type="password" name="pass_new1"/><br/></label>
        <label>Powtórz nowe hasło:  <input type="password" name="pass_new2"/><br/></label>
        <input type="submit" value="Zmień hasło"/>
    </form>

    <?php
    if (isset($_SESSION['change_com'])){
        echo $_SESSION['change_com'];
        unset($_SESSION['change_com']);
    }
    ?>
    <br /><br />
    <a href="logout.php">Wyloguj się</a>
    <br /><br />
    <a href="rejestracja">Rejestracja użytkowników</a>
</div>
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>

<script src="js/bootstrap.min.js"></script>
</body>
</html>