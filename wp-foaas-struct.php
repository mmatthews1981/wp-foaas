<?php

function wp_foaas_getthejson($url){
    $json_url = $url;
    $ch = curl_init($json_url);
    $options = array(
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => array('Accept: application/json')
    );
    curl_setopt_array($ch, $options);
    return json_decode(curl_exec($ch), true);
}