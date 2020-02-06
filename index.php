<?php
session_start();
if((isset($_SESSION['zalogowany']))&&($_SESSION['zalogowany']==true))    {
    header('Location: podstawowy');
    exit();
}
?>
<!DOCTYPE HTML>
<html lang="pl">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
    <title> Logowanie do strefy studenta ATMIS </title>
    <link rel="stylesheet" href="css/bootstrap.min.css" >
    <link rel="stylesheet" href="css/css.css" type="text/css"/>

    <!--[if lt IE 9]>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.min.js"></script>
    <![endif]-->
</head>
<body>
<div class="container">
    <div class="row">
        <div class="col-12" style="text-align: center">
            <img src="img/atmis.duze.png" height="275" width="245" alt="logo">
        </div>
        <div class="w-100"></div>
        <div class="col-12" style="text-align: center">
            <form action="zaloguj" method="post">
                <label>E-mail:  <input type="text" name="email"/></label><br/>
                <label>Hasło:  <input type="password" name="password"/></label><br/>
                <input type="submit" value="Zaloguj"/>
            </form>

            <?php
            if (isset($_SESSION['blad']))
                echo $_SESSION['blad'];
            //unset($_SESSION['blad']);
            ?>
        </div>
    </div>
</div>


<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>

<script src="js/bootstrap.min.js"></script>
</body>
</html>








