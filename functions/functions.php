<?php
function sortArray($array, $orderBy1, $direction1, $orderBy2, $direction2){ //sorts an array by two key/value pairs. $direction variables should be either "asc" or "desc".
		//get a list of columns by which to sort
		foreach ($array as $key => $value) {
		    $sort["$orderBy2"][$key] = $value["$orderBy2"]; //column 1
		    $sort["$orderBy1"][$key] = $value["$orderBy1"]; //column 2
		}

		if (($direction1 == "asc") && ($direction2 == "asc")) { // 2*2 = 4 possible permutations if you sort by 2 columns. If you add another column to the above foreach() (if you want to sort by 3 columns, for example) 3*3 = 9 possible permutations so you would have to modify the existing permutations and add the 5 missing ones. I only sort by facilitytype and by callsign alphabetically but this is how you do it if you want to.
			array_multisort($sort["$orderBy1"], SORT_ASC, $sort["$orderBy2"], SORT_ASC, $array);
		} elseif (($direction1 == "desc") && ($direction2 == "desc")) {
			array_multisort($sort["$orderBy1"], SORT_DESC, $sort["$orderBy2"], SORT_DESC, $array);
		} elseif (($direction1 == "asc") && ($direction2 == "desc")) {
			array_multisort($sort["$orderBy1"], SORT_ASC, $sort["$orderBy2"], SORT_DESC, $array);
		} elseif(($direction1 == "desc") && ($direction2 == "asc")) {
			array_multisort($sort["$orderBy1"], SORT_DESC, $sort["$orderBy2"], SORT_ASC, $array);
		}
		
		return $array;
}

function searchByIndex(array $array, $index, $data){ //$index is the array key. $data is array value that you want to search for.
	unset($searchedRecords);
	foreach($array as $key => $value) {
		foreach($value as $k => $v) {
			if(($k == "$index") && ($v == $data)) {
				$searchedRecords[] = $array[$key];
			}
		}
	}
	if (isset($searchedRecords)) {
		return $searchedRecords;
	}
}

function search2Array(array $array, $index, $data){ //$index is the array key. $data is array value that you want to search for.
	foreach($array as $key => $value) {
		foreach($value as $k => $v) {
			if(($k == "$index") && ($v == $data)) {
				return true;
			}
		}
	}
}

function searchArray(array $array, $key, $value){ //$index is the array key. $data is array value that you want to search for.
	foreach($array as $k => $v) {
			if(($k == "$key") && ($v == $value)) {
				return true;
			}
	}
}

function o2a($object) {
	return json_decode(json_encode($object), true);
}

function a2o($array) {
	return json_decode(json_encode($array), FALSE);
}

function cacheFile($localFile, $remoteFile, $delayTime = 1800) {
	if(!file_exists($localFile) || time()-filemtime($localFile) > $delayTime) { //Checking to make sure the file is > the time allocated above. If yes, it rewrites the file already stored on disk. If not, it just reads the stored file. Change line 14 if you want more frequent updates but be reasonable, you don't want your server IP blocked from the server after all...
			if(!copy($remoteFile, $localFile)) {
				echo "Could not download datafile from the server";
			}
		}
}

function getMetar($ICAO = "EI") {
	$metarFile = file(URL . "datafiles/metar.txt");
	return implode("", preg_grep("/$ICAO/", $metarFile)); //preg_grep does a partial string match against the array for the ICAO and puts it in a temp array
}