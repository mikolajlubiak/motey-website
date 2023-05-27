<?php

$target_dir = "emotes/";
$target_file = $target_dir . basename($_FILES["emote"]["name"]);
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
$required = array($_POST['emotename'], $target_file);

foreach($required as $field) {
	if (empty($field)) {
		die(nl2br("\nYou need to fill all fields"));
	}
}

// Check if image file is a actual image or fake image
if(isset($_POST["submit"])) {
	$check = getimagesize($_FILES["emote"]["tmp_name"]);
	if($check !== false) {
		echo nl2br("\nFile is an emote - " . $check["mime"] . ".");
		$uploadOk = 1;
	} else {
		die(nl2br("\nFile is not an emote."));
		$uploadOk = 0;
	}
}

// Check if file already exists
if (file_exists($target_file)) {
	die(nl2br("\nSorry, emote with this name already exists."));
	$uploadOk = 0;
}

// Check file size
if ($_FILES["emote"]["size"] > 20000000) {
	die(nl2br("\nSorry, your emote is too large. Max size emote is 20MB"));
	$uploadOk = 0;
}

// Allow certain file formats
if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
&& $imageFileType != "gif" && $imageFileType != "webp" && $imageFileType != "mp4" ) {
	die(nl2br("\nSorry, only WEBP, MP4, JPG, JPEG, PNG & GIF emotes are allowed."));
	$uploadOk = 0;
}

// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
	die(nl2br("\nSorry, your emote was not uploaded."));
// if everything is ok, try to upload file
} else {
	if (move_uploaded_file($_FILES["emote"]["tmp_name"], $target_file)) {
		echo nl2br("\nThe file ". htmlspecialchars( basename( $_FILES["emote"]["name"])). " has been uploaded.");
	} else {
		die(nl2br("\nSorry, there was an error uploading your file."));
	}
}


$username = "root";
$password = "passwd";
$dbname = "emotebot";

$conn = new mysqli("localhost", $username, $password, $dbname);
$sql = sprintf("INSERT INTO emotes
		(name, location)
		VALUES
		('%s', '%s')",
		$conn -> real_escape_string( $_POST['emotename'] ),
		$conn -> real_escape_string( $target_file ),
		);

if ( $conn -> query( $sql ) === TRUE ) {
	echo nl2br("\nNew emote created successfully");
} else {
	die(nl2br("\nError: " . $sql . "<br>" . $conn->error));
}

$conn -> close();

?>
