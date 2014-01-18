<?php

include_once "inclusions.php";

start_html("Trailers");
make_header(true, "teams");

?>

<?php




//<img src="files/giungla.png" alt="You shall not pass" height="764" width="1200">

/*
<ul>
	<li><p><a href="files/trailer2012.avi">Trailer 2012 [AVI, 22MB]</a> (dialoghi, voci e montaggio di Giovanni Paolini e Denis Nardin)</p></li>
	<li><p><a href="files/trailer2013.avi">Trailer 2013 [AVI, 154MB]</a></p></li>
	<li><p><a href="files/trailer2013-2.mkv">Trailer clandestino 2013 [MKV, 66MB]</a> (sottotitoli e montaggio di Giovanni Mascellani, Fabrizio Bianchi ed Enrico Polesel)</p></li>
</ul>
*/

?>


<?php
start_box("Trailer 2014", "purple", 600);
?>
<iframe width="560" height="315" src="//www.youtube.com/embed/zWSAXOlzBcE?rel=0" frameborder="0" allowfullscreen></iframe>

<?php
end_box();

start_box("Trailer 2013", "blue", 600);
?>
<iframe width="560" height="315" src="//www.youtube.com/embed/e1G09hAuMMA?rel=0" frameborder="0" allowfullscreen></iframe>
<p><a href="files/trailer2013.avi">Scarica il trailer 2013 [AVI, 154MB]</a></p>

<?php
end_box();

start_box("Trailer Clandestino 2013", "blue", 600);
?>
<iframe width="560" height="315" src="//www.youtube.com/embed/OWZrbjebqrE?rel=0" frameborder="0" allowfullscreen></iframe>
<p><a href="files/trailer2013-2.mkv">Scarica il trailer clandestino 2013 [MKV, 66MB]</a> </br>
(sottotitoli e montaggio di Giovanni Mascellani, Fabrizio Bianchi ed Enrico Polesel)</p>



<?php
end_box();

start_box("Trailer 2012", "blue", 600);
?>
<iframe width="560" height="315" src="//www.youtube.com/embed/Y4_oP3aYQs4?rel=0" frameborder="0" allowfullscreen></iframe>
<p><a href="files/trailer2012.avi">Scarica il trailer 2012 [AVI, 22MB]</a> </br>
(dialoghi, voci e montaggio di Giovanni Paolini e Denis Nardin)</p>



<?php
end_box();

/*
<p><b>Giuramento dei Guardiani della Notte</b><br />
<table class="giuramento">
<col width="500">
<tr><td>
<i>"Night gathers, and now my watch begins. It shall not end until the
defeat of the physicists. I am the goalkeeper that guards the score of
the mathematicians. I pledge my life and honor to the Night's Watch,
for this 24-hours tournament and all the 24-hours to come."</i>
</td></tr></table></p>
*/

end_html();

?>
