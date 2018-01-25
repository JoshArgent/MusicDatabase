<!DOCTYPE html>
<html>
<?php
include_once('database.php');
if(isset($_POST['action']))
{
	if($_POST['action'] == "delete")
	{
		$artistID = htmlspecialchars($_POST['artistID']);
		delete_artist($artistID);
	}
	else if($_POST['action'] == "create")
	{
		$artistName = htmlspecialchars($_POST['artistName']);
		create_artist($artistName);
	}
	else if($_POST['action'] == "edit")
	{
		$artistID = htmlspecialchars($_POST['artistID']);
		$artistName = htmlspecialchars($_POST['artistName']);
		update_artist($artistID, $artistName);
	}
}
?>
<head>
<title>Artists | Music Library</title>
<link rel="stylesheet" type="text/css" href="style.css">
<script type="text/javascript" src="script.js"></script>
</head>
<body>

<div id="main">
	
	
	<div class="header">
		<h1>Artists</h1>
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
		
		<table>
			<tr><th>Artist ID</th><th>Name</th><th>No. Albums</th><th></th></tr>
			<?php display_artists(""); ?>
		</table>
		<br /><br /><form action="" method="POST" onsubmit="return validateCreateArtistForm();"><input type="hidden" name="action" value="create" /><input type="text" name="artistName" id="artistName" placeholder="Artist Name" /> <input type="submit" value="Create" /></form>
	
	
	</div>

</div>

</body>
</html>
