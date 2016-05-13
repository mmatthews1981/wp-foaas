<?php
/**
 * Created by PhpStorm.
 * User: Meredith
 * Date: 5/11/2016
 * Time: 9:33 PM
 */
function getthejson($url){
    $json_url = $url;
    $ch = curl_init($json_url);
    $options = array(
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => array('Accept: application/json')
    );
    curl_setopt_array($ch, $options);
    return json_decode(curl_exec($ch), true);
}

function p_array($arr){
    echo '<pre>'.print_r($arr).'</pre>';
}