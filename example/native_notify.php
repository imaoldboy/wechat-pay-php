<?php
ini_set('date.timezone','Asia/Shanghai');
error_reporting(E_ERROR);

require_once "../lib/WxPay.Api.php";
require_once '../lib/WxPay.Notify.php';
require_once 'log.php';

//初始化日志
$logHandler= new CLogFileHandler("../logs/".date('Y-m-d').'.log');
$log = Log::Init($logHandler, 15);
$orderno = "";

class NativeNotifyCallBack extends WxPayNotify
{
	public function unifiedorder($openId, $product_id)
	{
        global $orderno;
		//统一下单
		$input = new WxPayUnifiedOrder();
		$input->SetBody("美式咖啡");
		$input->SetAttach($product_id);
		//$input->SetAttach("test2");
		//$input->SetOut_trade_no(WxPayConfig::MCHID.date("YmdHis"));
        $orderno = WxPayConfig::MCHID.date("YmdHis");
		$input->SetOut_trade_no($orderno);
		$input->SetTotal_fee("1");
		$input->SetTime_start(date("YmdHis"));
		$input->SetTime_expire(date("YmdHis", time() + 600));
		$input->SetGoods_tag("test");
		$input->SetNotify_url("http://www.usertech.cn/app/acpay/example/notify.php");
		//$input->SetNotify_url("http://www.usertech.cn/app/acpay/example/native_notify.php");
		$input->SetTrade_type("NATIVE");
		$input->SetOpenid($openId);
		$input->SetProduct_id($product_id);
		$result = WxPayApi::unifiedOrder($input);
		Log::DEBUG("unifiedorder:" . json_encode($result));
		return $result;
	}
	
	public function NotifyProcess($data, &$msg)
	{
		//echo "处理回调";
		Log::DEBUG("call back:" . json_encode($data));
		
		if(!array_key_exists("openid", $data) ||
			!array_key_exists("product_id", $data))
		{
			$msg = "回调数据异常";
			return false;
		}
		 
		$openid = $data["openid"];
		$product_id = $data["product_id"];
		
		Log::DEBUG("===========_____________product id is :" . $product_id);
		//统一下单
		$result = $this->unifiedorder($openid, $product_id);
		if(!array_key_exists("appid", $result) ||
			 !array_key_exists("mch_id", $result) ||
			 !array_key_exists("prepay_id", $result))
		{
		 	$msg = "统一下单失败";
		 	return false;
		 }
		
		$this->SetData("product_id", $result["product_id"]);
		$this->SetData("appid", $result["appid"]);
		$this->SetData("mch_id", $result["mch_id"]);
		$this->SetData("nonce_str", WxPayApi::getNonceStr());
		$this->SetData("prepay_id", $result["prepay_id"]);
        if($this->getRestData($product_id)){
                $this->SetData("result_code", "SUCCESS");
                $this->SetData("err_code_des", "OK");
        }else{
                $this->SetData("result_code", "SYSTEMERROR");
                $this->SetData("err_code_des", "系统繁忙,请稍后再试！");
        }
		return true;
	}

    public function getRestData($product_id){
        $url = "http://101.200.80.132:3000/api/machines/" . $product_id;
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $data = curl_exec($curl);
        curl_close($curl);
        $findme   = "status\":\"0\"";
        $pos = strpos($data, $findme);
        if ($pos === false) {
            return false;
        } else {
            return true;
        }
    }


}

Log::DEBUG("begin native_notify!");
$notify = new NativeNotifyCallBack();
$notify->Handle(true);
