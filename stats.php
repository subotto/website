<?php
$page_name = $_GET['page_name'];

$pages = array("blue_attacker", "blue_attacker_stats", "blue_defender", "blue_defender_stats", "blue_score", "blue_team", "fake", "general_stats", "player00", "player00_stats", "player01", "player01_stats", "player10", "player10_stats", "player11", "player11_stats", "projection", "red_attacker", "red_attacker_stats", "red_defender", "red_defender_stats", "red_score", "red_team", "score0", "score1", "statistics", "team0", "team1", "time", "countdown");

if(!in_array($page_name, $pages)) {
    header('HTTP/1.0 404 Not Found');
} else {
    echo file_get_contents("stats/$page_name.html");
}

?>
