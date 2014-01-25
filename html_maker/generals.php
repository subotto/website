<?php



function start_html($titolo, $streaming = FALSE, $live_score = FALSE) {
	?>
	    <!doctype html>
		<html>
		<head>
			<meta charset='utf-8' />
			<meta name="viewport" content="width=1368">
			<?php
			// Segue il redirect al nuovo sito
			// <meta HTTP-EQUIV="REFRESH" content="0; url=http://uz.sns.it/24ore/">
			?>
			
			<title>
			<?php echo $titolo; ?>
			</title>
			<link rel="stylesheet" type="text/css" href="style.css" />
			
			<?php
			if ( $streaming ) {
				?>
				<link href="video-js/video-js.css" rel="stylesheet">
				<script src="video-js/video.js"></script>
				<?php
			}
			
			if ( $live_score ) {
				?>
				<script src="js/jquery.js"></script>
				<script src="js/svg.js"></script>
				<script src="js/field.js"></script>
				<?php
			}
			?>
			
		</head>
		
		<body id="body">
		
		<script src="js/functions.js"></script>
		<script src="js/sorttable.js"></script>
		<script>
		  var status = 0;
		  document.getElementById("body").onkeydown = function(e) {
		    //up up dw dw right right left left a a b b
		    if((status == 0 || status == 1) && e.keyCode == 38) {
		        status++;
		    } else if((status == 2 || status == 3) && e.keyCode == 40) {
		        status++;
		    } else if((status == 5 || status == 7) && e.keyCode == 39) {
		        status++;
		    } else if((status == 4 || status == 6) && e.keyCode == 37) {
		        status++;
		    } else if(status == 9 && e.keyCode == 65) {
		        status++;
		    } else if(status == 8 && e.keyCode == 66) {
		        status++;
		    } else {
		        status = 0;
		    }
		    if(status != 0 && e.keyCode > 64) {
		        e.preventDefault();
		    }
		    if(status == 10) {
		        status = 0;
		        el = document.getElementById('konami_code');
		        el.style.display = "block";
		        setTimeout(function(){
		            document.getElementById('konami_code').style.display = 'none';
		        }, 2000);
		        el.innerHTML = '<img style="position: absolute; bottom: 0px; right: 0px;" src="images/konami-code-' +  Math.floor(Math.random()*3) + '.png" />';
		    }
		  }
		</script>
	<?php
	
}


function end_html() {
	?>
</div>
<div id="konami_code" style="display:none; position:fixed; top:0; bottom:0; left:0; right:0; z-index: 5000"></div>
<!-- Piwik --> 
<script type="text/javascript">
var pkBaseURL = (("https:" == document.location.protocol) ? "https://piwik.gulp.linux.it/" : "http://piwik.gulp.linux.it/");
document.write(unescape("%3Cscript src='" + pkBaseURL + "piwik.js' type='text/javascript'%3E%3C/script%3E"));
</script><script type="text/javascript">
try {
var piwikTracker = Piwik.getTracker(pkBaseURL + "piwik.php", 6);
piwikTracker.trackPageView();
piwikTracker.enableLinkTracking();
} catch( err ) {}
</script><noscript><p><img src="http://piwik.gulp.linux.it/piwik.php?idsite=6" style="border:0" alt="" /></p></noscript>
<!-- End Piwik Tracking Code -->

		</body>
		</html>
	<?php
}



function paragraph($frase) {
	echo "<p>".$frase."</p>";
}

function title($dim,$titolo) {
	echo "<h".$dim.">".$titolo."</h".$dim.">";
}


function make_header($countdown = TRUE, $group = "home") {
	
	// Commentare la riga seguente quando viene settato il countdown!!!
	// $countdown = FALSE;
	
	menu($countdown, $group);
	
}

function make_title($title, $dim=1) {
    echo "<h$dim>Please replace me! - $title</h$dim>";
}

function make_page_title($title) {
    echo "<div class=\"page-title\">$title</div>";
}


function start_box($title, $color='blue', $width=0, $pull = '') {
    if ($pull != '') $pull = "pull-$pull";
	$style='';
	if ($width!=0) $style='style="width: '.$width.'px"';
?>
      <div class="box box-<?php echo $color;?> <?php echo $pull;?>" <?php echo $style;?>>
        <div class="box-header">
          <span class="box-header-name">
            <span><?php echo $title;?></span>
          </span>
          <span class="box-header-middle"></span>
          <span class="box-header-end"></span>
        </div>
        <div class="box-content">
<?php
}
function end_box() {
?>
        </div>
      </div>
<?php
}

/*
function make_table($tabella) {
	echo "<table>";
	foreach ($tabella as $row) {
		echo "<tr>";
		foreach ($row as $column) {
			echo "<td>".$column."</td>";
		}
		echo "</tr>";
	}
	echo "</table>";
}
*/

function return_table( $tabella, $style = FALSE, $head = FALSE, $chead = FALSE, $params = array("td" => "", "table" => "") ) {
	
	$result = "";
	
	$pt = "";
	if ( array_key_exists("table",$params) ) $pt = $params["table"];
	
	if ( $style ) $result .= "<table id=\"".$style."\" ".$pt.">";
	else $result .= "<table ".$pt.">";
	
	
	if ( array_key_exists("col_widths",$params) ) {
		foreach ( $params["col_widths"] as $width ) {
			$result .= "<col width=\"".$width."\">";
		}
	}
	
	if ( $head ) {
		$result .= "<thead>";
		foreach ( $head as $column ) {
			$result .= "<th>".$column."</th>";
		}
		$result .= "</thead><tbody>\n";
	}
	foreach ($tabella as $row) {
		$result.= "<tr>";
		$primo = TRUE;
		
		$par = NULL;
		if ( array_key_exists("td",$params) ) $par = $params["td"];
		else $par = "";
		
		foreach ($row as $column) {
			if ( $primo && $chead ) {
				$primo = FALSE;
				$result .= "<th ".$par.">".$column."</th>";
			}
			else $result .= "<td ".$par.">".$column."</td>";
		}
		$result .= "</tr>\n";
	}
	if ( $head ) $result .= "</tbody>";
	$result .= "</table>";
	
	return $result;
}

function make_table($tabella, $style = FALSE, $head = FALSE, $chead = FALSE, $params = array("td" => "", "table" => "") ) {
	echo return_table($tabella,$style,$head,$chead,$params);
}

function collega ($id, $nome, $oggetto) {
	$result = '';
	$result = '<a href="view.php?tipo='.$oggetto.'&id='.$id.'">'.$nome.'</a>';
	
	return $result;
}

function print_rating ($rating) {
	if ( $rating == NULL ) return "-";
	else return sprintf("%01.2f",$rating);
}

function return_link ($pagina,$cosa) {
	return '<a href="'.$pagina.'">'.$cosa.'</a>';
}

function make_list ($list) {
	echo "<ul>";
	foreach ($list as $element) {
		echo "<li>".$element."</li>";
	}
	echo "</ul>";
}

function bold($text) {
	return "<b>".$text."</b>";
}

function italics($text) {
	return "<i>".$text."</i>";
}

function return_hidden_div($text) {
	return "<div style=\"display:none;\">".$text."</div>";
}

function return_hidden_key($number) {
	return return_hidden_div( sprintf("%09d", 999999999-((int)$number)) );
}

?>
