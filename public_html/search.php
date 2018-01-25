<!DOCTYPE html>
<html>
<?php
include_once('database.php');
?>
<head>
<title>Search Results | Music Library</title>
<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>

<div id="main">
	
	<div class="header">
		<h1>Search Results</h1>
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
	
		<p>Search results for: "<?php echo htmlspecialchars($_GET['query']); ?>"</p>
		
		<b>Artists:</b><br />		
		<table>
			<tr><th>Artist ID</th><th>Name</th><th>No. Albums</th><th></th></tr>
			<?php display_artists(htmlspecialchars($_GET['query'])); ?>
		</table>
		<br /><hr />
		
		<b>CDs:</b><br />
		<table>
			<tr><th>CD ID</th><th>Artist</th><th>Title</th><th>Genre</th><th>Price</th><th>Tracks</th><th>Length</th><th></th></tr>
			<?php display_cds(htmlspecialchars($_GET['query']), null); ?>
		</table>
		<br /><hr />
		
		<b>Tracks:</b><br />
		<table>
			<tr><th>Track ID</th><th>Artist</th><th>CD</th><th>Title</th><th>Duration</th><th></th></tr>
			<?php display_tracks(htmlspecialchars($_GET['query']), null); ?>
		</table>
	
	</div>

</div>

</body>
</html>
