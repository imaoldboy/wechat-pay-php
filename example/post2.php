<?php
        $curl = curl_init();
        $data = array('message' => 'simple message!');

        curl_setopt($curl, CURLOPT_URL, "http://localhost:8000/push");
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        curl_exec($curl);
?>
