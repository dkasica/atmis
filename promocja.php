<?php
session_start();
require_once "connect.php";

if (isset($_POST['email'])) {
    $ok = true;

    //poprawność adresu e-mail
    $email = $_POST['email'];
    $emailB = filter_var($email, FILTER_SANITIZE_EMAIL);
    if ((filter_var($emailB, FILTER_VALIDATE_EMAIL) == false) ||
        ($emailB != $email)
    ) {
        $ok = false;
        $_SESSION['echo'] = "Podaj poprawny e-mail.";
    };

    if(!isset($_POST['consent'])){
        $ok = false;
        $_SESSION['echo'] = "Aby uzyskać dostęp musisz zaakceptować postanowienia Polityki prywatności.";
    }

    //Stworzenie pierwszego hasła:
    $pass_array = randomPassword();
    $pass = implode("", $pass_array);
    $pass_hash = password_hash($pass, PASSWORD_DEFAULT);

    $today = date('Y-m-d H:i:s');
    $tomorrow = date('Y-m-d H:i:s', strtotime('+1 day', strtotime($today)));
    $promoBoolean = 1;
    $name = NULL;
    $surname = NULL;
    $podstawowy = $tomorrow;
    $rozszerzony = $tomorrow;

    try {
        $connection = new mysqli($host, $db_user, $db_password, $db_name);
        if ($connection->connect_errno != 0) {
            throw new Exception(mysqli_connect_errno());
        } else {
            //Does email exist?
            $result = $connection->query("SELECT * FROM bmi WHERE email='$email'");

            if (!$result) throw new Exception($connection->error);

            $howManyEmails = $result->num_rows;

            if ($howManyEmails > 0) {
                $ok = false;
                $_SESSION['echo'] = "Istnieje już taki e-mail.";
                $result = mysqli_fetch_array($result);
                if (!$result[7]) {
                    if ($result[6] < '2038-01-09 03:14:07') {
                        $connection->query(sprintf(
                            "UPDATE bmi SET rozszerzenie = '%s' , promo = '%s' WHERE id = '%s'",
                            mysqli_real_escape_string($connection, $tomorrow),
                            mysqli_real_escape_string($connection, 1),
                            mysqli_real_escape_string($connection, $result[0])
                        ));
                        $_SESSION['echo'] = "Twój dostęp do materiałów został rozszerzony. Możesz zalogować się na swoje konto.";
                    } else {
                        $_SESSION['echo'] = "Posiadasz już pełny dostęp do naszych materiałów.";
                    }
                };
            };

            if ($ok == true) {

                if ($connection->query("INSERT INTO bmi (Imie, Nazwisko, Haslo, email, podstawa, rozszerzenie, promo) VALUES ('$name', '$surname', '$pass_hash', '$email', '$podstawowy', '$rozszerzony', 1)")) {

                    $sender = 'kontakt@atmis.pl';

                    $from  = 'From: ' . $sender . "\r\n";
                    $from .= 'MIME-Version: 1.0' . "\r\n";
                    $from .= 'Content-type: text/html; charset=utf-8' . "\r\n";
                    $title = 'Twoje konto w strefie studenta ATMIS';

                    $message = "Witaj! <br /><br />Twoje konto w strefie studenta ATMIS właśnie zostało utworzone. Znajdziesz tam materiały dodatkowe do naszego kursu.<br />Dane do logowania:<br />e-mail: " . $email . "<br />hasło: " . $pass . "<br />Zaloguj sie pod tym adresem: https://www.atmis.pl/student/<br />Dostęp wygaśnie: " . $tomorrow . "<br /><br /> Pozdrawiamy<br />Zespół ATMIS<br /><br /><br />W razie problemów skontaktuj się z: kontakt@atmis.pl";

                    mail($email, $title, $message, $from);
                    $_SESSION['echo'] = "Dziękujemy! Wysłaliśmy Ci email z danymi do logowania.";
                } else {
                    throw new Exception($connection->error);
                }
            }

            $connection->close();
        }
    } catch (Exception $e) {
        echo 'Błąd serwera! Przepraszamy za niedogodności i prosimy o rejestrację w innym terminie!';
        echo '<br/> Informacja developerska:' . $e;
    }
}

function randomPassword()
{
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
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <title> Uzyskaj darmowy dostęp do materiałów szkoleniowych </title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css?family=Arsenal&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/promo.css" type="text/css" />

    <!--[if lt IE 9]>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.min.js"></script>
    <![endif]-->

    <!-- Facebook Pixel Code -->
    <script>
        ! function(f, b, e, v, n, t, s) {
            if (f.fbq) return;
            n = f.fbq = function() {
                n.callMethod ?
                    n.callMethod.apply(n, arguments) : n.queue.push(arguments)
            };
            if (!f._fbq) f._fbq = n;
            n.push = n;
            n.loaded = !0;
            n.version = '2.0';
            n.queue = [];
            t = b.createElement(e);
            t.async = !0;
            t.src = v;
            s = b.getElementsByTagName(e)[0];
            s.parentNode.insertBefore(t, s)
        }(window, document, 'script',
            'https://connect.facebook.net/en_US/fbevents.js');
        fbq('init', '547141642327192');
        fbq('track', 'PageView');
    </script>
    <noscript>
        <img height="1" width="1" src="https://www.facebook.com/tr?id=547141642327192&ev=PageView
&noscript=1" />
    </noscript>
    <!-- End Facebook Pixel Code -->
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/cookieconsent@3/build/cookieconsent.min.css" />
</head>

<body>

    <div id="container" class="w-100">
        <div id="logo">
            <img src="img/logo.png" alt="logo" height="50px">
        </div>
        <div class="clear"></div>
        <div class="row background">
            <div class="w-100"></div>
            <div id="title" class="col-12" style="text-align: center">
                <h1>Darmowy kurs!</h1>
                <h2>Podaj swój adres email i uzyskaj 24 godzinny dostęp do naszych materiałów szkoleniowych.</h2>
            </div>
            <div class="w-100"></div>
            <div class="col-12" style="text-align: center">
                <form action="promocja" method="post">
                    <input type="email" name="email" placeholder="wpisz swój email..." autofocus required /><br />
                    <input id="consent-checkbox" type="checkbox" name="consent"><label for="consent-checkbox" class="check"><span class="regulations">Oświadczam, że zapoznałem/am się i akceptuję <a href="https://www.atmis.pl/student/polityka-prywatnosci.html">Politykę prywatności</a> oraz wyrażam zgodę na otrzymywanie informacji handlowych za pomocą środków komunikacji elektronicznej.</span>
                    </label><br />
                    <input type="submit" value="Wyślij" />
                </form>

                <?php
                if (isset($_SESSION['echo'])) echo '<p class="echo">' . $_SESSION['echo'] . '</p>';
                unset($_SESSION['echo']);
                ?>
            </div>
        </div>
    </div>


    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>

    <script src="js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/cookieconsent@3/build/cookieconsent.min.js" data-cfasync="false"></script>
    <script>
        window.cookieconsent.initialise({
            "palette": {
                "popup": {
                    "background": "#f37441"
                },
                "button": {
                    "background": "#182a9d"
                }
            },
            "theme": "classic",
            "content": {
                "message": "Nasza strona internetowa używa plików cookies (tzw. ciasteczka) w celach statystycznych, reklamowych oraz funkcjonalnych. Każdy może zaakceptować pliki cookies albo ma możliwość wyłączenia ich w przeglądarce.",
                "dismiss": "Rozumiem",
                "link": "Dowiedz się więcej"
            }
        });
    </script>
</body>

</html>