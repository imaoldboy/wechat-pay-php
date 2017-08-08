<?php
        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, 'http://localhost:3000');
        curl_setopt($curl, CURLOPT_HEADER, 1);
        //设置获取的信息以文件流的形式返回，而不是直接输出。
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POST, 1);


        curl_setopt($curl, CURLOPT_POSTFIELDS, "setPay=true");


        //curl_setopt($curl, CURLOPT_POSTFIELDS, $post_data);
        //curl_setopt($curl, CURLOPT_POSTFIELDS, urlencode(json_encode($post_data)));  


        //执行命令
        $data = curl_exec($curl);
        //关闭URL请求
        curl_close($curl);
        //显示获得的数据
        print_r($data);
?>
