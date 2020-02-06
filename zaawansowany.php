<?php
session_start();

if (!isset($_SESSION['signed'])) {
    header('Location: index');
    exit();
}

if (isset($_GET['page'])) {
    $page = $_GET['page'];
} else {
    $page = 1;
}
$no_of_records_per_page = 6;
$offset = ($page - 1) * $no_of_records_per_page;

$date = date('Y-m-d h:i:s');

if ($date <= $_SESSION['rozszerzenie']) {
    require_once "connect.php";

    $connection = mysqli_connect($host, $db_user, $db_password, $db_name);
    if (mysqli_connect_errno()) {
        echo "Error:" . mysqli_connect_error();
        die();
    }
    $connection->query('SET NAMES utf8');
    $connection->query('SET CHARACTER_SET utf8_unicode_ci');

    $total_pages_sql = "SELECT COUNT(*) FROM rozszerzenie";
    $result = mysqli_query($connection, $total_pages_sql);
    $total_rows = mysqli_fetch_array($result)[0];
    $total_pages = ceil($total_rows / $no_of_records_per_page);
}
?>
<!DOCTYPE HTML>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <title> Panel </title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/stylee.css" type="text/css" />

    <!--[if lt IE 9]>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.min.js"></script>
    <![endif]-->
</head>
<body>
<?php include("top-nav.php");?>
    <div id="content">
        <div class="container-fluid">
            <div class="row">
                <?php
                if ($date <= $_SESSION['rozszerzenie']) {
                    $sql = "SELECT * FROM rozszerzenie LIMIT $offset, $no_of_records_per_page";
                    $data = mysqli_query($connection, $sql);
                    while ($row = mysqli_fetch_array($data)) {
                        echo '<div class="col-sm-12 col-md-6 col-lg-4"><figure><iframe src="https://www.youtube.com/embed/' . $row[2] . '" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe><figcaption class="figure-caption">' . $row[1] . '</figcaption></figure></div>';
                    }
                    mysqli_close($connection);
                } else echo '<h3>Nie masz dostępu do tej części materiałów. Zapraszamy na inne nasze kursy :)</h3>'
                ?>
            </div>
        </div>
    </div>

    <nav aria-label="page navigation">
            <ul class="pagination justify-content-center">
                <li class="page-item <?php if ($page <= 1) {
                                            echo 'disabled';
                                        } ?>">
                    <a class="page-link" href="<?php if ($page <= 1) {
                                                    echo '#';
                                                } else {
                                                    echo "?page=" . ($page - 1);
                                                } ?>">Poprzednie</a>
                </li>
                <?php
                if ($date <= $_SESSION['rozszerzenie']) {
                for ($i = 1; $i <= $total_pages; $i++) {
                    echo '<li class="page-item' . ($page == $i ? ' active' : '') . '"><a class="page-link" href="?page=' . $i . '">' . $i . '</a></li>';
                }}
                ?>
                <li class="page-item <?php if ($page >= $total_pages) {
                                            echo 'disabled';
                                        } ?>">
                    <a class="page-link" href="<?php if ($page >= $total_pages) {
                                                    echo '#';
                                                } else {
                                                    echo "?page=" . ($page + 1);
                                                } ?>">Następne</a>
                </li>

            </ul>
        </nav>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>

    <script src="js/bootstrap.min.js"></script>


</body>

</html>