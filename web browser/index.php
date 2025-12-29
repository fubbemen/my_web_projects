<?php
$search = "fab";
// get cURL resource
$ch = curl_init();

// set url
curl_setopt($ch, CURLOPT_URL, 'https://app.scrapingbee.com/api/v1/google?api_key=secret&search='."$search".'&language=en');

// set method
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');

// return the transfer as a string
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

// send the request and save response to $response
$response = curl_exec($ch);

// stop if fails
if (!$response) {
  die('Error: "' . curl_error($ch) . '" - Code: ' . curl_errno($ch));
}

echo 'HTTP Status Code: ' . curl_getinfo($ch, CURLINFO_HTTP_CODE) . PHP_EOL;

// close curl resource to free up system resources
curl_close($ch);

?>