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
            $res = @$connection->query(sprintf("SELECT haslo FROM admin WHERE login='%s'",
                mysqli_real_escape_string($connection, 'mati')));
            $stupid_mysqli = $res->fetch_assoc();
            $pass_db = $stupid_mysqli['haslo'];

            if (password_verify($_POST['pass_old'], $pass_db)) {

                $connection->query(sprintf("UPDATE admin SET haslo='%s' WHERE login='%s'",
                    mysqli_real_escape_string($connection, $pass_hash),
                    mysqli_real_escape_string($connection, 'mati')));
                $_SESSION['change_com'] = 'Hasło zostało zmienione';

            } else $_SESSION['change_com'] = 'Niepoprawne stare hasło!';
        }
    } else $_SESSION['change_com'] = 'Hasła nie są identyczne!';
} else $_SESSION['change_com'] = 'Hasło musi mieć od 4 do 30 znaków!';

header('Location: rejestracja');
exit();