<?php
ini_set('date.timezone','Asia/Shanghai');
error_reporting(E_ERROR);

require_once "../lib/WxPay.Api.php";
require_once '../lib/WxPay.Notify.php';
require_once 'log.php';

//初始化日志
$logHandler= new CLogFileHandler("../logs/".date('Y-m-d').'.log');
$log = Log::Init($logHandler, 15);

class PayNotifyCallBack extends WxPayNotify
{
	//查询订单
	public function Queryorder($transaction_id)
	{
		$input = new WxPayOrderQuery();
		$input->SetTransaction_id($transaction_id);
		$result = WxPayApi::orderQuery($input);
		Log::DEBUG("query:" . json_encode($result));
		if(array_key_exists("return_code", $result)
			&& array_key_exists("result_code", $result)
			&& $result["return_code"] == "SUCCESS"
			&& $result["result_code"] == "SUCCESS")
		{
            if ($result["trade_state"] == "SUCCESS")
            {
                $this->setPayIsTrue($result["attach"]); 
            }
			return true;
		}
		return false;
	}
	
	//重写回调处理函数
	public function NotifyProcess($data, &$msg)
	{
		Log::DEBUG("call back:" . json_encode($data));
		$notfiyOutput = array();
		
		if(!array_key_exists("transaction_id", $data)){
			$msg = "输入参数不正确";
			return false;
		}
		//查询订单，判断订单真实性
		if(!$this->Queryorder($data["transaction_id"])){
			$msg = "订单查询失败";
			return false;
		}
		return true;
	}

    //设置支付成功
	public function setPayIsTrue($attach)
    {
        Log::DEBUG("================= set Pay is true and attach is" . $attach);
        $post_data = array(
           'setPay' => 'true',
           'product_id' => $attach,
        );
        try{
                $this->send_post($post_data);
        }
        catch(Exception $e)
        {
                echo 'Message: ' .$e->getMessage();
        }
    }

    public function send_post($post_data) {
        Log::DEBUG("================= begin send_post");
        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, 'http://localhost:3000');
        curl_setopt($curl, CURLOPT_HEADER, 1);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POST, 1);

        if (is_array($post_data))
        {
            $post_data = http_build_query($post_data, null, '&');
        }
        //curl_setopt($curl, CURLOPT_POSTFIELDS, "setPay=true");
        curl_setopt($curl, CURLOPT_POSTFIELDS, $post_data);

        //执行命令
        $data = curl_exec($curl);
        //关闭URL请求
        curl_close($curl);
         Log::DEBUG("================= end of send_post");
    }



}

Log::DEBUG("begin notify");
$notify = new PayNotifyCallBack();
$notify->Handle(false);
