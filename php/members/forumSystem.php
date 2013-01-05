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
	 * Returns the number of files in the "recent" directory not including "." and ".."
	 */
	function getFilesCount($folder){
		$dir = new DirectoryIterator($folder);
		$count = 0;
		foreach($dir as $file){
			if (!$file->isDot()){
				$count++;
			}
		}
		return $count;
	}

	/*
	 * Remove all files from the recent folder
	 */
	function clearFolder($folder){
		$dir = new DirectoryIterator($folder);
		foreach($dir as $file){
			if (!$file->isDot()){
				unlink($file->getPathname());
			}
		}
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
	 * Adds another <chat>...</chat> right before the </post> tag
	 */
	function appendToXML($category, $filename, $xml){
		global $categoriesLocation;
	   
	    $handle = fopen($categoriesLocation.$category."/".$filename, "r+");
	    if ($handle === FALSE) return FALSE;

	    // Move file pointer to the start of </post>
	    if (fseek($handle, -7, SEEK_END) === -1) return FALSE;

	    // Write the new content and end it with </post>
	    if (fwrite($handle, $xml."\n</post>") === FALSE) return FALSE;
	    return fclose($handle);
	}

	/*
	 * Adds the file to the recent folder. If there are more than 100 files then delete the oldest one.
	 */
	function addToRecent($category, $filename){
		global $categoriesLocation;

		$recentPath = $categoriesLocation."recent/";
		if (file_exists($recentPath.$filename)) {
			return TRUE;
		}

		// Number of files in directory without "." and ".."

		$numFiles = getFilesCount($recentPath);
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

	/*
	 * Grab recent files and put them in the recent folder if the number of files in the folder is less than 10.
	 */
	function refreshRecent(){
		global $categoriesLocation;

		$folder = $categoriesLocation."recent";
		$numFiles = getFilesCount($folder);
		if ($numFiles < 10){
			// Clear out the recent files
			clearFolder($folder);

			// Array to hold all the files
			$files = array();

			// Loop through all the files from all categories
			$directory = $categoriesLocation;
			$it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($directory));
			while($it->valid()){
			    if (!$it->isDot()){
			    	$filePath = $it->key();
			    	$fileInfo = explode("/", $it->getSubPathName());
			    	$category = $fileInfo[0];
			    	$filename = $fileInfo[1];
			        $files[filemtime($filePath)] = array($category, $filename);
			    }
			    $it->next();
			}

			// Sort all the files by their modified time
			krsort($files);

			// Put the first 100 (or less) files into the recent folder
			$count = 0;
		    foreach($files as $file){
		        if ($count >= 100) break;
		        addToRecent($file[0], $file[1]);
		        $count++;
		    }
		}
	}

	/*
	 * Prettifies an XML string into a human-readable and indented work of art 
	 * @param string $xml The XML as a string 
	 * @param boolean $html_output True if the output should be escaped (for use in HTML) 
	 *
	 * Code from: http://gdatatips.blogspot.com/2008/11/xml-php-pretty-printer.html
	 */  
	function xmlpp($xml, $html_output=false) {  
	    $xml_obj = new SimpleXMLElement($xml);  
	    $level = 4;  
	    $indent = 0; // current indentation level  
	    $pretty = array();  
	      
	    // get an array containing each XML element  
	    $xml = explode("\n", preg_replace('/>\s*</', ">\n<", $xml_obj->asXML()));  
	  
	    // shift off opening XML tag if present  
	    if (count($xml) && preg_match('/^<\?\s*xml/', $xml[0])) {  
	      $pretty[] = array_shift($xml);  
	    }  
	  
	    foreach ($xml as $el) {  
	      if (preg_match('/^<([\w])+[^>\/]*>$/U', $el)) {  
	          // opening tag, increase indent  
	          $pretty[] = str_repeat(' ', $indent) . $el;  
	          $indent += $level;  
	      } else {  
	        if (preg_match('/^<\/.+>$/', $el)) {              
	          $indent -= $level;  // closing tag, decrease indent  
	        }  
	        if ($indent < 0) {  
	          $indent += $level;  
	        }  
	        $pretty[] = str_repeat(' ', $indent) . $el;
	      }  
	    }     
	    $xml = implode("\n", $pretty);     
	    return ($html_output) ? htmlentities($xml) : $xml;
	}
?>