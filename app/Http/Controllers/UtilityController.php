<?php

namespace App\Http\Controllers;

class UtilityController extends Controller {

    static function getDataCurlPost($apiEndPoint, $postData, $request) {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $apiEndPoint,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode($postData),
            CURLOPT_HTTPHEADER => array(
                "authorization: Basic " . $request->session()->get('authToken') . "",
                "cache-control: no-cache",
                "content-type: application/json",
                "postman-token: 0ad9ef7d-0383-6f21-cfed-550483cdcb87"
            ),
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        if ($err) {
            return "cURL Error #:" . $err;
        }
        else {
            return $response;
        }
    }

    static function getDataCurlGet($apiEndPoint, $request) {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $apiEndPoint,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "authorization: Basic " . $request->session()->get('authToken') . "",
                "cache-control: no-cache",
                "content-type: application/json",
                "postman-token: ddae12bb-97bf-970d-5586-5c27dcb49aec"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        if ($err) {
            return "cURL Error #:" . $err;
        }
        else {
            return $response;
        }
    }

    static function minutes($time) {
        $time = explode(':', $time);
        return round(($time[0] * 60) + ($time[1]) + ($time[2] / 60), 0);
    }

    static function helpermultiArrayToSingleArray($data) {
        $newArray = [];
        foreach ($data as $key => $value) {
            $newArray[$data[$key]['name']] = $data[$key]['value'];
        }
        return $newArray;
    }

    static function secondToHrMS($second) {
        return gmdate("H:i:s", $second);
    }

    static function getDomain($url) {
        if (strpos($url, '//') !== false) {
            $domain = explode('//', $url);
            $domain = $domain[1];
        }
        else {
            $domain = $url;
        }
        return 'https://' . $domain;
    }

}
