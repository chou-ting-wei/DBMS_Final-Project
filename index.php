<!DOCTYPE html>
<?php
    error_reporting(E_ERROR | E_WARNING);
    session_start();
    if(!isset($_SESSION["login_session"])){
        $_SESSION["login_session"] = false;
        $_SESSION["username"] = NULL;
    }
?>
<html>
    <head>
        <meta charset="UTF=8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>NBA Stat</title>
        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    </head>
    <style>
        body{
            background-color: #e9eded;
        }
        .unselectable {
            -webkit-user-select: none;
            -webkit-touch-callout: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }
    </style>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand unselectable">NBA Stat</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-lg-end" id="navbarSupportedContent">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="#">Home</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle active" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">Menu</a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="playerstat.php">Player Stat</a></li>
                            <li><a class="dropdown-item" href="teamstat.php">Team Stat</a></li>
                            <li><a class="dropdown-item" href="forum.php">Forum</a></li>
                            <li><a class="dropdown-item" href="vote.php">Vote</a></li>
                        </ul>   
                    </li>
                    <?php
                        if(!$_SESSION["login_session"]){
                            echo "<li class='nav-item'>";
                            echo "<a class='nav-link active' href='login.php'>Login</a>";
                            echo "</li>";
                        }
                        else{
                            if(isset($_SESSION['username'])){
                                $username = $_SESSION['username'];
                                if($username == "admin"){
                                    echo "<li class='nav-item'>";
                                    echo "<a class='nav-link active' href='admin.php'>".$username."</a>";
                                    echo "</li>";
                                }
                                else{
                                    echo "<li class='nav-item'>";
                                    echo "<a class='nav-link active' href='editpw.php'>".$username."</a>";
                                    echo "</li>";
                                }
                            }
                            echo "<li class='nav-item'>";
                            echo "<a class='nav-link active' href='logout.php'>Logout</a>";
                            echo "</li>";
                        }
                    ?>
                </ul>
            </div>
        </div>
    </nav>
    <body>
        <div class="container">
            <div id="carouselSlides" class="carousel carousel-dark slide" data-bs-ride="carousel" data-bs-interval="6000">

            <div class="carousel-indicators">
            <button type="button" data-bs-target="#carouselSlides" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
            <button type="button" data-bs-target="#carouselSlides" data-bs-slide-to="1" aria-label="Slide 2"></button>
            <button type="button" data-bs-target="#carouselSlides" data-bs-slide-to="2" aria-label="Slide 3"></button>
            <button type="button" data-bs-target="#carouselSlides" data-bs-slide-to="3" aria-label="Slide 4"></button>
            </div>

            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img src="images/carousel/s1.png" class="d-block w-100" alt="s1">
                    <div class="carousel-caption d-none d-md-block bg-body bg-opacity-75">
                    <h5>NBA Opening Night</h5>
                    <p>NBA Opening Night marks the beginning of the regular season. 
                    It serve as a showcase for the league, featuring top-tier talent and setting the tone for the season ahead. </p>
                    </div>
                </div>
                <div class="carousel-item">
                    <img src="images/carousel/s2.png" class="d-block w-100" alt="s2">
                    <div class="carousel-caption d-none d-md-block bg-body bg-opacity-75">
                    <h5>NBA Christmas Day</h5>
                    <p>NBA Christmas Day is an annual tradition on December 25th. 
                    It has become a highly anticipated event for basketball fans, offering a day filled with competitive and entertaining games featuring some of the league's top teams and players.</p>
                    </div>
                </div>
                <div class="carousel-item">
                    <img src="images/carousel/s3.png" class="d-block w-100" alt="s3">
                    <div class="carousel-caption d-none d-md-block bg-body bg-opacity-75">
                    <h5>NBA All-Star Game</h5>
                    <p>The NBA All-Star Game is an annual exhibition basketball game held during the NBA All-Star Weekend. 
                    It features the league's top players, usually divided into teams representing the Eastern and Western Conferences.</p>
                    </div>
                </div>
                <div class="carousel-item">
                    <img src="images/carousel/s4.png" class="d-block w-100" alt="s4">
                    <div class="carousel-caption d-none d-md-block bg-body bg-opacity-75">
                    <h5>NBA Finals</h5>
                    <p>The NBA Finals is the culmination of the NBA season, typically take place in June and involve the Eastern Conference champion facing off against the Western Conference champion. </p>
                    </div>
                </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselSlides" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselSlides" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
            </button>
            </div>
        </div>

        <!-- Bootstrap JavaScript Bundle with Popper -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    </body>
</html>