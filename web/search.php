<?php
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: POST, GET, DELETE, PUT, PATCH, OPTIONS');
    header('Access-Control-Allow-Headers: X-PINGOTHER, Content-Type');
    header('Access-Control-Max-Age: 1728000');
    header('Content-Length: 0');
    header('Content-Type: text/plain');
    die();
}

header('Access-Control-Allow-Origin: *');

header('Access-Control-Allow-Methods: GET, POST, OPTIONS');

header("Access-Control-Allow-Headers: X-PINGOTHER, Content-Type");

error_reporting(0);
$keyword = $_GET['keyword'];
$url = 'https://www.mapmyindia.com/api/advanced-maps/doc/sample/respatosgst.php?query=%22'.str_replace(' ', '%2B', $keyword).'%22';

$header = array();
$header[] = 'Content-length: 0';
$header[] = 'Content-type: text/html';

$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, $url);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($curl, CURLOPT_VERBOSE, 1);
curl_setopt($curl, CURLOPT_HEADER, 1);
// curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
$result = curl_exec($curl);

$http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
$header_size = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
$response_header[] = explode("\r\n", substr($result, 0, $header_size));

$body[] = substr($result, $header_size);

curl_close($curl);
//$res['url'] = $url;
if ($http_status == '200') {
    $res['status'] = 'success';
    $res['data'] = $body;
} elseif ($http_status == '400') {
    $res['status'] = 'fail';
    $res['data'] = "Bad request.";
} else {
    $res['status'] = 'fail';
    $res['data'] = str_replace("message:", "", $response_header[0][5]);
}
echo json_encode($res);
?>