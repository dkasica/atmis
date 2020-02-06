<?php
  session_start();
  if($_SESSION['admin']==true){
    header('Location: rejestracja');
    exit();
  }

  ?>
<!DOCTYPE HTML>
<html lang="pl">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
    <title> Logowanie do panelu admina ATMIS </title>
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
          <h2>Logowanie do panelu administracyjnego</h2>
        </div>
        <div class="w-100"></div>
        <div class="col-12" style="text-align: center">
            <form action="check" method="post">
              <label>Login:  <input type="text" name="login"/></label><br/>
              <label>Hasło:  <input type="password" name="password_adm"/></label><br/>
              <input type="submit" value="Zaloguj"/>
            </form>

            <?php
            if (isset($_SESSION['error']))
              echo $_SESSION['error'];
              unset($_SESSION['error']);
            ?>
        </div>
    </div>
</div>


<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>

<script src="js/bootstrap.min.js"></script>
</body>
</html>