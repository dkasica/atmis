<?php
session_start();

require_once 'connect.php';
mysqli_report(MYSQLI_REPORT_STRICT);
$connection = @new mysqli($host, $db_user, $db_password, $db_name);
if ($connection->connect_errno!=0) {
    echo "Error:".$connection->connect_errno."Opis:".$connection->connect_error; //jeśli brak połączenia
    
} else {  
    $login = $_POST['login'];
    $password = $_POST['password_adm'];
              
    if($result=@$connection->query(sprintf("SELECT * FROM admin WHERE login='%s'",
      mysqli_real_escape_string($connection, $login))))   {
        $adm_rows = $result->num_rows;
            if($adm_rows>0)     {
                $row=$result->fetch_assoc();
                if (password_verify($password, $row['haslo'])) {
                    $_SESSION['admin']=true;
                    unset($_SESSION['error']);
                    $result->free_result();
                    header('Location: rejestracja.php');
                    exit();
                } else  {
                    $_SESSION['error']="Nieprawidłowe hasło!";
                    header('Location: mati.php');
                    exit();
                }
            } else  {
                $_SESSION['error']="Nieprawidłowy login!";
                header('Location: mati.php');
                exit();
            }            
        }
}
$connection->close();
