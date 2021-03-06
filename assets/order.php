<?php
$name = (isset($_POST['name'])) ? $_POST['name'] : '';
$phone = (isset($_POST['phone'])) ? $_POST['phone'] : '';
$address = (isset($_POST['address'])) ? $_POST['address'] : '';
$click_id = (isset($_POST['click_id']) && trim($_POST['click_id']) != '') ? $_POST['click_id'] : '61f336f05b62820041a78cb0';
$fb_pixel_id = (isset($_GET['fb_pixel_id'])) ? $_GET['fb_pixel_id'] : '732589131044526';

$domainDefault = 'https://duracore-th.fastbuy.biz/'; // for Pubs download
if ($name == '' || $phone == '') {
    errors('กรุณากรอกข้อมูลของคุณ!');
    die;
}
$data = [];
$data['name'] = urlencode(substr($name, 0, 60));
$data['phone'] = substr($phone, 0, 20);
$data['address'] = urlencode(substr($address, 0, 255));
$data['click_id'] = $click_id;
$data['fb_pixel_id'] = $fb_pixel_id;
function call_post($url = '', $data = [])
{
    $timeout = 10000;
    $http_header = [
        "content-type: application/json",
    ];
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_CONNECTTIMEOUT_MS => $timeout,
        CURLOPT_TIMEOUT_MS => $timeout,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_POSTFIELDS => json_encode($data),
        CURLOPT_HTTPHEADER => $http_header,
        CURLOPT_VERBOSE => true,
    ));
    $response = curl_exec($curl);
    curl_close($curl);
    return $response;
}
if($domainDefault != ''){
    $result = call_post($domainDefault . '/post.php', $data);
}else{
    $result = call_post( url('post.php'), $data);
}
$result = json_decode($result);
if ($result->status == 0) {
    $FBPixelId = htmlspecialchars($fb_pixel_id, ENT_QUOTES, 'utf-8');
    header("Location: https://www.bangkok-life.site/thankyou");
}
errors($result->message);
function errors($mess = '')
{
    echo '<pre>';
    print_r($mess);
    echo '</pre>';
}
function url($file)
{
    if (isset($_SERVER['HTTPS'])) {
        $protocol = ($_SERVER['HTTPS'] && $_SERVER['HTTPS'] != "off") ? "https" : "http";
    } else {
        $protocol = 'http';
    }
    return str_replace('/order.php', '/post.php',  $protocol . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF']);
}