<?php

include_once "inclusions.php";

start_html("24 ore: Streaming live",TRUE);
make_header(true, "current");

?>

<?php


start_box("24 ore: streaming live");


$test = TRUE;


$quality = "hi";
if ( $_GET["quality"] ) {
	if ( $_GET["quality"] == "low" ) {
		$quality = "low";
	}
}


if ( $test == FALSE || isset( $_GET["test"] ) ) echo '<div id="score"></div>';

?>

<div class="quality-buttons">
<form name="input" action="streaming.php" method="GET">
<input type="hidden" name="quality" value="low" />
<input type="submit" value="Bassa qualit&agrave;" />
</form>
<form name="input" action="streaming.php" method="GET">
<input type="hidden" name="quality" value="high" />
<input type="submit" value="Alta qualit&agrave;" />
</form>
</div>

<center>
<video id="my_video_1" class="video-js vjs-default-skin" width="800" height="450" controls
  preload="auto" poster="images/subotto2.jpg"
  data-setup="{}">
  <?php
  echo '<source src="http://soyuz.sns.it:8000/'.$quality.'.ogg" type="video/ogg">';
  ?>
</video>
</center>

<p>L'unico browser con cui questo streaming &egrave; stato testato &egrave; Firefox (<a href="http://www.mozilla.org/products/download.html?product=firefox-10.0.1&os=linux&lang=en-US">download</a>).</p>

<?php

echo '<p><a href="http://soyuz.uz.sns.it:8000/'.$quality.'.ogg">Link diretto allo streaming (';
if ( $quality == "low" ) echo "bassa";
else echo "alta";
echo ' qualit&agrave;).</a></p>';

?>


<script type="text/javascript">

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
	loadXMLDoc("score", "streaming_score.php");
	
	setTimeout("dataRefresh()",1000);
}

dataRefresh();

</script>

<?php
end_box();
end_html();

?>
