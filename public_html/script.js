function editArtist(id)
{
	document.getElementById("viewRowA" + id).style.display = "none";
	document.getElementById("editRowA" + id).style.display = "table-row";
}

function validateArtistForm(id)
{
	var artistName = document.getElementById("artistName" + id).value;
	if(artistName == "")
	{
		alert("Artist name field is empty!");
		return false;
	}
	return true;
}

function validateCreateArtistForm()
{
	var artistName = document.getElementById("artistName").value;
	if(artistName == "")
	{
		alert("Artist name field is empty!");
		return false;
	}
	return true;
}

function deleteArtist(id)
{
	var result = confirm("Are you sure you want to delete this record?");
	if(result == true)
	{
		document.getElementById("deleteA" + id).submit();
	}
}

function editCD(id)
{
	document.getElementById("viewRowC" + id).style.display = "none";
	document.getElementById("editRowC" + id).style.display = "table-row";
}

function validateCDForm(id)
{
	var CDTitle = document.getElementById("CDTitle" + id).value;
	var CDGenre = document.getElementById("CDGenre" + id).value;
	var CDPrice = document.getElementById("CDPrice" + id).value;
	if(CDTitle == "" || CDGenre == "" || CDPrice == "")
	{
		alert("Some field(s) are empty!");
		return false;
	}
	return true;
}

function validateCreateCDForm()
{
	var artistID = document.getElementById("artistID").value;
	var CDTitle = document.getElementById("CDTitle").value;
	var CDGenre = document.getElementById("CDGenre").value;
	var CDPrice = document.getElementById("CDPrice").value;
	if(artistID == "" || CDTitle == "" || CDGenre == "" || CDPrice == "")
	{
		alert("Some field(s) are empty!");
		return false;
	}
	return true;
}

function deleteCD(id)
{
	var result = confirm("Are you sure you want to delete this record?");
	if(result == true)
	{
		document.getElementById("deleteC" + id).submit();
	}
}

function editTrack(id)
{
	document.getElementById("viewRowT" + id).style.display = "none";
	document.getElementById("editRowT" + id).style.display = "table-row";
}

function validateTrackForm(id)
{
	var trackTitle = document.getElementById("trackTitle" + id).value;
	var trackLength = document.getElementById("trackLength" + id).value;
	if(trackTitle == "" || trackLength == "")
	{
		alert("Some field(s) are empty!");
		return false;
	}
	return true;
}

function validateCreateTrackForm(id)
{
	var CDID = document.getElementById("CDID").value;
	var trackTitle = document.getElementById("trackTitle").value;
	var trackLength = document.getElementById("trackLength").value;
	if(CDID == "" || trackTitle == "" || trackLength == "")
	{
		alert("Some field(s) are empty!");
		return false;
	}
	return true;
}

function deleteTrack(id)
{
	var result = confirm("Are you sure you want to delete this record?");
	if(result == true)
	{
		document.getElementById("deleteT" + id).submit();
	}
}
