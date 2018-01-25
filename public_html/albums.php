<!DOCTYPE html>
<html>
<?php
include_once('database.php');
$displayArtistID = null;
if(isset($_POST['action']))
{
	if($_POST['action'] == "delete")
	{
		$CDID = htmlspecialchars($_POST['CDID']);
		delete_CD($CDID);
	}
	else if($_POST['action'] == "create")
	{
		$artistID = htmlspecialchars($_POST['artistID']);
		$CDTitle = htmlspecialchars($_POST['CDTitle']);
		$CDGenre = htmlspecialchars($_POST['CDGenre']);
		$CDPrice = htmlspecialchars($_POST['CDPrice']);
		create_CD($artistID, $CDTitle, $CDGenre, $CDPrice);
	}
	else if($_POST['action'] == "edit")
	{
		$CDID = htmlspecialchars($_POST['CDID']);
		$CDTitle = htmlspecialchars($_POST['CDTitle']);
		$CDGenre = htmlspecialchars($_POST['CDGenre']);
		$CDPrice = htmlspecialchars($_POST['CDPrice']);
		update_cd($CDID, $CDTitle, $CDGenre, $CDPrice);
	}
}
else if(isset($_GET['display_artistID']))
{
	$displayArtistID = htmlspecialchars($_GET['display_artistID']);
}
?>
<head>
<title>Albums | Music Library</title>
<link rel="stylesheet" type="text/css" href="style.css">
<script type="text/javascript" src="script.js"></script>
</head>
<body>

<div id="main">
	
	<div class="header">
		<h1>Albums</h1>
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
		<table width="100%">
			<tr><th>CD ID</th><th>Artist</th><th>Title</th><th>Genre</th><th>Price</th><th>Tracks</th><th>Length</th><th></th></tr>
			<?php display_cds("", $displayArtistID); ?>
		</table>
		<br /><br /><form action="" method="POST" onsubmit="return validateCreateCDForm();"><input type="hidden" name="action" value="create" /><select id="artistID" name="artistID"><?php  displayArtistList(); ?></select> <input type="text" id="CDTitle" name="CDTitle" placeholder="Album Title" /> <input type="text" id="CDGenre" name="CDGenre" placeholder="Album Genre" /> <input type="text" id="CDPrice" name="CDPrice" placeholder="Album Price" /> <input type="submit" value="Create" /></form>
	
	</div>

</div>

</body>
</html>
