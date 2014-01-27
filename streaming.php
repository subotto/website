<?php

include_once "inclusions.php";

start_html("24 ore: Streaming live",TRUE);
make_header(true, "current");

?>

<?php


start_box("24 ore: streaming live");


$test = TRUE;


if ( $test == FALSE || isset( $_GET["test"] ) ) echo '<div id="score"></div>';

?>

<div class="toggle" id="toggle_quality">
    <a onclick="toggle(this)" id="tab_low" class="active">Bassa qualità</a>
    <a onclick="toggle(this)" id="tab_hi">Alta qualità</a>
</div>

<center id="low_quality">
<video id="my_video_1" class="video-js vjs-default-skin" width="800" height="450" controls
  preload="auto" poster="images/subotto2.jpg"
  data-setup="{}">
  <?php
  echo '<source src="http://soyuz.sns.it:8000/low.ogg" type="video/ogg">';
  ?>
</video>
</center>

<center id="hi_quality" class="hidden">
<video id="my_video_1" class="video-js vjs-default-skin" width="800" height="450" controls
  preload="auto" poster="images/subotto2.jpg"
  data-setup="{}">
  <?php
  echo '<source src="http://soyuz.sns.it:8000/hi.ogg" type="video/ogg">';
  ?>
</video>
</center>

<script>loadtoggle("quality")</script>

<p>L'unico browser con cui questo streaming &egrave; stato testato &egrave; Firefox (<a href="http://www.mozilla.org/products/download.html?product=firefox-10.0.1&os=linux&lang=en-US">download</a>).</p>

<?php

echo '<p>Link diretto allo streaming (<a href="http://soyuz.uz.sns.it:8000/low.ogg">bassa qualit&agrave;</a>, <a href="http://soyuz.uz.sns.it:8000/hi.ogg">alta qualit&agrave;</a>).</p>';

end_box();
end_html();

?>
