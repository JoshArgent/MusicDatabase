<?php

$servername = "mysql.cs.nott.ac.uk";
$username = "psyja2";
$password = "*******";
$dbname = "psyja2";
error_reporting(-1);
ini_set('display_errors', 'On');

function display_artists($searchQuery) {
	
	$mysqli = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);

	/* check connection */
	if (mysqli_connect_errno())
	{
		printf("Connect failed: %s\n", mysqli_connect_error());
		exit();
	}

	/* if a search query is set */
	$query = "SELECT Artist.artistID, artistName, count(CD.artistID) FROM CD RIGHT JOIN Artist ON CD.artistID=Artist.artistID GROUP BY Artist.artistID ORDER BY artistName";
	if($searchQuery != "")
	{
		$searchQuery = "%" . $searchQuery . "%";
		$query = "SELECT Artist.artistID, artistName, count(CD.artistID) FROM CD RIGHT JOIN Artist ON CD.artistID=Artist.artistID WHERE artistName LIKE ? GROUP BY artistID ORDER BY artistName";
	}

	if ($stmt = $mysqli->prepare($query))
	{
		/* send search query param if set */
		if($searchQuery != "")
		{
			$stmt->bind_param("s", $searchQuery);
		}
		
		/* execute statement */
		$stmt->execute();

		/* bind result variables */
		$stmt->bind_result($artistID, $artistName, $numCDs);

		/* fetch and display values */
		while ($stmt->fetch())
		{
			echo "<tr id='editRowA$artistID' class='editRow' style='display: none;'><form method='POST' action='' onsubmit='return validateArtistForm($artistID)'><input type='hidden' name='artistID' value='$artistID' /><input type='hidden' name='action' value='edit' /><td>$artistID</td><td><input type='text' name='artistName' id='artistName$artistID' style='width: 100%' value='$artistName' /></td><td>$numCDs</td><td><input type='submit' value='Save' /></td></form></tr>";
			echo "<tr id='viewRowA$artistID'><td>$artistID</td><td>$artistName</td><td>$numCDs</td><td>";
			if($searchQuery == "")
			{
				echo "<a href='#' onclick='editArtist($artistID)'><img src='images/edit.png' alt='Edit' /></a> <form id='deleteA$artistID' method='POST' action=''><input type='hidden' name='action' value='delete' /><input type='hidden' name='artistID' value='$artistID' /></form><a href='#' onclick='deleteArtist($artistID)'><img src='images/delete.png' alt='Edit' /></a>";
			}
			echo " <a href='albums.php?display_artistID=$artistID'>Albums</a></td></tr>";
		}

		/* close statement */
		$stmt->close();
	}

	/* close connection */
	$mysqli->close();
}

function display_cds($searchQuery, $artistID) {
	
	$mysqli = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);

	/* check connection */
	if (mysqli_connect_errno())
	{
		printf("Connect failed: %s\n", mysqli_connect_error());
		exit();
	}

	/* if a search query is set */
	$query = "SELECT CD.cdID, Artist.artistName, CD.cdTitle, CD.cdGenre, CD.cdPrice, count(Track.cdID), sum(Track.trackLength) FROM Artist RIGHT JOIN CD ON CD.artistID=Artist.artistID LEFT JOIN Track ON CD.cdID=Track.cdID GROUP BY CD.cdID ORDER BY CD.cdTitle";
	if($searchQuery != "")
	{
		$searchQuery = "%" . $searchQuery . "%";
		$query = "SELECT CD.cdID, Artist.artistName, CD.cdTitle, CD.cdGenre, CD.cdPrice, count(Track.cdID), sum(Track.trackLength) FROM Artist RIGHT JOIN CD ON CD.artistID=Artist.artistID LEFT JOIN Track ON CD.cdID=Track.cdID WHERE CD.cdTitle LIKE ? GROUP BY CD.cdID ORDER BY CD.cdTitle";
	}
	else if($artistID != null)
	{
		$query = "SELECT CD.cdID, Artist.artistName, CD.cdTitle, CD.cdGenre, CD.cdPrice, count(Track.cdID), sum(Track.trackLength) FROM Artist RIGHT JOIN CD ON CD.artistID=Artist.artistID LEFT JOIN Track ON CD.cdID=Track.cdID WHERE CD.artistID=? GROUP BY CD.cdID ORDER BY CD.cdTitle";
	}

	if ($stmt = $mysqli->prepare($query))
	{
		/* send search query param if set */
		if($searchQuery != "")
		{
			$stmt->bind_param("s", $searchQuery);
		}
		else if($artistID != null)
		{
			$stmt->bind_param("i", $artistID);
		}
		
		/* execute statement */
		$stmt->execute();

		/* bind result variables */
		$stmt->bind_result($CDID, $artistName, $CDTitle, $CDGenre, $CDPrice, $numTracks, $length);

		/* fetch and display values */
		while ($stmt->fetch())
		{
			$artistName = htmlspecialchars($artistName, ENT_QUOTES);
			$CDTitle = htmlspecialchars($CDTitle, ENT_QUOTES);
			?>
			<tr id='editRowC<?php echo $CDID; ?>' class='editRow' style='display: none;'>
				<form method='POST' action='' onsubmit='return validateCDForm(<?php echo $CDID; ?>)'>
					<input type='hidden' name='CDID' value='<?php echo $CDID; ?>' /><input type='hidden' name='action' value='edit' />
					<td><?php echo $CDID; ?></td>
					<td><?php echo $artistName; ?></td>
					<td><input type='text' style='width: 100%' name='CDTitle' id='CDTitle<?php echo $CDID; ?>' value='<?php echo $CDTitle; ?>' /></td>
					<td><input type='text' style='width: 100%'  name='CDGenre' id='CDGenre<?php echo $CDID; ?>' value='<?php echo $CDGenre; ?>' /></td>
					<td><input type='text' style='width: 100%' name='CDPrice' id='CDPrice<?php echo $CDID; ?>' value='<?php echo $CDPrice; ?>' /></td>
					<td><?php echo $numTracks; ?></td>
					<td><?php echo $length; ?></td>
					<td><input type='submit' value='Save' /></td>
				</form>
			</tr>
			<tr id='viewRowC<?php echo $CDID; ?>'>
				<td><?php echo $CDID; ?></td>
				<td><?php echo $artistName; ?></td>
				<td><?php echo $CDTitle; ?></td>
				<td><?php echo $CDGenre; ?></td>
				<td>&pound;<?php echo $CDPrice; ?></td>
				<td><?php echo $numTracks; ?></td>
				<td><?php echo $length; ?></td>
				<td>
				<?php if($searchQuery == "")
				{ ?>
				<a href='#' onclick='editCD(<?php echo $CDID; ?>)'><img src='images/edit.png' alt='Edit' /></a> <form id='deleteC<?php echo $CDID; ?>' method='POST' action=''><input type='hidden' name='action' value='delete' /><input type='hidden' name='CDID' value='<?php echo $CDID; ?>' /></form><a href='#' onclick='deleteCD(<?php echo $CDID; ?>)'><img src='images/delete.png' alt='Edit' /></a> 
				<?php } ?><a href='tracks.php?display_CDID=<?php echo $CDID; ?>'>Tracks</a></td>
			</tr>
			<?php
		}

		/* close statement */
		$stmt->close();
	}

	/* close connection */
	$mysqli->close();
}

function display_tracks($searchQuery, $CDID) {
	
	$mysqli = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);

	/* check connection */
	if (mysqli_connect_errno())
	{
		printf("Connect failed: %s\n", mysqli_connect_error());
		exit();
	}

	/* if a search query is set */
	$query = "SELECT Track.trackID, Artist.artistName, CD.cdTitle, Track.trackTitle, Track.trackLength FROM Artist NATURAL JOIN CD NATURAL JOIN Track ORDER BY Track.trackTitle";
	if($searchQuery != "")
	{
		$searchQuery = "%" . $searchQuery . "%";
		$query = "SELECT Track.trackID, Artist.artistName, CD.cdTitle, Track.trackTitle, Track.trackLength FROM Artist NATURAL JOIN CD NATURAL JOIN Track WHERE Track.trackTitle LIKE ? ORDER BY Track.trackTitle";
	}
	else if($CDID != null)
	{
		$query = "SELECT Track.trackID, Artist.artistName, CD.cdTitle, Track.trackTitle, Track.trackLength FROM Artist NATURAL JOIN CD NATURAL JOIN Track WHERE Track.cdID=? ORDER BY Track.trackTitle";
	}
	
	if ($stmt = $mysqli->prepare($query))
	{
		/* send search query param if set */
		if($searchQuery != "")
		{
			$stmt->bind_param("s", $searchQuery);
		}
		else if($CDID != null)
		{
			$stmt->bind_param("i", $CDID);
		}
		
		/* execute statement */
		$stmt->execute();

		/* bind result variables */
		$stmt->bind_result($trackID, $artistName, $CDTitle, $trackTitle, $trackLength);

		/* fetch and display values */
		while ($stmt->fetch())
		{
			
			$artistName = htmlspecialchars($artistName, ENT_QUOTES);
			$CDTitle = htmlspecialchars($CDTitle, ENT_QUOTES);
			$trackTitle = htmlspecialchars($trackTitle, ENT_QUOTES);
			?>
			<tr id='editRowT<?php echo $trackID; ?>' class='editRow' style='display: none;'>
				<form method='POST' action='' onsubmit='return validateTrackForm(<?php echo $trackID; ?>)'>
					<input type='hidden' name='trackID' value='<?php echo $trackID; ?>' />
					<input type='hidden' name='action' value='edit' />				
					<td><?php echo $trackID; ?></td>
					<td><?php echo $artistName; ?></td>
					<td><?php echo $CDTitle; ?></td>
					<td><input type='text' style='width: 100%'  name='trackTitle' id='trackTitle<?php echo $trackID; ?>' value='<?php echo $trackTitle; ?>' /></td>
					<td><input type='text' style='width: 100%' name='trackLength' id='trackLength<?php echo $trackID; ?>' value='<?php echo $trackLength; ?>' /></td>
					
					<td><input type='submit' value='Save' /></td>
				</form>
			</tr>
			<tr id='viewRowT<?php echo $trackID; ?>'>
				<td><?php echo $trackID; ?></td>
				<td><?php echo $artistName; ?></td>
				<td><?php echo $CDTitle; ?></td>
				<td><?php echo $trackTitle; ?></td>
				<td><?php echo $trackLength; ?></td>
				<td><?php if($searchQuery == "")
				{ ?>
				<a href='#' onclick='editTrack(<?php echo $trackID; ?>)'><img src='images/edit.png' alt='Edit' /></a> <a href='#' onclick='deleteTrack(<?php echo $trackID; ?>)'><img src='images/delete.png' alt='Edit' /></a><form method='POST' action='' id='deleteT<?php echo $trackID; ?>'><input type='hidden' name='action' value='delete' /><input type='hidden' name='trackID' value='<?php echo $trackID; ?>' /></form><?php } ?></td>
			</tr>
			<?php
		}

		/* close statement */
		$stmt->close();
	}

	/* close connection */
	$mysqli->close();
}

function display_metrics() {
	
	$mysqli = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);

	/* check connection */
	if (mysqli_connect_errno())
	{
		printf("Connect failed: %s\n", mysqli_connect_error());
		exit();
	}

	/* if a search query is set */
	$query = "SELECT (SELECT COUNT(*) FROM Artist), (SELECT COUNT(*) FROM CD), (SELECT COUNT(*) FROM Track)";
	
	if ($stmt = $mysqli->prepare($query))
	{	
		/* execute statement */
		$stmt->execute();

		/* bind result variables */
		$stmt->bind_result($artists, $cds, $tracks);

		/* fetch and display values */
		while ($stmt->fetch())
		{
			echo "<li>Number of Artists: <b>$artists</b></li>";
			echo "<li>Number of CDs: <b>$cds</b></li>";
			echo "<li>Number of Tracks: <b>$tracks</b></li>";
		}

		/* close statement */
		$stmt->close();
	}

	/* close connection */
	$mysqli->close();
}

function update_artist($artistID, $artistName) {
	$mysqli = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);

	/* check connection */
	if (mysqli_connect_errno())
	{
		printf("Connect failed: %s\n", mysqli_connect_error());
		exit();
	}

	/* if a search query is set */
	$query = "UPDATE Artist SET artistName=? WHERE artistID=?";

	if ($stmt = $mysqli->prepare($query))
	{
		/* set query parameters */
		$stmt->bind_param("si", $artistName, $artistID);
		
		/* execute statement */
		$stmt->execute();

		/* close statement */
		$stmt->close();
	}

	/* close connection */
	$mysqli->close();
}

function update_cd($CDID, $cdTitle, $cdGenre, $cdPrice) {
	$mysqli = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);

	/* check connection */
	if (mysqli_connect_errno())
	{
		printf("Connect failed: %s\n", mysqli_connect_error());
		exit();
	}

	/* if a search query is set */
	$query = "UPDATE CD SET cdTitle=?, cdGenre=?, cdPrice=?  WHERE CDID=?";

	if ($stmt = $mysqli->prepare($query))
	{
		/* set query parameters */
		$stmt->bind_param("ssdi", $cdTitle, $cdGenre, $cdPrice, $CDID);
		
		/* execute statement */
		$stmt->execute();

		/* close statement */
		$stmt->close();
	}

	/* close connection */
	$mysqli->close();
}

function update_track($trackID, $trackTitle, $trackLength) {
	$mysqli = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);

	/* check connection */
	if (mysqli_connect_errno())
	{
		printf("Connect failed: %s\n", mysqli_connect_error());
		exit();
	}

	/* if a search query is set */
	$query = "UPDATE Track SET trackTitle=?, trackLength=? WHERE TrackID=?";

	if ($stmt = $mysqli->prepare($query))
	{
		/* set query parameters */
		$stmt->bind_param("sii", $trackTitle, $trackLength, $trackID);
		
		/* execute statement */
		$stmt->execute();

		/* close statement */
		$stmt->close();
	}

	/* close connection */
	$mysqli->close();
}

function create_artist($artistName) {

	$mysqli = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);

	/* check connection */
	if (mysqli_connect_errno())
	{
		printf("Connect failed: %s\n", mysqli_connect_error());
		exit();
	}

	/* if a search query is set */
	$query = "INSERT INTO Artist (artistName) VALUES (?)";

	if ($stmt = $mysqli->prepare($query))
	{
		/* set query parameters */
		$stmt->bind_param("s", $artistName);
		
		/* execute statement */
		$stmt->execute();

		/* close statement */
		$stmt->close();
	}

	/* close connection */
	$mysqli->close();

}

function delete_artist($artistID) {

	$mysqli = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);

	/* check connection */
	if (mysqli_connect_errno())
	{
		printf("Connect failed: %s\n", mysqli_connect_error());
		exit();
	}

	/* if a search query is set */
	$query = "DELETE FROM Artist WHERE artistID=?";

	if ($stmt = $mysqli->prepare($query))
	{
		/* set query parameters */
		$stmt->bind_param("i", $artistID);
		
		/* execute statement */
		$stmt->execute();

		/* close statement */
		$stmt->close();
	}

	/* close connection */
	$mysqli->close();
	
}

function create_CD($artistID, $CDTitle, $CDGenre, $CDPrice) {

	$mysqli = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);

	/* check connection */
	if (mysqli_connect_errno())
	{
		printf("Connect failed: %s\n", mysqli_connect_error());
		exit();
	}

	/* if a search query is set */
	$query = "INSERT INTO CD (artistID, CDTitle, CDGenre, CDPrice) VALUES (?,?,?,?)";

	if ($stmt = $mysqli->prepare($query))
	{
		/* set query parameters */
		$stmt->bind_param("issd", $artistID, $CDTitle, $CDGenre, $CDPrice);
		
		/* execute statement */
		$stmt->execute();

		/* close statement */
		$stmt->close();
	}

	/* close connection */
	$mysqli->close();

}

function delete_CD($CDID) {
	
	$mysqli = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);

	/* check connection */
	if (mysqli_connect_errno())
	{
		printf("Connect failed: %s\n", mysqli_connect_error());
		exit();
	}

	/* if a search query is set */
	$query = "DELETE FROM CD WHERE CDID=?";

	if ($stmt = $mysqli->prepare($query))
	{
		/* set query parameters */
		$stmt->bind_param("i", $CDID);
		
		/* execute statement */
		$stmt->execute();

		/* close statement */
		$stmt->close();
	}

	/* close connection */
	$mysqli->close();	
}

function create_track($CDID, $trackTitle, $trackLength) {

	$mysqli = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);

	/* check connection */
	if (mysqli_connect_errno())
	{
		printf("Connect failed: %s\n", mysqli_connect_error());
		exit();
	}

	/* if a search query is set */
	$query = "INSERT INTO Track (CDID, trackTitle, trackLength) VALUES (?,?,?)";

	if ($stmt = $mysqli->prepare($query))
	{
		/* set query parameters */
		$stmt->bind_param("isd", $CDID, $trackTitle, $trackLength);
		
		/* execute statement */
		$stmt->execute();

		/* close statement */
		$stmt->close();
	}

	/* close connection */
	$mysqli->close();

}

function delete_track($trackID) {
	
	$mysqli = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);

	/* check connection */
	if (mysqli_connect_errno())
	{
		printf("Connect failed: %s\n", mysqli_connect_error());
		exit();
	}

	/* if a search query is set */
	$query = "DELETE FROM Track WHERE trackID=?";

	if ($stmt = $mysqli->prepare($query))
	{
		/* set query parameters */
		$stmt->bind_param("i", $trackID);
		
		/* execute statement */
		$stmt->execute();

		/* close statement */
		$stmt->close();
	}

	/* close connection */
	$mysqli->close();		
}

function displayArtistList() {
	
	$mysqli = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);

	/* check connection */
	if (mysqli_connect_errno())
	{
		printf("Connect failed: %s\n", mysqli_connect_error());
		exit();
	}

	/* if a search query is set */
	$query = "SELECT artistID, artistName FROM Artist";

	if ($stmt = $mysqli->prepare($query))
	{
		/* execute statement */
		$stmt->execute();

		/* bind result variables */
		$stmt->bind_result($artistID, $artistName);

		/* fetch and display values */
		while ($stmt->fetch())
		{
			echo "<option value='$artistID'>$artistName</option>";
		}

		/* close statement */
		$stmt->close();
	}

	/* close connection */
	$mysqli->close();
	
}

function displayCDList() {
	
	$mysqli = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);

	/* check connection */
	if (mysqli_connect_errno())
	{
		printf("Connect failed: %s\n", mysqli_connect_error());
		exit();
	}

	/* if a search query is set */
	$query = "SELECT CDID, CDTitle FROM CD";

	if ($stmt = $mysqli->prepare($query))
	{
		/* execute statement */
		$stmt->execute();

		/* bind result variables */
		$stmt->bind_result($CDID, $CDTitle);

		/* fetch and display values */
		while ($stmt->fetch())
		{
			echo "<option value='$CDID'>$CDTitle</option>";
		}

		/* close statement */
		$stmt->close();
	}

	/* close connection */
	$mysqli->close(); 
	
}

?>
