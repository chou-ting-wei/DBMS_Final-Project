<?php
    error_reporting(E_ERROR | E_WARNING);
    date_default_timezone_set("Asia/Taipei");
    
    include("classes/player.php");
    include("classes/team.php");
    include("classes/forum.php");
    include("classes/vote.php");
    include("classes/user.php");

    $databaseURL;
    $databaseUName;
    $databasePWord;
    $databaseName; 

    function initDB(){
        if(!isset($_SESSION["databaseURL"])){
            include("conf/conf.php");
            $dbConf = new Conf();
            $databaseURL = $dbConf->get_databaseURL();
            $databaseUName = $dbConf->get_databaseUName();
            $databasePWord = $dbConf->get_databasePWord();
            $databaseName = $dbConf->get_databaseName();

            $_SESSION['databaseURL'] = $databaseURL; 
            $_SESSION['databaseUName'] = $databaseUName; 
            $_SESSION['databasePWord'] = $databasePWord; 
            $_SESSION['databaseName'] = $databaseName;        
                
            $connection = mysqli_connect($databaseURL,$databaseUName, $databasePWord,$databaseName) or die ("Error: MySQL connection failed!");
        
            mysqli_close($connection);
        }

        $databaseURL = $_SESSION['databaseURL'];
        $databaseUName = $_SESSION['databaseUName'];
        $databasePWord = $_SESSION['databasePWord'];
        $databaseName = $_SESSION['databaseName']; 

        $connection = mysqli_connect($databaseURL,$databaseUName, $databasePWord,$databaseName) or die ("Error: MySQL connection failed!");
        
        mysqli_query($connection,'SET CHARACTER SET utf8');
        mysqli_query($connection,"SET collation_connection = 'utf8_general_ci'");

        return $connection;
    }
    
    function closeDB($connection){
        mysqli_close($connection);
    }

    function chkLogin($username, $password){
        $ret = false;

        $connection = initDB();
        $query = "SELECT * FROM User WHERE password='$password' AND username='$username'";
        $result = mysqli_query($connection, $query);
        if(mysqli_num_rows($result) > 0){
            $ret = true;
        }

        closeDB($connection);
        return $ret;
    }

    function addRegister($username, $password){
        $ret = false;

        $connection = initDB();
        // 確認是否存在 username
        $query = "SELECT * FROM User WHERE username='$username'";
        $result = mysqli_query($connection, $query);
        if(mysqli_num_rows($result) > 0){
            return $ret;
        }

        // 加入此 username password
        $query2 = "INSERT INTO User Values('$username','$password')";
        $result2 = mysqli_query($connection, $query2);

        closeDB($connection);
        return $result2;
    }

    function getUserList($name){
        $connection = initDB();

        $query = "SELECT * FROM User WHERE username LIKE '%$name%' ORDER BY username";
        $result = mysqli_query($connection, $query);

        $userData = NULL;
        $userID = 0;

        while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){            
            $user = new User();
            $user->set_User($row);
            $userData[$userID] = $user;
            $userID = $userID + 1;
        }

        closeDB($connection);
        return $userData;
    }

    function delUser($username){
        $connection = initDB();
        $query="DELETE FROM User WHERE username='$username'";
        $b=mysqli_query($connection, $query);
        closeDB($connection);
        return $b;
    }

    function editPassword($username, $password){
        $ret = false;
        $connection = initDB();
        $query = "SELECT * FROM User WHERE username='$username'";
        $result = mysqli_query($connection, $query);
        if(mysqli_num_rows($result)==1){
            $query2="UPDATE User SET User.password='$password' WHERE username='$username'";
            $result2 = mysqli_query($connection, $query2);
            closeDB($connection);
            return $result2;
        }
        else{
            closeDB($connection);
            return $ret;
        }
    }

    function getPlayerInfo($PName,$TName,$year,$mode){
        $connection = initDB();
        $playerData = NULL;
        
        if ($mode==1){ //player_info
            $query = "SELECT * FROM team_abbrev ta, player_info pl WHERE ta.tid=pl.tid AND pl.name LIKE '%$PName%' AND ta.abbrev LIKE '%$TName%' AND ta.year LIKE '%$year%' ORDER BY ta.year DESC, pl.name";
            $result = mysqli_query($connection, $query);
            $playerID = 0;
            while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){            
                $player = new player();
                $player->setPlayer_1($row);
                $playerData[$playerID] = $player;
                $playerID = $playerID + 1;
            }
        }
        else if ($mode==2){ //player_basic
            $query = "SELECT * FROM team_abbrev ta, player_info pl, player_basic pb WHERE ta.tid=pl.tid AND pl.pid=pb.pid AND pl.tid=pb.tid AND pl.name LIKE '%$PName%' AND ta.abbrev LIKE '%$TName%' AND ta.year LIKE '%$year%' ORDER BY ta.year DESC, pl.name";
            $result = mysqli_query($connection, $query);
            $playerID = 0;
            while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){            
                $player = new player();
                $player->setPlayer_2($row);
                $playerData[$playerID] = $player;
                $playerID = $playerID + 1;
            }
        }
        else if ($mode==3){ //player_shooting
            $query = "SELECT * FROM team_abbrev ta, player_info pl, player_shooting ps WHERE ta.tid=pl.tid AND pl.pid=ps.pid AND pl.tid=ps.tid AND pl.name LIKE '%$PName%' AND ta.abbrev LIKE '%$TName%' AND ta.year LIKE '%$year%' ORDER BY ta.year DESC, pl.name";
            $result = mysqli_query($connection, $query);
            $playerID = 0;
            while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){            
                $player = new player();
                $player->setPlayer_3($row);
                $playerData[$playerID] = $player;
                $playerID = $playerID + 1;
            }
        }
        closeDB($connection);
        return $playerData;
    }

    function getTeamInfo($TName,$year,$mode,$playoff){
        $connection = initDB();
        $teamData = NULL;
        if ($mode==1){
            $query = "SELECT * FROM team_abbrev ta, team_total tt WHERE ta.tid=tt.tid and ta.abbrev LIKE '%$TName%' and ta.year LIKE '%$year%' and ta.playoff LIKE '%$playoff%' ORDER BY ta.year DESC, ta.team";
            $result = mysqli_query($connection, $query);
            $teamID = 0;
            while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){            
                $team = new team();
                $team->setTeam_1($row);
                $teamData[$teamID] = $team;
                $teamID = $teamID + 1;
            }
        }
        else{
            $query = "SELECT * FROM team_abbrev ta, team_per_game tpg WHERE ta.tid=tpg.tid and ta.abbrev LIKE '%$TName%' and ta.year LIKE '%$year%' and ta.playoff LIKE '%$playoff%' ORDER BY ta.year DESC, ta.team";
            $result = mysqli_query($connection, $query);
            $teamID = 0;
            while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){            
                $team = new team();
                $team->setTeam_2_3_4($row);
                $teamData[$teamID] = $team;
                $teamID = $teamID + 1;
            }
        }
        closeDB($connection);
        return $teamData;
    }

    function nameToAbbrev($name){
        $connection = initDB();
        $query = "SELECT abbrev FROM team_abbrev ta WHERE ta.team='$name' LIMIT 1";
        $result = mysqli_query($connection, $query);
        if (mysqli_num_rows($result) > 0){
            $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
            return $row["abbrev"];
        }
        else{
            return false;
        }
    }

    function getForumList($FTitle){
        $connection = initDB();

        $query = "SELECT * FROM Forum WHERE title LIKE '%$FTitle%' ORDER BY post_time DESC";
        $result = mysqli_query($connection, $query);

        $forumData = NULL;
        $forumID = 0;

        while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){            
            $forum = new Forum();
            $forum->set_Forum($row);
            $forumData[$forumID] = $forum;
            $forumID = $forumID + 1;
        }

        closeDB($connection);
        return $forumData;
    }

    function addForum($FTitle, $FText, $username){
        $connection = initDB();
        $time=date("Y/m/d H:i:s");
        $query="INSERT INTO Forum Values('$FTitle','$FText','$username','$time')";
        $b=mysqli_query($connection, $query);
        closeDB($connection);
        return $b;
    }

    function delForum($FTitle){
        $connection = initDB();
        $query="DELETE FROM Forum WHERE title='$FTitle'";
        $b=mysqli_query($connection, $query);
        closeDB($connection);
        return $b;
    }

    function getVoteList($VTitle){
        $connection = initDB();

        $query = "SELECT * FROM Vote WHERE title LIKE '%$VTitle%' ORDER BY post_time DESC";
        $result = mysqli_query($connection, $query);

        $voteData = NULL;
        $voteID = 0;

        while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){ 
            $vote = new Vote();
            $vote->set_Vote($row);
            $voteData[$voteID] = $vote;
            $voteID = $voteID + 1;
        }

        closeDB($connection);
        return $voteData;
    }
    
    function addVote($VTitle, $username){
        $connection = initDB();
        $time=date("Y/m/d H:i:s");
        $query="INSERT INTO Vote Values('$VTitle', 0, 0, '$time','$username')";
        $b=mysqli_query($connection, $query);
        closeDB($connection);
        return $b;
    }

    function Vote($VTitle, $username, $side){
        $connection = initDB();
        $query="SELECT * FROM Voted WHERE title='$VTitle' and username='$username'";
        $result=mysqli_query($connection, $query);
        if(mysqli_num_rows($result) > 0){
            return false;
        }

        if ($side==1){
            $query="UPDATE Vote SET vote_1=vote_1+1 WHERE title='$VTitle'";
        }
        else{
            $query="UPDATE Vote SET vote_2=vote_2+1 WHERE title='$VTitle'";
        }
        $update="INSERT INTO Voted Values('$VTitle','$username')";
        $a=mysqli_query($connection,$update);
        $b=mysqli_query($connection, $query);

        closeDB($connection);
        return ($b && $a);
    }

    function delVote($VTitle){
        $connection = initDB();
        $query="DELETE FROM Vote WHERE title='$VTitle'";    
        $b=mysqli_query($connection, $query);
        $query="DELETE FROM Voted WHERE title='$VTitle'";
        $a=mysqli_query($connection, $query);
        closeDB($connection);
        return ($b && $a);
    }
?>