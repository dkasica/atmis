<?php
session_start();

if(strlen($_POST['pass_new1'])>3 && strlen($_POST['pass_new1']<31)) {
    if ($_POST['pass_new1'] == $_POST['pass_new2']) {
        $pass_hash = password_hash($_POST['pass_new1'], PASSWORD_DEFAULT);
        require_once "connect.php";
        $connection = @new mysqli($host, $db_user, $db_password, $db_name);

        if ($connection->connect_errno != 0) {
            echo "Error:" . $connection->connect_errno . "Description:" . $connection->connect_error;

        } else {
            $res = @$connection->query(sprintf("SELECT Haslo FROM bmi WHERE id='%s'",
                mysqli_real_escape_string($connection, $_SESSION['id'])));
            $stupid_mysqli = $res->fetch_assoc();
            $pass_db = $stupid_mysqli['Haslo'];

            if (password_verify($_POST['pass_old'], $pass_db)) {

                $connection->query(sprintf("UPDATE bmi SET Haslo='%s' WHERE id='%s'",
                    mysqli_real_escape_string($connection, $pass_hash),
                    mysqli_real_escape_string($connection, $_SESSION['id'])));
                $_SESSION['change_com'] = 'Hasło zostało zmienione';

            } else $_SESSION['change_com'] = 'Niepoprawne stare hasło!';
        }
    } else $_SESSION['change_com'] = 'Hasła nie są identyczne!';
} else $_SESSION['change_com'] = 'Hasło musi mieć od 4 do 30 znaków!';

header('Location: zmiana_hasla.php');
exit();