<?php
require_once(__DIR__ . '/../../../config.php');
global $CFG;
require_once("$CFG->dirroot/lib/filelib.php");
require_once("$CFG->dirroot/local/webmonetization/lib/interledgerphp/receipthandler.php");

@header('Content-Type: application/spsp4+json; charset=UTF-8');

if (empty($_SERVER['HTTP_WEB_MONETIZATION_ID'])) {
    @header('HTTP/1.0 404 not found');
    echo 'HTTP_WEB_MONETIZATION_ID header missing';
    die();
}

$paymentpointer = required_param('paymentpointer', PARAM_RAW_TRIMMED);
$paymentpointer = urldecode($paymentpointer);
$paymentpointer = trim($paymentpointer, '$');
$paymentpointer = "https://$paymentpointer";

$receipthandler = new \interledgerphp\receipthandler(
        get_config('local_webmonetization', 'receiptseed')
);

$rawnonce = $receipthandler->generate_receipt_nonce();
$nonce = base64_encode($rawnonce);
$secret = base64_encode($receipthandler->generate_receipt_secret($rawnonce));
$webmonetizationid = $_SERVER['HTTP_WEB_MONETIZATION_ID'];

$curl = new curl();
$headers = [
        'accept: application/spsp4+json',
        'content-type: application/spsp4+json',
        "Web-Monetization-Id: $webmonetizationid",
        "Receipt-Nonce: $nonce",
        "Receipt-Secret: $secret"
];

$curl->setHeader($headers);

try {
    $result = $curl->get($paymentpointer);
} catch (Exception $exception) {
    @header("HTTP/1.0 404 not found");
    echo 'Error reaching payment pointer';
    die();
}

$response = $curl->getResponse();
if ($response['HTTP/2'] != 200) {
    @header('HTTP/1.0 404 not found');
    echo "Error received from payment pointer\n";
}
if ($response['content-type'] !== "application/spsp4+json") {
    @header('HTTP/1.0 404 not found');
    echo "Unexpected content type received from payment pointer\n";
}

$parsedresult = json_decode($result);
if (empty($parsedresult)) {
    @header('HTTP/1.0 404 not found');
    echo "Unable to parse response from payment pointer\n";
}

if (empty($parsedresult->receipts_enabled)) {
    @header('HTTP/1.0 409 Conflict');
    echo "Receipts_enabled not set to true\n";
}

echo $result;
die();
