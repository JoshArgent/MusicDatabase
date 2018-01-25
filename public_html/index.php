<!DOCTYPE html>
<html>
<?php
include_once('database.php');
?>
<head>
<title>Home | Music Library</title>
<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>

<div id="main">
	
	<div class="header">
		<h1>Home</h1>
	</div>
	
	<div class="navigation">
		<ul class="nav">
			<li class="nav"><a href="index.php">Home</a></li>
			<li class="nav"><a href="artists.php">Artists</a></li>
			<li class="nav"><a href="albums.php">Albums</a></li>
			<li class="nav"><a href="tracks.php">Tracks</a></li>
			<li class="nav_search"><form action="search.php" method="GET"><input type="text" name="query" placeholder="Search" /></form></li>
		</ul>
	</div>
	
	<div class="content">
	
		<h2>Database Metrics</h2>
		<ul>
			<?php display_metrics(); ?>
		</ul>
	
	</div>

</div>

</body>
</html>
