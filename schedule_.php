<?php

include_once "inclusions.php";

start_html("Turni");
make_header(true, "current");

start_box("Turni della 24 ore 2014");

include("schedule2014.php");
?>

<table id="tabella">
<thead>
<tr><th class='center'>Orario</th><th>Matematici</th><th>Fisici</th></tr>
</thead><tbody>
<?php
foreach($schedule as $sched) {
    echo "<tr><td class='center'>" . $sched['time'] . "</td><td>" . $sched['mathematicians'] . "</td><td>" . $sched['physicists'] . "</td></tr>";
}
?>
</tbody>
</table>


<?php

end_box();
end_html();

?>
