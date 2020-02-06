<?php
session_start();

if ((! isset($_POST['email'])) || (! isset($_POST['password']))) {
   header('Location: index');
   exit();
}
require_once "connect.php";

$connection = @new mysqli($host, $db_user, $db_password, $db_name);

if ($connection->connect_errno!=0) {
    echo "Error:".$connection->connect_errno." Opis:".$connection->connect_error;
} else {  
    $email = $_POST['email'];
    $password = $_POST['password'];
    $_SESSION['email'] = $email;
    
    $sql="SELECT * FROM bmi WHERE email='$email' AND Haslo='$password'";
              
    if($result=@$connection->query(sprintf("SELECT * FROM bmi WHERE email='%s'",
      mysqli_real_escape_string($connection, $email))))   {
            $users_number=$result->num_rows;
            if($users_number>0)     {
                $row=$result->fetch_assoc();
                if (password_verify($password, $row['Haslo'])) {
                    $_SESSION['signed']=true;
                    $_SESSION['id']=$row['id'];
                    $_SESSION['name']=$row['Imie'];
                    $_SESSION['surname']=$row['Nazwisko'];
                    $_SESSION['podstawa']=$row['podstawa'];
                    $_SESSION['rozszerzenie']=$row['rozszerzenie'];
                    unset($_SESSION['error']);
                    $result->free_result();
                    header('Location: podstawowy');
                } else  {
                    $_SESSION['error']="Nieprawidłowy adres email lub hasło!";
                    header('Location: index');
                }
            } else  {
                $_SESSION['error']="Nieprawidłowy adres email lub password!";
                header('Location: index');
            }            
        }
    $connection->close();
}



?>