<!DOCTYPE html>
<html>
<?php
include_once('database.php');
$displayCDID = null;
if(isset($_POST['action']))
{
	if($_POST['action'] == "delete")
	{
		$trackID = htmlspecialchars($_POST['trackID']);
		delete_track($trackID);
	}
	else if($_POST['action'] == "create")
	{
		$CDID = htmlspecialchars($_POST['CDID']);
		$trackTitle = htmlspecialchars($_POST['trackTitle']);
		$trackLength = htmlspecialchars($_POST['trackLength']);
		create_track($CDID, $trackTitle, $trackLength);
	}
	else if($_POST['action'] == "edit")
	{
		$trackID = htmlspecialchars($_POST['trackID']);
		$trackTitle = htmlspecialchars($_POST['trackTitle']);
		$trackLength = htmlspecialchars($_POST['trackLength']);
		update_track($trackID, $trackTitle, $trackLength);
	}
}
else if(isset($_GET['display_CDID']))
{
	$displayCDID = htmlspecialchars($_GET['display_CDID']);
}
?>
<head>
<title>Tracks | Music Library</title>
<link rel="stylesheet" type="text/css" href="style.css">
<script type="text/javascript" src="script.js"></script>
</head>
<body>

<div class="main">
	
	<div class="header">
		<h1>Tracks</h1>
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
			<tr><th>Track ID</th><th>Artist</th><th>CD</th><th>Title</th><th>Duration</th><th></th></tr>
			<?php display_tracks("", $displayCDID); ?>
		</table>
		<br /><br /><form action="" method="POST" onsubmit="return validateCreateTrackForm();"><input type="hidden" name="action" value="create" /><select id="CDID" name="CDID"><?php displayCDList(); ?></select> <input type="text" id="trackTitle" name="trackTitle" placeholder="Track Title" /> <input type="text" id="trackLength" name="trackLength" placeholder="Track Length" /> <input type="submit" value="Create" /></form>
	
	</div>

</div>

</body>
</html>
