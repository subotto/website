<!doctype html>

<html lang="en">
<head>
  <meta charset="utf-8">

  <title>Test Subbotto SVG</title>

  <!--<link rel="stylesheet" href="css/styles.css?v=1.0">-->

  <!--[if lt IE 9]>
  <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
  <![endif]-->

  <script src="js/jquery.js"></script>
  <script src="js/svg.js"></script>
  <script src="js/field.js"></script>

</head>


<body>
	<input type="range" id="angle_slider" min="-314" max="314"><span id="angle_value"></span>
	<!--<div id="canvas" style="width:380px; height: 640px; background: green;"></div>-->

	<div id="debug_div"></div>
  <div id="frames_div"></div>

	<div><?php include "field.svg"; ?></div>
	<div id="time"></div>
	<script>
   init_field();
	</script>
</body>

</html>
