<?php
$lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
$olang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
$supportedLanguages = ['en', 'de', 'fr', 'es', 'nl'];
if (!in_array($lang, $supportedLanguages))
{
    $lang = 'en';
}

if (empty($apiname))
{
    $apiname = "0";
    $status = "0";
}
elseif ($apiname == "OFF-else")
{
    $apiname = "fallback OFF";
    $status = "2";
}
else
{
    $status = "1";
}

$date = date("d-m-yy");
$time = date("h:i:sa");

function isMobile()
{
    return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
}
if (isMobile())
{
    $device = "mobile";
}
else
{
    $device = "desktop";
}

$additionalArray = array(
    'scan' => array(
        'code' => $barcode,
        'response' => array(
            'status' => $status,
            'api' => $apiname
        )
    ) ,
    'metadata' => array(
        'timestamp' => array(
            'date' => $date,
            'time' => $time
        ) ,
        'debuginfo' => array(
            'device_type' => $device,
            'support' => array(
                'ticket' => $ticket
            )
        ) ,
        'localization' => array(
            'lang' => $olang,
            'supported_lang' => $lang
        )
    )
);

$data_results = file_get_contents('stats.json');
$tempArray = json_decode($data_results);
$tempArray[] = $additionalArray;
$jsonData = json_encode($tempArray);

file_put_contents('stats.json', $jsonData);
?>
