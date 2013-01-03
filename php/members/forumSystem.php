<?php

/*
 * Utility functions for the forum
 */

$categoriesLocation = '/u/acm/storage/categories/';

/*
 * Returns the simplexml object of the file
 */
function openFile($category, $filename){
	global $categoriesLocation;

	$filePath = $categoriesLocation.$category."/".$filename;
	if (file_exists($filePath)) {
	    $xml = simplexml_load_file($filePath);
	    return $xml;
	}else{
	    return FALSE;
	}
}

/*
 * Save file as hour.minute.second_Seconds since the Unix Epoch_month.day.year.xml
 */
function generateFileName($category){
	global $categoriesLocation;
	
	$time = date("g.i.s_U");
	$date = date("m.d.y");
	return $time."_".$date.".xml";
}

/*
 * Returns the current date in m/d/y format
 */
function getChatDate($timestamp){
	return date("m/d/y", $timestamp);
}

/*
 * Returns the current time in h:m am/pm format
 */
function getChatTime($timestamp){
	return date("g:i a", $timestamp);
}

/*
 * Adds another <chat>...</chat> to the end of the xml file
 *
 * This is bad... our php version doesn't fully support simplexml. Right now I have to manually
 * edit the xml file to add a new message. Fix later...
 */
function appendToXML($category, $filename, $xml){
	global $categoriesLocation;

	$filePath = $categoriesLocation.$category."/".$filename;

	$file = file($filePath);
	if ($file === FALSE) return FALSE;

	// Remove last line of xml file (</post>)
	array_pop($file);

	$fp = fopen($filePath, 'w');
	if ($fp === FALSE) return FALSE;

	fwrite($fp, implode("", $file));
	fwrite($fp, $xml);
	fwrite($fp, "\n</post>");
	fclose($fp);
	return TRUE;
}

/*
 * Similar to json_encode function, but our php version does not include it so here is the alternative.
 * Escapes string for json format.
 */
function escapeJsonString($value) {
    $escapers = array("\\", "/", "\"", "\n", "\r", "\t", "\x08", "\x0c");
    $replacements = array("\\\\", "\\/", "\\\"", "\\n", "\\r", "\\t", "\\f", "\\b");
    $result = str_replace($escapers, $replacements, $value);
    return $result;
}

/*
 * Certain characters like & can break an xml file. This function escapes the string so it is xml friendly.
 */
function escapeXmlString($string) {
    return str_replace(array("&", "<", ">", "\"", "'"),
        array("&amp;", "&lt;", "&gt;", "&quot;", "&apos;"), $string);
}

/*
 * Adds the file to the recent folder. If there are more than 10 files then delete the oldest one.
 */
function addToRecent($category, $filename){
	global $categoriesLocation;

	if (file_exists($categoriesLocation."recent/".$filename)) {
		return TRUE;
	}

	// Number of files in directory without "." and ".."
	$numFiles = iterator_count(new DirectoryIterator($categoriesLocation."recent"))-2;
	if ($numFiles >= 100){
		// Set time to infinity
		$mTime = INF;

		// Name of the oldest file in directory
		$oldestFilename = '';    

		$recentFolder = dir($categoriesLocation."recent");
		while (false !== ($entry = $recentFolder->read())) {
			$filePath = $categoriesLocation."recent/".$entry;
			if (is_file($filePath)){
				$modifiedTime = filemtime($filePath);
				if ($modifiedTime < $mTime) {
					$mTime = $modifiedTime;
					$oldestFilename = $entry;
				}
			}
		}
		$recentFolder->close();

		// Delete the oldest file
		$result = unlink($categoriesLocation."recent/".$oldestFilename);
		if ($result === FALSE){
			return FALSE;
		}
	}

	$target = $categoriesLocation.$category."/".$filename;
	$link = $categoriesLocation."recent/".$filename;

	// Create a soft link in the recent folder
	return symlink($target, $link);
}
?>