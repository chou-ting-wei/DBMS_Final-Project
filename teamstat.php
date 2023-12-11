<!DOCTYPE html>
<?php
    error_reporting(E_ERROR | E_WARNING);
    session_start();
    if(!isset($_SESSION["login_session"])){
        $_SESSION["login_session"] = false;
        $_SESSION["username"] = NULL;
    }
    if(!isset($_SESSION["team_info"])){
        $_SESSION["team_info"] = NULL;
    }
    include("sqlmanager.php")
?>
<html>
    <head>
        <meta charset="UTF=8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>NBA Stat - Team Stat</title>
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
                        <a class="nav-link active" aria-current="page" href="index.php">Home</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle active" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">Menu</a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="playerstat.php">Player Stat</a></li>
                            <li><a class="dropdown-item" href="#">Team Stat</a></li>
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
                                    echo "<a class='nav-link active'>".$username."</a>";
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
        <div class="container mt-4">
            <h3 class="fw-bolder">Team Stat</h3>
            <hr class="mt-3 mb-3"></hr>
            <?php
                $voteData = getVoteList("");
                $voteCnt = 0;
                if($voteData != NULL){
                    $voteCnt = count($voteData);
                }
                if($voteCnt > 0){
                    for($index = 0; $index < $voteCnt; $index ++){
                        $vote = $voteData[$index]->get_all();
                        // echo "<pre>";
                        // print_r($vote);
                        // echo "</pre>";
                        echo "<tr>";
                        echo "<td class='align-middle'><a href='#' data-bs-toggle='modal' data-bs-target='#voteModalIdx".$index."'>".$vote[0]."</a></td>";
                        // echo "<td class='align-middle'>".$vote[0]."</td>";
                        echo "<td class='align-middle text-center'>".$vote[1]."</td>";
                        echo "<td class='align-middle'>";
                        echo "<div class='progress'>";
                        if($vote[1] + $vote[2] == 0){
                            echo "<div class='progress-bar' role='progressbar' aria-valuenow='0' aria-valuemin='0' aria-valuemax='100'></div>";
                        }
                        else{
                            $tmp = round($vote[1] * 100 / ($vote[1] + $vote[2]));
                            echo "<div class='progress-bar' role='progressbar' style='width: ".$tmp."%' aria-valuenow='".$tmp."' aria-valuemin='0' aria-valuemax='100'></div>";
                            echo "<div class='progress-bar bg-danger' role='progressbar' style='width: ".(100 - $tmp)."%' aria-valuenow='".(100 - $tmp)."' aria-valuemin='0' aria-valuemax='100'></div>";
                        }
                        echo "</div>";
                        echo "</td>";
                        echo "<td class='align-middle text-center'>".$vote[2]."</td>";
                        echo "<td class='align-middle'>".$vote[4]."</td>";
                        echo "<td class='align-middle'>".$vote[3]."</td>";
                        echo "<td class='align-middle'>";
                        if(isset($_SESSION['username'])){
                            $username = $_SESSION['username'];
                            if($username == "admin" || $username = $vote[4]){
                                echo "<button class='btn btn-danger' type='button' onclick=\"_delVote('".$vote[0]."')\">";
                                echo "<svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-trash' viewBox='0 0 16 16'>";
                                echo "<path d='M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z'/>";
                                echo "<path d='M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z'/>";
                                echo "</svg>";
                                echo "</button>";
                            }
                        }
                        echo "</td>";
                        echo "</tr>";
                    }
                }
                else{
                    echo "<tr><td class='align-middle' colspan='7'><span class='text-danger mb-3'>No data found.</span></td></tr>";
                }
                echo "</tbody></table></div></div>";
            ?>
        </div>

        <!-- Bootstrap JavaScript Bundle with Popper -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    </body>
</html>