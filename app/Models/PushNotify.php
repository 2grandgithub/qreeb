<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PushNotify extends Model
{
    public static function user_send($tokens,$ar_text,$en_text,$type,$order_id=null,$extra=null)
    {
        $fields = array
        (
            "registration_ids" => $tokens,
            "priority" => 10,
            'data' => [
                'type' => $type,
                'ar_text' => $ar_text,
                'en_text' => $en_text,
                'order_id' => $order_id,
                'extra' => $extra
            ],
            'notification' => [
                'type' => $type,
                'ar_text' => $ar_text,
                'en_text' => $en_text,
                'order_id' => $order_id,
                'extra' => $extra
            ],
            'vibrate' => 1,
            'sound' => 1
        );
        $headers = array
        (
            'accept: application/json',
            'Content-Type: application/json',
            'Authorization: key=' .
            'AAAAN_xXn34:APA91bFWfTtrh_ykmDNb2HMlChSd-lgeqxxVRPXsj4u-JwkFfCv6ZhhMzgmcWaKyBZKqb_AOdQiQaoLfrjbuqdzzCf9uhyPXOd3nYLaupDsTrR18enyAegkOL1x8ZSNfq7_bma2-glrB'

        );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        $result = curl_exec($ch);
        //  var_dump($result);
        if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
        }
        curl_close($ch);

        return $result;
    }


    public static function tech_send($tokens,$ar_text,$en_text,$type,$order_id=null,$extra=null)
    {
        $fields = array
        (
            "registration_ids" => $tokens,
            "priority" => 10,
            'data' => [
                'type' => $type,
                'ar_text' => $ar_text,
                'en_text' => $en_text,
                'order_id' => $order_id,
                'extra' => $extra
            ],
            'notification' => [
                'type' => $type,
                'ar_text' => $ar_text,
                'en_text' => $en_text,
                'order_id' => $order_id,
                'extra' => $extra
            ],
            'vibrate' => 1,
            'sound' => 1
        );
        $headers = array
        (
            'accept: application/json',
            'Content-Type: application/json',
            'Authorization: key=' .
            'AAAAr8BfoPQ:APA91bGGJL2qYrLjXXKEGXTrZAE1elBmSbh7gI269unmU8QvAnzVPRaylxCRj9Z4UrktFCMD-xkvT5QvjlA-VA_0qj4K2hoUOnwois-ic150E3nFRs8otwRJECM1TDDOx8l8BkvdRHS1'
        );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        $result = curl_exec($ch);
        //  var_dump($result);
        if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
        }
        curl_close($ch);

        return $result;
    }
}
