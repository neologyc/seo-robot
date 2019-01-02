<?php

/**
 * Downloads HTML response and status code of URL.
 *
 * @param string $url URL to download
 * @param string $useragent Optional. Useragent of the request. Default can be changed in settings file
 * @return array htmlContent and statusCode.
 */
function downloadURL($url, $useragent = DEFAULT_USER_AGENT){
	$ch = curl_init($url);

	curl_setopt($ch, CURLOPT_USERAGENT, $useragent);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_HEADER, false);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
	curl_setopt($ch, CURLOPT_ENCODING , "");

	$response = curl_exec($ch);
	$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

	curl_close($ch);

	return array('statusCode' => $httpCode, 'html' => $response );
}


/**
 * logging errors
 *
 * @param string $text Description of an error
 * @param string $type
 * @return html string
 */
function logger($text, $type = "ok") {
		$htmlLog = '';
		if ( $type == "ok" ) {
				$htmlLog = "<span style='color:lime;font-weight:bold'>OK: </span><span>".$text."</span><br>";
		} else if ( $type == "error") {
				$htmlLog = "<span style='color:red;font-weight:bold'>CHYBA: </span><span>".$text."</span><br>";
				$GLOBALS['hasError'] = TRUE;
		} else if ( $type == "notice") {
				$htmlLog = "<span style='color:orange;font-weight:bold'>POZOR: </span><span>".$text."</span><br>";
		} else if ( $type == "boldInfo") {
				$htmlLog = "<br><b>".$text."</b><br>";
		} else if ( $type == "info") {
				$htmlLog = "".$text."<br>";
		}
		return $htmlLog;
}



function getRowsFromFile($filename){
  $data = file("./urls/".$filename, FILE_SKIP_EMPTY_LINES);
  $unused = array_splice($data,LIMITPERDAY-1);
  // rewrite file
  $string_data = implode($unused);
  file_put_contents("./urls/".$filename, $string_data);

  return array_splice($data,0,LIMITPERDAY);
}
