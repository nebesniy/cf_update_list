<?php

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

// Переменные

$cf_account_id = 'ACCOUNT_ID';
$cf_api_key = 'API_KEY';
$cf_list_id = 'LIST_ID';
$cf_email = 'EMAIL';
$github_blacklist_url = 'https://raw.githubusercontent.com/C24Be/AS_Network_List/main/blacklists/blacklist.txt';

// Работаем с массивом диапазонов IP

$blacklist = file_get_contents($github_blacklist_url);
if ($blacklist === false) {
    exit("Ошибка при запросе ".$github_blacklist_url);
}

$blacklist_cf_prepared = array_filter(explode(PHP_EOL, $blacklist));

$blacklist_cf_prepared = array_map(function ($value) {
    return ['ip' => $value];
}, $blacklist_cf_prepared);

$blacklist_cf_prepared = json_encode($blacklist_cf_prepared);

// Работаем с CloudFlare API

$ch = curl_init();

$cf_api_url = 'https://api.cloudflare.com/client/v4/accounts/'.$cf_account_id.'/rules/lists/'.$cf_list_id.'/items';

curl_setopt($ch, CURLOPT_URL, $cf_api_url);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
curl_setopt($ch, CURLOPT_POSTFIELDS, $blacklist_cf_prepared);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$headers = [
    'Content-Type: application/json',
    'X-Auth-Email: '.$cf_email,
    'X-Auth-Key: '.$cf_api_key
];
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

$result = curl_exec($ch);
if (curl_errno($ch)) {
    exit('Ошибка при работе с API: ' . curl_error($ch));
} else {
    echo 'OK';
}
curl_close($ch);
