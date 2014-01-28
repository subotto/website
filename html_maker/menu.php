<?php
function menu($countdown = TRUE, $group = "home") {
    $links = array();
    $links["home"] = array();
    $links["history"] = array(
        "Statistiche generali" => "statistics.php",
        "Edizione 2010" => "statistics.php?match_id=1",
        "Edizione 2011" => "statistics.php?match_id=2",
        "Edizione 2012" => "statistics.php?match_id=3",
        "Edizione 2013" => "statistics.php?match_id=4"
    );
    $links["current"] = array(
        "Turni programmati" => "schedule.php",
        "Punteggio in tempo reale" => "24h.php",
        "Streaming in tempo reale" => "streaming.php"
    );
    $links["teams"] = array(
    );
    $links["event"] = array(
        "Regolamento" => "rules.php",
        "Riconoscimenti" => "credits.php",
        "Storia" => "annals.php"
    );
	?>
    <div class="header">
      <div class="header-inner">
        <div class="container">
          <span class="header-span">
            <span class="white left-white"></span>
            <span class="lightblue lightblue-left"></span>
            <div class="header-text">
              <span class="white header-background">
                <a href="index.php"><img class="logo" src="images/logo.svg" /></a>
                <span class="info">
                  Ventiquattr'ore di Biliardino <br/>
                  Quinta edizione <br/>
                  <i>28-29 gennaio 2014</i>
                </span>
              </span>
              <span class="lightblue lightblue-right"></span>
              <span class="scoreboard-background">
                <table class="scoreboard">
                  <tr class="bigger">
                    <td class="score score-mat" id="score-0">&ndash;</td>
                    <td class="scoreboard-separator">:</span>
                    <td class="score score-fis" id="score-1">&ndash;</td>
                  </tr>
                  <tr>
                    <td class="score score-mat" id="team-0">Matematici</td>
                    <td class="scoreboard-separator"></td>
                    <td class="score score-fis" id="team-1">Fisici</td>
                  </tr>
                </table>
              </span>
            </div>
            <nav class="links links-upper white">
              <li><a href="index.php">Home</a></li>
              <li><a href="schedule.php">Edizione corrente</a></li>
              <li><a href="statistics.php">Edizioni passate</a></li>
              <li><a href="trailers.php">Trailer</a></li>
              <li><a href="rules.php">La manifestazione</a></li>
              <!--<li><a href="streaming.php">Live</a></li>-->
              <li></li>
            </nav>
            <nav class="links links-lower">
              <?php
                foreach($links[$group] as $n => $l) {
                  echo "<li><a href='$l'>$n</a></li>";
                }
              ?>
            </nav>
	        <?php
	        if ( $countdown ) {
	        ?>
            <div class="subheader">
              <div id="countdown">
              </div>
            </div>
	        <script language="JavaScript">
	            var status = "<?php echo trim(file_get_contents("stats/fake.html"));?>";
		        // set the date we're counting down to
		        var target_date = new Date("Jan 28, 2014 22:00:00").getTime();
		        // variables for time units
		        var days, hours, minutes, seconds;
		        // get tag element
		        var countdown = document.getElementById("countdown");
		        function update_timer() {
                    if(status === "running" || status === "advantages") {
			            countdown.innerHTML = "Partita iniziata da <span id='dtime' class='big'></span>";
			            $("#dtime").load("stats.php?page_name=time");
			            return;
			        } else if (/*status === "before"*/ true) {
			            // find the amount of "seconds" between now and target
			            var current_date = new Date().getTime();
			            var seconds_left = (target_date - current_date) / 1000;
			            // do some time calculations
			            days = parseInt(seconds_left / 86400);
			            seconds_left = seconds_left % 86400;
			            hours = parseInt(seconds_left / 3600);
			            seconds_left = seconds_left % 3600;
			            minutes = parseInt(seconds_left / 60);
			            seconds = parseInt(seconds_left % 60);
			            if(minutes < 10) minutes = '0' + minutes;
			            if(seconds < 10) seconds = '0' + seconds;
			            // format countdown string + set tag value
			            if(days>0)
			                countdown.innerHTML = "<span class='big'>" + days + "</span> giorni e ";
			            else
			                countdown.innerHTML = '';
			            countdown.innerHTML += "<span class='big'>" + hours + ":" + minutes + ":" + seconds + "</span> all'inizio!";
			         } else if (status === "ended") {
			            countdown.innerHTML = "<span class='big'>Partita finita.</span>";
			         }
		        }
		        function update_status_and_score() {
		          $.get("stats.php?page_name=fake", function (data) {
		            status = $.trim(data);
		          });
	              $("#team-0").load("stats.php?page_name=team0");
	              $("#team-1").load("stats.php?page_name=team1");
		          if (/*status === "before"*/ true) {
		              $("#score-0").html("&ndash;");
		              $("#score-1").html("&ndash;");
		          } else {
    		          $("#score-0").load("stats.php?page_name=score0");
	    	          $("#score-1").load("stats.php?page_name=score1");
	    	      }
		        }
		        // update the tag with id "countdown" every 1 second
		        setInterval(update_timer, 1000);
		        update_timer();

		        setInterval(update_status_and_score, 1000);
		        update_status_and_score();
	        </script>
            <?php
	        }
	        ?>
          </span>
        </div>
      </div>
    </div>
	<div class="container" >
	<?php
}
?>
