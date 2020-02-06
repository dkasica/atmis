<?php
session_start();
if($_SESSION['admin']!=true){
	header('Location: index');
	exit();
}
require_once "connect.php";
$connection = new mysqli($host, $db_user, $db_password, $db_name);
$_SESSION['upr_email']=$POST['email'];
if ($connection->connect_errno!=0) {
    throw new Exception(mysqli_connect_errno());
}else{
    if (isset($_POST['email'])){
    	$email=$_POST['email'];
    	$emailB= filter_var($email, FILTER_SANITIZE_EMAIL);

    	if ((filter_var($emailB, FILTER_VALIDATE_EMAIL)==false) || ($emailB!=$email)){
        $_SESSION['upr_com']="Podaj poprawny e-mail";
        header('Location:rejestracja');
        exit();
    	}else{
    	
    		$result=$connection->query("SELECT * FROM bmi WHERE email='$email'");

        	if(!$result) throw new Exception($connection->error);

        	if($result->num_rows>0){

        		if (isset($_POST['email'])){
                    if ($_POST['delete']=="on"){

                        $connection->query("DELETE FROM bmi WHERE email='$email'");
                        $_SESSION['upr_com']="Konto ".$email." poprawnie usunięte";
                        unset($_SESSION['upr_email']);
                        header('Location:rejestracja');
                        $connection->close();
                        exit();

                    }else{

					$connection->query(sprintf("UPDATE bmi SET rozszerzenie='%s' WHERE email='%s'",
            	        mysqli_real_escape_string($connection, '2038-01-09 03:14:07'),
                	    mysqli_real_escape_string($connection, $email)));
					$_SESSION['upr_com']="Uprawnienia dla ".$email." rozszerzone";
					unset($_SESSION['upr_email']);
                    $connection->close();
					header('Location:rejestracja');
        			exit();
            	    }
                }
        	}else{
        		$_SESSION['upr_com']="Nie ma takiego adresu w bazie";
                $connection->close();
        		header('Location:rejestracja');
        		exit();
        	}
        	$connection->close();
    	}
	}
}
