<?php
require_once "pdo.php";
require_once "TMDB.php";
session_start();

$stmt = $pdo->query("SELECT name, _id FROM favorites");
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Default English language
$tmdb = new TMDb('531eaffcac14a8c431f91d7a77a345e8');

if (isset($_POST['search'])) {

    $_SESSION["search"] = $_POST['search'];

    if (
        strlen($_POST['search']) < 1
        //if any of the fields are not filled in
    ) {
        $_SESSION["error"] = "All fields are required";
        header('Location:index.php'); //redirect to self so refresh doesnt post data again
        return;
    }


    $_SESSION["result"] = $tmdb->searchMovie($_POST['search'], 1, false, 'en');
    header('Location:index.php'); //redirect to self so refresh doesnt post data again
    return;
}

if (isset($_POST['favorite'])) {

    $sql = "INSERT INTO favorites (name) 
    VALUES (:name)";
    //echo ("<pre>\n" . $sql . "\n</pre>\n");
    $stmt = $pdo->prepare($sql);
    $stmt->execute(
        array(
            ':name' => $_POST['favorite']
        )
    );

    header("Location:index.php");
    return;
}

?>

<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!-->
<html class="no-js">
<!--<![endif]-->

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title></title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="clamp.css">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"
        integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

</head>

<body>
    <header>
        <div class="bg-dark collapse" id="navbarHeader" style="">
            <div class="container">
                <div class="row">
                    <div class="col-sm-8 col-md-7 py-4">
                        <h4 class="text-white">About</h4>
                        <p class="text-muted">Add some information about the album below, the author, or any other
                            background context. Make it a few sentences long so folks can pick up some informative
                            tidbits. Then, link them off to some social networking sites or contact information.</p>
                    </div>
                    <div class="col-sm-4 offset-md-1 py-4">
                        <h4 class="text-white">Contact</h4>
                        <ul class="list-unstyled">
                            <li><a href="#" class="text-white">Follow on Twitter</a></li>
                            <li><a href="#" class="text-white">Like on Facebook</a></li>
                            <li><a href="#" class="text-white">Email me</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="navbar navbar-dark bg-dark shadow-sm">
            <div class="container d-flex justify-content-between">
                <a href="#" class="navbar-brand d-flex align-items-center">
                    <svg class="mr-2" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor"
                        xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd"
                            d="M0 1a1 1 0 011-1h14a1 1 0 011 1v14a1 1 0 01-1 1H1a1 1 0 01-1-1V1zm4 0h8v6H4V1zm8 8H4v6h8V9zM1 1h2v2H1V1zm2 3H1v2h2V4zM1 7h2v2H1V7zm2 3H1v2h2v-2zm-2 3h2v2H1v-2zM15 1h-2v2h2V1zm-2 3h2v2h-2V4zm2 3h-2v2h2V7zm-2 3h2v2h-2v-2zm2 3h-2v2h2v-2z"
                            clip-rule="evenodd" />
                    </svg>
                    <strong>TMDB API</strong>
                </a>
                <button class="navbar-toggler collapsed" type="button" data-toggle="collapse"
                    data-target="#navbarHeader" aria-controls="navbarHeader" aria-expanded="false"
                    aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
            </div>
        </div>
    </header>



    <!-- Main jumbotron for a primary marketing message or call to action -->
    <div class="jumbotron">
        <div class="container">
            <h1 class="display-3">Search for movies</h1>
            <p>Find movies and add them to your favorite's</p>
            <form class="form-inline my-2 my-lg-0" method="post">
                <input class="form-control mr-sm-2" type="text" placeholder="Search" aria-label="Search" name="search">
                <button class="btn btn-outline-success my-2 my-sm-0" type="submit" value="Search">Search</button>
            </form>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-8">
                <div class="row">
                    <?php
                    if (isset($_SESSION["result"])) {


                        //$data = json_encode($result, true);
                        $result = $_SESSION["result"];

                        $res = ($result['results']);
                        //$res2 = ($res[0]["title"]);

                        $arrlength = count($res);

                        //var_dump($res[0]);

                        for ($x = 0; $x < $arrlength; $x++) {

                            $poster = $res[$x]["poster_path"];
                            $thisTitle = $res[$x]["title"];
                            //$wh = 'width="300" height="400"';
                            echo '

                        <div class="col-md-4">
                        <div class="card mb-4 shadow-sm">
                            <img class="bd-placeholder-img card-img-top" width="100%" height="100%"
                            src="https://image.tmdb.org/t/p/w500/' . $poster . '"
                                role="img" aria-label="Placeholder: Thumbnail" 
                                alt="image not available">
                                
                                <title>Placeholder</title>
                                <rect width="100%" height="100%" fill="#55595c"></rect><text x="50%" y="50%" fill="#eceeef"
                                    dy=".3em">' . $res[$x]["title"] . '</text>
                            </img>
                            <div class="card-body">
                            <div class="box">
                            <p>' . $res[$x]["overview"] . '</p>                            
                            </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="btn-group">
                                    <form class="form-inline my-2 my-lg-0" method="post">
                                    <input type="hidden" name="favorite" value="'.$thisTitle.'">
                                    <button class="btn btn-outline-success my-2 my-sm-0" type="submit" >Fav\' this</button>
                                </form>
                                    </div>
                                    <small class="text-muted">9 mins</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    ';
                        }


                        //var_dump($res[0]);
                        unset($_SESSION["result"]); //unset for clean refresh

                    } else {
                        echo ''; //echo nothing
                    }
                    ?>
                </div>
            </div>
            <div class="col-4">
                <h3>Favorites:</h3>
                <?php

                    if ($rows == !false) {

                        echo ('<p> <table >' . "\n");


                        foreach ($rows as $row) {
                            echo "<tr><td>";
                            echo (htmlentities($row['name']));
                            echo ("</td><td>");
                            //echo ('|'); //anchor with get param
                            echo (' <a class="btn btn-outline-success my-2 my-sm-0" href="delete.php?_id=' . $row['_id'] . '" >Delete</a>'); //anchor with get param
                            echo ("</td></tr>\n");
                        }
                        echo ('</table>');
                    }



                    ?>
            </div>
        </div>
    </div>











    <footer class="container">
        <p>© Company 2017-2019</p>
    </footer>
 


</body>

</html>

<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"
    integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous">
</script>

<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
    integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous">
</script>

<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"
    integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous">
</script>