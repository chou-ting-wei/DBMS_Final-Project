<!DOCTYPE html>
<?php
    error_reporting(E_ERROR | E_WARNING);
    session_start();
    if(!isset($_SESSION["login_session"])){
        $_SESSION["login_session"] = false;
        $_SESSION["username"] = NULL;
    }
    include("sqlmanager.php");
?>
<html>
    <head>
        <meta charset="UTF=8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>NBA Stat - Forum</title>
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
        .w-5{
            width: 5%;
        }
        .w-20{
            width: 20%;
        }
        .w-55{
            width: 55%;
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
                            <li><a class="dropdown-item" href="teamstat.php">Team Stat</a></li>
                            <li><a class="dropdown-item" href="#">Forum</a></li>
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
        <script>
            <?php
                $search = "";
                if(isset($_COOKIE["searchFTitle"])){
                    $search = $_COOKIE["searchFTitle"];
                    echo "document.cookie = 'searchFTitle=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=forum.php;';";
                }
            ?>
            function _searchForum() {
                var searchFTitle = document.getElementById('search').value;
    
                if(searchFTitle){
                    document.cookie = "searchFTitle=" + searchFTitle;
                }
                window.location.reload();
            }
        </script>
        <div class="container mt-4">
            <h3 class="fw-bolder">Forum</h3>
            <hr class="mt-3 mb-3"></hr>

            <div class="container">
                <div class="row">
                    <div class="col-md-3 me-auto">
                        <div class="input-group">
                            <input type="text" class="form-control" id="search" name="search" placeholder="Search" value="<?php echo isset($_COOKIE["searchFTitle"]) ? $_COOKIE["searchFTitle"] : '' ?>">
                            <button class="btn btn-secondary" type="button" id="searchBtn" onclick="_searchForum()">
                            &nbsp;
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                                <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0"/>
                            </svg>
                            &nbsp;
                            </button>
                        </div>
                    </div>
                    <div class="col-auto">
                        <button class="btn btn-secondary" type="button" href="#" data-bs-toggle="modal" data-bs-target="#forumModal" <?php echo $_SESSION["username"] == NULL ? 'disabled' : ''; ?>>
                            &nbsp;
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-lg" viewBox="0 0 16 16">
                                <path fill-rule="evenodd" d="M8 2a.5.5 0 0 1 .5.5v5h5a.5.5 0 0 1 0 1h-5v5a.5.5 0 0 1-1 0v-5h-5a.5.5 0 0 1 0-1h5v-5A.5.5 0 0 1 8 2"/>
                            </svg>
                            &nbsp;
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <script>
            <?php
                if(isset($_COOKIE["delFTitle"])){
                    if(delForum($_COOKIE["delFTitle"])){
                        echo "document.cookie = 'delFTitle=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=forum.php;';";
                        echo "alert('Delete forum successful!');";
                        echo "window.location.reload();";
                    }
                    else{
                        echo "alert('Delete forum failed!');";
                    }
                }
            ?>
            function _delForum(FTitle) {
                if(FTitle){
                    document.cookie = "delFTitle=" + FTitle;
                    window.location.reload();
                }
                else{
                    alert('Delete forum failed! (ERR: Title undefined)');
                }
            }
        </script>
        <div class="container mt-3">
            <?php
                echo "<div class='table-responsive'>";
                echo "<table class='table table-borded table-hover'>";
                echo "<thead><tr>";
                echo "<th scope='col' class='w-55 align-middle'>Title</th>";
                echo "<th scope='col' class='w-20 align-middle'>Author</th>";
                echo "<th scope='col' class='w-20 align-middle'>Time</th>";
                echo "<th scope='col' class='w-5 align-middle'></th>";
                echo "</tr></thead>";
                echo "<tbody>";
                if($search != ""){
                    $forumData = getForumList($search);
                    $forumCnt = 0;
                    if($forumData != NULL){
                        $forumCnt = count($forumData);
                    }
                    if($forumCnt > 0){
                        for($index = 0; $index < $forumCnt; $index ++){
                            $forum = $forumData[$index]->get_all();
                            echo "<tr>";
                            echo "<td class='align-middle'><a href='#' data-bs-toggle='modal' data-bs-target='#forumModalIdx".$index."'>".$forum[0]."</a></td>";
                            // echo "<td class='align-middle'>".$forum[0]."</td>";
                            echo "<td class='align-middle'>".$forum[2]."</td>";
                            echo "<td class='align-middle'>".$forum[3]."</td>";
                            echo "<td class='align-middle'>";
                            if(isset($_SESSION['username'])){
                                $username = $_SESSION['username'];
                                if($username == "admin" || $username == $forum[2]){
                                    echo "<button class='btn btn-danger' type='button' onclick=\"_delForum('".$forum[0]."')\">";
                                    echo "<svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-trash' viewBox='0 0 16 16'>";
                                    echo "<path d='M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z'/>";
                                    echo "<path d='M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z'/>";
                                    echo "</svg>";
                                    echo "</button>";
                                }
                            }
                            echo "</td>";
                            echo "</tr>";
                            echo "<div class='modal fade' id='forumModalIdx".$index."' tabindex='-1' data-bs-backdrop='static' data-bs-keyboard='false' aria-labelledby='forumModalLabel' aria-hidden='true'>";
                            echo "<div class='modal-dialog modal-lg'>";
                            echo "<div class='modal-content'>";
                            echo "<div class='modal-header'>";
                            echo "<h5 class='modal-title' id='forumModalIdx".$index."Label'>".$forum[0]."</h5>";
                            echo "<button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>";
                            echo "</div>";
                            echo "<div class='modal-body'>";
                            echo "<div class='mb-3' style='word-break:break-all'>".$forum[1]."</div>";
                            echo "</div>";
                            echo "<div class='modal-footer text-secondary'>".$forum[2]."</div>";
                            echo "</div></div></div>";
                        }
                    }
                    else{
                        echo "<tr><td class='align-middle' colspan='4'><span class='text-danger mb-3'>No result found.</span></td></tr>";
                    }
                    echo "</tbody></table></div></div>";
                }
                else{
                    $forumData = getForumList("");
                    $forumCnt = 0;
                    if($forumData != NULL){
                        $forumCnt = count($forumData);
                    }
                    if($forumCnt > 0){
                        for($index = 0; $index < $forumCnt; $index ++){
                            $forum = $forumData[$index]->get_all();
                            // echo "<pre>";
                            // print_r($forum);
                            // echo "</pre>";
                            echo "<tr>";
                            echo "<td class='align-middle'><a href='#' data-bs-toggle='modal' data-bs-target='#forumModalIdx".$index."'>".$forum[0]."</a></td>";
                            // echo "<td class='align-middle'>".$forum[0]."</td>";
                            echo "<td class='align-middle'>".$forum[2]."</td>";
                            echo "<td class='align-middle'>".$forum[3]."</td>";
                            echo "<td class='align-middle'>";
                            if(isset($_SESSION['username'])){
                                $username = $_SESSION['username'];
                                if($username == "admin" || $username == $forum[2]){
                                    echo "<button class='btn btn-danger' type='button' onclick=\"_delForum('".$forum[0]."')\">";
                                    echo "<svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-trash' viewBox='0 0 16 16'>";
                                    echo "<path d='M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z'/>";
                                    echo "<path d='M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z'/>";
                                    echo "</svg>";
                                    echo "</button>";
                                }
                            }
                            echo "</td>";
                            echo "</tr>";
                            echo "<div class='modal fade' id='forumModalIdx".$index."' tabindex='-1' data-bs-backdrop='static' data-bs-keyboard='false' aria-labelledby='forumModalLabel' aria-hidden='true'>";
                            echo "<div class='modal-dialog modal-lg'>";
                            echo "<div class='modal-content'>";
                            echo "<div class='modal-header'>";
                            echo "<h5 class='modal-title' id='forumModalIdx".$index."Label'>".$forum[0]."</h5>";
                            echo "<button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>";
                            echo "</div>";
                            echo "<div class='modal-body'>";
                            echo "<div class='mb-3' style='word-break:break-all'>".$forum[1]."</div>";
                            echo "</div>";
                            echo "<div class='modal-footer text-secondary'>".$forum[2]."</div>";
                            echo "</div></div></div>";
                        }
                    }
                    else{
                        echo "<tr><td class='align-middle' colspan='4'><span class='text-danger mb-3'>No data found.</span></td></tr>";
                    }
                }
                echo "</tbody></table></div></div>"
            ?>
        </div>
        <script>
            <?php
                if(isset($_COOKIE["addFTitle"]) && isset($_COOKIE["addFText"])){
                    if(addForum($_COOKIE["addFTitle"], $_COOKIE["addFText"], $_SESSION["username"])){
                        echo "document.cookie = 'addFTitle=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=forum.php;';";
                        echo "document.cookie = 'addFText=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=forum.php;';";
                        echo "alert('Add forum successful!');";
                        echo "window.location.reload();";
                    }
                    else{
                        echo "alert('Add forum failed!');";
                    }
                }
            ?>
            function _addForum() {
                var FTitle = document.getElementById('FTitle').value;
                var FText = document.getElementById('FText').value;
    
                if(FTitle && FText){
                    if(FTitle.length > 20){
                        alert('Add forum failed! (ERR: The length of Title is greater than 20.)');
                    }
                    else if(FText.length > 500){
                        alert('Add forum failed! (ERR: The length of Content is greater than 500.)');
                    }
                    else{
                        document.cookie = "addFTitle=" + FTitle; path="forum.php";
                        document.cookie = "addFText=" + FText; path="forum.php";
                        window.location.reload();
                    }
                }
                else{
                    alert('Add forum failed! Please fill in all fields.');
                }
            }
        </script>
        <div class="modal fade" id="forumModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" aria-labelledby="forumModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="forumModalLabel">New Forum</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="FTitle" class="form-label">Title</label>
                            <input type="text" name="title" class="form-control" id="FTitle">
                        </div>
                        <div class="mb-3">
                            <label for="FText" class="form-label">Content</label>
                            <textarea class="form-control" id="FText" rows="4"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" id="submitBtn" onclick="_addForum()">Submit</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bootstrap JavaScript Bundle with Popper -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    </body>
</html>