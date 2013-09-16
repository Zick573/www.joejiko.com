<?php
/*
	Remote Downloader
	MP3-jPlayer 1.8.3
	www.sjward.org
*/

$mp3 = false;
$playerID = "";
$fp = "";
$file = "";
$dbug = "";
$sent = "";
$rooturl = preg_replace("/^www\./i", "", $_SERVER['HTTP_HOST']);

if ( isset($_GET['mp3']) ) {

	$mp3 = strip_tags($_GET['mp3']);
	$playerID = ( isset($_GET['pID']) ) ? strip_tags($_GET['pID']) : "";
	
	if ( preg_match("!\.mp3$!i", $mp3) ) {
		
		$sent = substr($mp3, 3);
		$file = substr(strrchr($sent, "/"), 1);
		
		if ( ($lp = strpos($sent, $rooturl)) || preg_match("!^/!", $sent) ) { //if local
			
			if ( $lp !== false ) { //a url
				
				$fp = str_replace($rooturl, "", $sent);
				$fp = str_replace("www.", "", $fp);
				$fp = str_replace("http://", "", $fp);
				$fp = str_replace("https://", "", $fp);
			
			} else { //a folder path
				
				$fp = $sent;
			}
			
			if ( ($fsize = @filesize($_SERVER['DOCUMENT_ROOT'] . $fp)) !== false ) { //if file can be read then set headers and cookie
				
				header('Accept-Ranges: bytes');  // download resume
				header('Content-Disposition: attachment; filename=' . $file);
				header('Content-Type: audio/mpeg');
				header('Content-Length: ' . $fsize);
				
				@readfile($_SERVER['DOCUMENT_ROOT'] . $fp);
				
				//if past the readfile then something went wrong
				$dbug .= "#read failed";
				
			} else {
				
				$dbug .= "#no file";
			}
				
		} else {
			
			$dbug .= "#unreadable";
		}
	
	} else {

		$dbug .= "#not an mp3";
	}

} else {

	$dbug .= "#no get param";
}

?>
<!DOCTYPE html>
<html>
	<head>
		<title>Download MP3</title>
	</head>
	<body>
		
		<?php 
		$info = "<p>
			Get: " . $_GET['mp3'] . "<br />
			Sent: " . $sent . "<br />
			File: " . $file . "<br />
			Open: " . $_SERVER['DOCUMENT_ROOT'] . $fp . "<br />
			Root: " . $rooturl . "<br />
			pID: " . $playerID . "<br />
			Dbug: " . $dbug . "<br /></p>";
		
		echo $info;
		?>	

	</body>
</html>