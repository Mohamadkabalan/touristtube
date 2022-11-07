<?php
	



/*
	 * Allsearch functions
	 */
	 function allSearchfunctions($search, $paragraph){
		
		$finalArray = array();
		
	 	$findWordslength = findWordsLength($search, $paragraph);
		$occurenceArray = findOccurencePositions($search, $paragraph, 0);
		$beginWith = stringBeginsWith($occurenceArray, $search);
		$occNumber = findNoOfOccurence($search, $paragraph);
		$mergedArray = returnMergedarray($occurenceArray,$findWordslength);
		$densityWord = keywordsDensity($search,$paragraph);
				
		$finalArray['occNumber'] = $occNumber;
		$finalArray['beginWith'] = $beginWith;
		$finalArray['position'] = $mergedArray;
		$finalArray['density'] = $densityWord;
		
		return $finalArray;
	 }
	 
	/*
	 * get the keyword density for a certain keyword in paragraph
	 * $search is the seach criteria<br />
	 * $occNumber is the number of occurency of this keyword in a paragraph
	 * $paragraph is the destination paragraph
	 */
	 function keywordsDensity($search,$paragraph){
	 	
		$paragraph = strip_punctuation($paragraph);
		$paragraph = str_replace(" ","",$paragraph);
		$occNumber = findNoOfOccurence($search, $paragraph);
		$searchLength = intval( strlen($search) * $occNumber );
		$paragraphLength = intval( strlen($paragraph) );
		$density = ($searchLength * 100) / $paragraphLength;
		
		return $density;
		
	 }
	
	/*
	 * Sort array by matching
	*/

//Commented by Anthony Malak as asked from Charbel Khadra to remove because it's not used  27/04/2015
//<start>
//	function densitySorting($a, $b){
//		$a = $a['density'];
//		$b = $b['density'];
//		if ($a == $b){
//			// a ($a) is same priority as b ($b), keeping the same;
//			return 0;
//		}else if ($a > $b){
//			// a ($a) is higher priority than b ($b), moving b down array;
//			return -1;
//		}else {
//			// b ($b) is higher priority than a ($a), moving b up array;                
//			return 1;
//		}
//	}
//Commented by Anthony Malak as asked from Charbel Khadra to remove because it's not used  27/04/2015
//<end>
	
	/*
	 * Sort array by number
	*/
//Commented by Anthony Malak as asked from Charbel Khadra to remove because it's not used  27/04/2015
//<start>
//	function occurencySorting($a, $b){
//		$a = $a['occNumber'];
//		$b = $b['occNumber'];
//		if ($a == $b){
//			// a ($a) is same priority as b ($b), keeping the same;
//			return 0;
//		}else if ($a > $b){
//			// a ($a) is higher priority than b ($b), moving b down array;
//			return -1;
//		}else {
//			// b ($b) is higher priority than a ($a), moving b up array;                
//			return 1;
//		}
//	}
	/*
	 * Sort array by begin with string
	*/
//	function beginWithSorting($a, $b){
//		$a = $a['beginWith'];
//		$b = $b['beginWith'];
//		if ($a && $b){
//			// a ($a) is same priority as b ($b), keeping the same;
//			return 0;
//		}else if ($a && !$b){
//			// a ($a) is higher priority than b ($b), moving b down array;
//			return -1;
//		}else {
//			// b ($b) is higher priority than a ($a), moving b up array;                
//			return 1;
//		}
//	}
//Commented by Anthony Malak as asked from Charbel Khadra to remove because it's not used  27/04/2015
//<end>
	
	/*
	 * find the number of occurrence of a search in a another one<br />
	 * $search is the desired search<br />
	 * $paragraph is the destination paragraph<br />
	 * return array of occurrence | false
	 */
	function findNoOfOccurence($searchStart, $paragraphStart){
		
		$search = "/".strtolower($searchStart)."/";
		$paragraph = strip_punctuation($paragraphStart);
		
		if ( preg_match_all( $search, $paragraph, $matches ) ) {
			return count( $matches[0] );
		}else {
			return false;
		}
		
	}
	
	/*
	 * find the position occurrence of a search in a another one<br />
	 * $search is the desired search<br />
	 * $paragraph is the destination paragraphe<br />
	 * $start is the starting point of search<br />
	 * return array of positions
	 */
	function findOccurencePositions($search, $paragraph, $start){
		
		$newLine = 0;
		$linesArray = array();
		
		$search = strtolower($search);
		$paragraph = strip_punctuation($paragraph);
		
		while( ($newLine = strpos( $paragraph, $search, $start ) ) !== false){
			$start = $newLine + 1;
			array_push($linesArray,$newLine);
		}
		return $linesArray;
		
	}
	
	/*
	 * find the occurent word occurrence of the citeria in a paragraph<br />
	 * $search is the desired search<br />
	 * $paragraph is the destination paragraphe<br />
	 * return array of words and the word length
	 */
	function findWordsLength($search, $paragraph){
		
		$strippedTxt = strip_punctuation($paragraph);
		
		$words = explode(" ", $strippedTxt);
		
		if(sizeof($words)==1) return array(0=>$paragraph);
		
		$results = array();
		
		foreach($words as $word){
			$length = strlen($word);
			if(strstr($word,$search)){
				array_push($results,$word);
			}
		}
		
		return $results;
			
	}
	
	/*
	 * find if the paragraphe begins with a specified string<br />
	 * $search is the desired search<br />
	 * $occurenceArray the arrayreturned from the matched posotion<br />
	 * return true | false
	 */
	function stringBeginsWith($occurenceArray, $search){
		
		$search = '<strong>"'.$search.'"</strong>';
		//return ( $occurenceArray[0]== 0 ) ? 'The paragraphe begins with '.$search : 'The paragraphe does not begin with '.$search;
		return ( $occurenceArray[0] == 0 ) ? true : false;
		
	}
	
	/*
	 * find if two strings matches even if they are capital letters<br />
	 * $string1 is the first string<br />
	 * $string2 is the second string<br />
	 * return true | false
	 */
//Commented by Anthony Malak as asked from Charbel Khadra to remove because it's not used  27/04/2015
//<start>
//	function caseMatchStrings($string1,$string2){
//		
//		return (strcasecmp($string1, $string2) == 0)? true :false;
//		
//	}
//Commented by Anthony Malak as asked from Charbel Khadra to remove because it's not used  27/04/2015
//<end>
	
	/*
	 * remove extra spaces tab and multiple new line and punctuation from a string<br />
	 * $paragraph is the string<br />
	 * return clean paragraph | false
	 */
	function strip_punctuation( $text ){
		$text = strtolower($text);
		$urlbrackets    = '\[\]\(\)';
		$urlspacebefore = ':;\'_\*%@&?!' . $urlbrackets;
		$urlspaceafter  = '\.,:;\'\-_\*@&\/\\\\\?!#' . $urlbrackets;
		$urlall         = '\.,:;\'\-_\*%@&\/\\\\\?!#' . $urlbrackets;
	
		$specialquotes = '\'"\*<>';
	
		$fullstop      = '\x{002E}\x{FE52}\x{FF0E}';
		$comma         = '\x{002C}\x{FE50}\x{FF0C}';
		$arabsep       = '\x{066B}\x{066C}';
		$numseparators = $fullstop . $comma . $arabsep;
	
		$numbersign    = '\x{0023}\x{FE5F}\x{FF03}';
		$percent       = '\x{066A}\x{0025}\x{066A}\x{FE6A}\x{FF05}\x{2030}\x{2031}';
		$prime         = '\x{2032}\x{2033}\x{2034}\x{2057}';
		$nummodifiers  = $numbersign . $percent . $prime;
	
		return preg_replace(
			array(
			// Remove separator, control, formatting, surrogate,
			// open/close quotes.
				'/[\p{Z}\p{Cc}\p{Cf}\p{Cs}\p{Pi}\p{Pf}]/u',
			// Remove other punctuation except special cases
				'/\p{Po}(?<![' . $specialquotes .
					$numseparators . $urlall . $nummodifiers . '])/u',
			// Remove non-URL open/close brackets, except URL brackets.
				'/[\p{Ps}\p{Pe}](?<![' . $urlbrackets . '])/u',
			// Remove special quotes, dashes, connectors, number
			// separators, and URL characters followed by a space
				'/[' . $specialquotes . $numseparators . $urlspaceafter .
					'\p{Pd}\p{Pc}]+((?= )|$)/u',
			// Remove special quotes, connectors, and URL characters
			// preceded by a space
				'/((?<= )|^)[' . $specialquotes . $urlspacebefore . '\p{Pc}]+/u',
			// Remove dashes preceded by a space, but not followed by a number
				'/((?<= )|^)\p{Pd}+(?![\p{N}\p{Sc}])/u',
			// Remove consecutive spaces
				'/ +/',
			),
			' ',
			$text );
	}
	
	/*
	 * return bold string if available<br />
	 * $str is the string<br />
	 * $search is the criteria<br />
	 * return bold string | false
	 */
//Commented by Anthony Malak as asked from Charbel Khadra to remove because it's not used  27/04/2015
//<start>
//	function returnBoldstr($search,$str){
//		return str_replace($search,"<strong>".$search."</strong>",$str);
//	}
//Commented by Anthony Malak as asked from Charbel Khadra to remove because it's not used  27/04/2015
//<end>
	
	/*
	 * return array merged indexes<br />
	 * $arr1 is the first array<br />
	 * $arr2 is the seconde array<br />
	 * return array merged | false
	 */
	function returnMergedarray($arr1,$arr2){
		$finalArray = array();
		$i=0;
		foreach($arr1 as $key=>$val){
			$finalArray[$val] = $arr2[$i];
			$i++;
		}
		return $finalArray;
	}