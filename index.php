<?php
require_once $_GET['location'];//This can be dangerous. what if user
// send important file in location query string

//Where is connection to db??


$sql = 'SELECT * FROM `users` WHERE `city` = "'.$_GET['city'].'"';// This query has sql injection bug
$users = mysql_query($sql); // mysql_query was removed on php >= 7
$usersFromOtherCities = [];
while ($users && ($user = mysql_fetch_assoc($users))) { //// mysql_fetch_assoc was removed on php >= 7
    if (file_exists($user['user_dir'])) {
        echo $_REQUEST['message'].' '.$user['name']."\n";
    } else {
        $usersFromOtherCities[$user['city']] = $user;
    }
}
echo 'Users from other cities:'."\n";
foreach (['Nürnberg', 'Ansbach'] as $city) {
    foreach ($usersFromOtherCities as $users) {
        foreach ($users as $user) {
            if ($user['city'] == $city) {
                echo $city.' ('.count($users).')'."\n";
                break;
            }
        }
    }
}

//------------------------------ rewrite above code -------------------

function connect_to_db(){
    //pdo connection
}

$pdo = connect_to_db();
$query = "SELECT * FROM `users` WHERE `city` = :city";
$sql = $pdo->prepare($query);
$sql->bindValue(":city", $_GET['city']);
$sql->execute();
$users = $sql->fetchAll();

$usersFromOtherCities = [];
foreach ($users as $user) {
    if (file_exists($user['user_dir'])) {
        echo $_REQUEST['message'].' '.$user['name']."\n";
    } else {
        $usersFromOtherCities[$user['city']] = $user;
    }
}
echo 'Users from other cities:'."\n";
foreach (['Nürnberg', 'Ansbach'] as $city) {
    foreach ($usersFromOtherCities as $users) {
        foreach ($users as $user) {
            if ($user['city'] == $city) {
                echo $city.' ('.count($users).')'."\n";
                break;
            }
        }
    }
}
?>
