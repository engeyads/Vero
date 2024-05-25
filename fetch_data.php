<?php

// to make authn easier with curl
function authenticate() {
    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => "https://api.baubuddy.de/index.php/login",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => "{\"username\":\"365\", \"password\":\"1\"}",
        CURLOPT_HTTPHEADER => [
            "Authorization: Basic QVBJX0V4cGxvcmVyOjEyMzQ1NmlzQUxhbWVQYXNz",
            "Content-Type: application/json"
        ],
    ]);
    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);

    if ($err) {
        echo "cURL Error #:" . $err;
        return null;
    } else {
        $json = json_decode($response, true);
        return $json["oauth"]["access_token"];
    }
}

function fetchData() {
    // get access token using the function I created up there
    $token = authenticate();
    if ($token) {
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => "https://api.baubuddy.de/dev/index.php/v1/tasks/select",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                "Authorization: Bearer $token",
                "Content-Type: application/json"
            ],
        ]);
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if ($err) {
            echo "Error: $err";
        } else {
            echo $response;
        }
    }
}

fetchData();
?>