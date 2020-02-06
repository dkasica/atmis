<?php

session_start();
require_once "connect.php";

if($_SESSION['success']==true){
    unset($_SESSION['success']);
    echo "Rejestracja udana! E-mail został wysłany do użytkownika :)";
}

if (isset($_SESSION['change_com'])){
    echo $_SESSION['change_com'];
    unset($_SESSION['change_com']);
}

if (!isset($_SESSION['admin'])){
    header('Location: mati.php');
    exit();
}


if (isset($_POST['email'])) {
    //Udana walidacja? Załóżmy, że tak.
    $ok=true;

    //poprawność adresu e-mail
    $email = $_POST['email'];
    $emailB= filter_var($email, FILTER_SANITIZE_EMAIL);
    if ((filter_var($emailB, FILTER_VALIDATE_EMAIL)==false) ||
        ($emailB!=$email)){
        $ok=false;
        $_SESSION['e_email']="Podaj poprawny e-mail";
    };

    //Stworzenie pierwszego hasła:
    $password_array = randomPassword();
    $password = implode("", $password_array);
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    $name = $_POST['name'];
    $surname = $_POST['surname'];
    $podstawowy = "2038-01-09 03:14:07";
    $rozszerzony = NULL;
    if($_POST['rozszerzony'] == 'on'){
        $rozszerzony = '2038-01-09 03:14:07';
    }

    $_SESSION['fr_name'] = $name;
    $_SESSION['fr_surname'] = $surname;
    $_SESSION['fr_email'] = $email;


    try{

        $connection = new mysqli($host, $db_user, $db_password, $db_name);
        if ($connection->connect_errno!=0) {
            throw new Exception(mysqli_connect_errno());
        }
        else{
            //Czy e-mail juz istnieje?
            $result=$connection->query("SELECT id FROM bmi WHERE email='$email'");

            if(!$result) throw new Exception($connection->error);

            $hom_many_emails = $result->num_rows;

            if($hom_many_emails>0){
                $ok=false;
                $_SESSION['e_email']="Istnieje już taki e-mail";
            };

            if ($ok==true)    {
                //Hurra wszystko ok

                if($connection->query("INSERT INTO bmi (Imie, Nazwisko, Haslo, email, podstawa, rozszerzenie) VALUES ('$name', '$surname', '$password_hash', '$email', '$podstawowy', '$rozszerzony')"))
                {
                    //$_SESSION['success']=true;
                    echo "Rejestracja udana! E-mail został wysłany do użytkownika :)";
                    $sender = 'kontakt@atmis.pl';

                    $from  = 'From: '.$sender."\r\n";
                    $from .= 'MIME-Version: 1.0'."\r\n";
                    $from .= 'Content-type: text/html; charset=utf-8'."\r\n";
                    $title = 'Twoje konto w strefie studenta ATMIS';

                    $message = "Witaj ".$name."<br /><br />Twoje konto w strefie studenta ATMIS właśnie zostało utworzone. Znajdziesz tam materiały dodatkowe do naszego kursu.<br />Dane do logowania:<br />e-mail: ".$email."<br />hasło: ".$password."<br /><br />Ze względów bezpieczeństwa sugerujemy zmianę hasła po zalogowaniu do Strefy w panelu użytkownika.<br /><br /> Pozdrawiamy<br />Zespół ATMIS
                <br /><br /><br />
                W razie problemów skontaktuj się z: kontakt@atmis.pl";

                    mail($email, $title, $message, $from);

                }
                else {
                    throw new Exception($connection->error);
                }


            }

            $connection->close();
        }

    } catch (Exception $e) {
        echo 'Błąd serwera! Przepraszamy za niedogodności i prosimy o rejestrację w innym terminie.';
        echo '<br/> Informacja developerska:'.$e;

    }
    


}
function randomPassword() {
    $alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
    for ($i = 0; $i < 8; $i++) {
        $n = rand(0, 59);
        $pass[$i] = $alphabet[$n];
    }
    return $pass;
}
?>
<!DOCTYPE HTML>
<html lang="pl">
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
    <title> Panel administracyjny </title>

    <link rel="stylesheet" href="css/bootstrap.min.css" >
    <link rel="stylesheet" href="css.css" type="text/css"/>
    <style>
        .error {color:red;
            margin-top: 10px;
            margin-bottom: 10px;}
    </style>
</head>
<body>

<div id="login">
<form method="post" action="rejestracja.php">

    <br />Imię: <br /> <input type="text" value="<?php  if (isset($_SESSION['fr_name'])){echo $_SESSION['fr_name']; unset($_SESSION['fr_name']);}?> " name="name" /> <br />
    <?php
    if (isset($_SESSION['e_name'])) {
        echo $_SESSION['e_name'];
        unset ($_SESSION['e_name']);
    }
    ?>
    <br />surname: <br /> <input type="text" value="<?php  if (isset($_SESSION['fr_surname'])){echo $_SESSION['fr_surname']; unset($_SESSION['fr_surname']);}?> " name="surname" /> <br />
    <?php
    if (isset($_SESSION['e_surname'])) {
        echo $_SESSION['e_surname'];
        unset ($_SESSION['e_surname']);
    }


    ?>
    <br />e-mail: <br /> <input type="text" value="<?php  if (isset($_SESSION['fr_email'])){echo $_SESSION['fr_email']; unset($_SESSION['fr_email']);}?>" name="email" /> <br />
    <?php
    if (isset($_SESSION['e_email'])) {
        echo $_SESSION['e_email'];
        unset ($_SESSION['e_email']);
    }
    ?>

    <br />
    <label>Kurs rozszerzony: <input type="checkbox" name="rozszerzony" /></label>

    <br /> <br />
    <input type="submit" value="Zarejestruj użytkownika"/>

</form>
<br /><br />
<a href="logout.php">Wyloguj się</a>
<br /><br />
<a href="zmiana_hasla_adm.php">Zmień hasło</a>
</div>
<br />
<div id="login">
    <h2>Dodaj uprawnienia lub usuń konto</h2>
    <form method="post" action="uprawnienia.php">
        <br /><label>e-mail: <br /> <input type="text" value="<?php  if (isset($_SESSION['upr_email'])){echo $_SESSION['upr_email']; unset($_SESSION['upr_email']);}?>" name="email" /></label> <br />
        <label>USUŃ KONTO: <input type="checkbox" name="delete" /></label><br />
        <input type="submit" value="Wykonaj"/> <br />
    </form>
        <?php
            if (isset($_SESSION['upr_com'])){
                echo $_SESSION['upr_com'];
                unset($_SESSION['upr_com']);
            }
        ?>
</div>
</body>
</html>