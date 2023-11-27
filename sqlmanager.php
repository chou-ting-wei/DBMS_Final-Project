<?php
    error_reporting(E_ERROR | E_WARNING);
    
    include("classes/player.php");
    include("classes/team.php");
    include("classes/forum.php");
    include("classes/vote.php");

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

    function getPlayerInfo($PID){
        $connection = initDB();
        $query = NULL;

        // PID = 0 -> return all player
        if($PID == 0){
            $query = "SELECT * FROM Players";
        }
        else{
            $query = "SELECT * FROM Players WHERE PID='".$PID."'";
        }

        $result = mysqli_query($connection, $query);

        $playerData = NULL;
        $playerID = 0;
        
        while($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){   
            $PID = $row['PID'];
            $PName = $row['PName'];

            // 這些是原本書裡給的
            // $SourceSID = $row['SourceSID'];
            // $DestSID = $row['DestSID'];
            
            // // 取得出發地點的航點資訊
            // $query2 = "SELECT * FROM Sectors WHERE SID='".$SourceSID."'";
            // $result2 = mysqli_query($connection,$query2);         
            // $row2 = mysqli_fetch_array($result2,MYSQLI_ASSOC);
            // $source = $row2['Sector'];

            // // 取得目的地點的航點資訊 
            // $query3 = "SELECT * FROM Sectors WHERE SID='".$DestSID."'";
            // $result3 = mysqli_query($connection, $query3);             
            // $row3 = mysqli_fetch_array($result3,MYSQLI_ASSOC);
            // $dest= $row3['Sector'];

            $player = new Player();        
            $player->set_PID($PID);
            $player->set_PName($PName);

            $playerData[$playerID] = $player;
            $playerID = $playerID +1;              
        }

        closeDB($connection);
        return $playerData;
    }
?>