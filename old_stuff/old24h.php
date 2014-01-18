<?php

include_once "inclusions.php";

start_html("24 ore");

make_header();

make_title("24 ore: aggiornamento in tempo reale");


$test = FALSE;

if ( $test ) {
	
	if ( isset( $_GET["test"] ) ) echo "<h2>Questa pagina &egrave; attualmente in fase di test: le informazioni presenti sono COSE A CASO (GioMasce fa il puntiglioso e nota che non sono proprio a caso).</h2>";
	else die( paragraph(bold( "Dati non disponibili." )) );

}

?>

<table class="centro">
<col width="250" />
<col width="800" />
<col width="250" />

<tr><td>
	<div id="player00" style="display:none"></div>
	<div id="player01" style="display:none"></div>
</td><td>
	<div id="main"><b>Caricamento dati...</b></div>
</td><td>
	<div id="player10" style="display:none"></div>
	<div id="player11" style="display:none"></div>
</td></tr>

</table>

<div id="secondary"></div>

<script type="text/javascript">

function show(id) {
	var e = document.getElementById(id);
	e.style.display = 'inline';
}

function hide(id) {
	var e = document.getElementById(id);
	e.style.display = 'none';
}


function loadXMLDoc(id, page) {
	var xmlhttp;
	if (window.XMLHttpRequest)
	{// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp=new XMLHttpRequest();
	}
	else
	{// code for IE6, IE5
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
  
	xmlhttp.onreadystatechange=function()
	{
		if (xmlhttp.readyState==4 && xmlhttp.status==200)
		{
			document.getElementById(id).innerHTML=xmlhttp.responseText;
		}
	}
	xmlhttp.open("GET", page, true);
	xmlhttp.send();
}

function dataRefresh() {	
	loadXMLDoc("main", "main.php");
	loadXMLDoc("secondary", "secondary.php");
	loadXMLDoc("player00", "stats/player00.html");
	loadXMLDoc("player01", "stats/player01.html");
	loadXMLDoc("player10", "stats/player10.html");
	loadXMLDoc("player11", "stats/player11.html");
	
	setTimeout("dataRefresh()",1000);
}

dataRefresh();

</script>

<?php

end_html();

?>
