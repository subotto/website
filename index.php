<?php

include_once "inclusions.php";

start_html("24 ore");
make_header();

start_box("Edizioni della 24 ore");


// <p><div id="countdown">La quarta 24 ore si terr&agrave; tra l'11 e il 12 febbraio 2013.</div></p>


//make_title("Memoria storica",2);

?>

<table id="tabella" class="vcenter">
<thead>
<tr> <th class="center">Edizione</th> <th class="center">Data</th> <th class="center">Matematici</th> <th colspan="2" class="center">Risultato</th> <th class="center">Fisici</th> <th class="center">Luogo</th> </tr>
</thead><tbody>
	<tr>
		<td class="center">VI</td>
		<td class="center">19-20 febbraio 2015</td>
		<td>Capitano: Federico Scavia<br />
			Vicecapitano: Lorenzo Benedini<br />
			Partecipanti ufficiali: N/D</td>
		<td class="center">--</td>
		<td class="center">--</td>
		<td>Capitano: N/D<br />
			Vicecapitano: N/D<br />
			Partecipanti ufficiali: N/D</td>
		<td class="center">Collegio Carducci</td>
	</tr>

	<tr>
		<td class="center">V</td>
		<td class="center">28-29 gennaio 2014</td>
		<td>Capitano: Alessandro Iraci<br />
			Vicecapitano: Federico Scavia<br />
			Partecipanti ufficiali: 69</td>
		<td class="center"><b>1511</b></td>
		<td class="center">1377</td>
		<td>Capitano: Giulio Mandorli<br />
			Vicecapitano: Marco Cilibrasi<br />
			Partecipanti ufficiali: 45</td>
		<td class="center">Collegio Carducci</td>
	</tr>
	
	<tr>
		<td class="center">IV</td>
		<td class="center">11-12 febbraio 2013</td>
		<td>Capitano: Giovanni Paolini<br />
			Vicecapitano: Alessandro Iraci<br />
			Partecipanti ufficiali: 53</td>
		<td class="center"><b>1519</b></td>
		<td class="center">1458</td>
		<td>Capitano: Luca Rigovacca<br />
			Vicecapitano: Giulio Mandorli<br />
			Partecipanti ufficiali: 43</td>
		<td class="center">Collegio Carducci</td>
	</tr>
	
	<tr>
		<td class="center">III</td>
		<td class="center">14-15 febbraio 2012</td>
		<td>Capitano: Giovanni Paolini<br />
			Vicecapitano: Giulio Bresciani<br />
			Partecipanti ufficiali: 52</td>
		<td class="center"><b>1865</b></td>
		<td class="center">1442</td>
		<td>Capitano: Luca Rigovacca<br />
			Vicecapitano: Stefano Bolzonella<br />
			Partecipanti ufficiali: 54</td>
		<td class="center">Collegio Carducci</td>
	</tr>
	
	<tr>
		<td class="center">II</td>
		<td class="center">4-5 aprile 2011</td>
		<td>Capitano: Fabrizio Bianchi<br />
			Vicecapitano: Gennady Uraltsev<br />
			Partecipanti ufficiali: 39</td>
		<td class="center"><b>1658</b></td>
		<td class="center">1398</td>
		<td>Capitano: Stefano Bolzonella<br />
			Vicecapitano: Enrico Morgante<br />
			Partecipanti ufficiali: 37</td>
		<td class="center">Collegio Timpano</td>
	</tr>
	
	<tr>
		<td class="center">I</td>
		<td class="center">3-4 marzo 2010</td>
		<td>Capitano: Fabrizio Bianchi<br />
			Vicecapitano: Gennady Uraltsev<br />
			Partecipanti ufficiali: 27</td>
		<td class="center"><b>1636</b></td>
		<td class="center">1616</td>
		<td>Capitano: Stefano Bolzonella<br />
			Vicecapitano: Enrico Morgante<br />
			Partecipanti ufficiali: 24</td>
		<td class="center">Collegio Timpano</td>
	</tr>
</tbody>
</table>

<?php end_box(); ?>

<script type="text/javascript">
/*
function dataRefresh() {	
	loadXMLDoc("countdown", "countdown.php");
	
	setTimeout("dataRefresh()",1000);
}
*/
dataRefresh();

</script>

<br />

<?php start_box("@24oresns", 'purple', 0, 'left') ?>

<a class="twitter-timeline" href="https://twitter.com/24oresns" data-widget-id="299895045971120129">Tweets di @24oresns</a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>

<?php
end_box();
start_box("#24oresns", 'orange', 0, 'right');
?>

<a class="twitter-timeline" href="https://twitter.com/search?q=%2324oresns" data-widget-id="299897150467350528">Tweet su "#24oresns"</a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>


<?php
end_box();
end_html();

?>
