<?php
ini_set('date.timezone','Asia/Shanghai');
//error_reporting(E_ERROR);

require_once "../lib/WxPay.Api.php";
require_once "WxPay.NativePay.php";
require_once 'log.php';

//模式一
/**
 * 流程：
 * 1、组装包含支付信息的url，生成二维码
 * 2、用户扫描二维码，进行支付
 * 3、确定支付之后，微信服务器会回调预先配置的回调地址，在【微信开放平台-微信支付-支付配置】中进行配置
 * 4、在接到回调通知之后，用户进行统一下单支付，并返回支付信息以完成支付（见：native_notify.php）
 * 5、支付完成之后，微信服务器会通知支付成功
 * 6、在支付成功通知中需要查单确认是否真正支付成功（见：notify.php）
 */
$notify = new NativePay();
$url1 = $notify->GetPrePayUrl("123456789");
$machineid = "0000000031f46074";
$url3 = $notify->GetPrePayUrl("0000000031f46074");
$url4 = $notify->GetPrePayUrl("0000000038c59c04");
$url5 = $notify->GetPrePayUrl("000000001805e209");

//模式二
/**
 * 流程：
 * 1、调用统一下单，取得code_url，生成二维码
 * 2、用户扫描二维码，进行支付
 * 3、支付完成之后，微信服务器会通知支付成功
 * 4、在支付成功通知中需要查单确认是否真正支付成功（见：notify.php）
 */
$input = new WxPayUnifiedOrder();
$input->SetBody("Any where coffee");	//商品描述，必填
$input->SetAttach("test");	//附加数据
$out_trade_no = WxPayConfig::MCHID.date("YmdHis").$machineid;
echo $out_trade_no;
$input->SetOut_trade_no($out_trade_no);	//商户订单号，必填

$input->SetTotal_fee("299");	//总金额，必填
$input->SetTime_start(date("YmdHis"));	//交易起始时间
$input->SetTime_expire(date("YmdHis", time() + 600));	//交易结束时间
$input->SetGoods_tag("test3");	//商品标记
$input->SetNotify_url("http://http://www.usertech.cn/app/acpay/example/native_notify.php");	//通知地址，必填
$input->SetTrade_type("NATIVE");	//交易类型，必填
$input->SetProduct_id("123456789");	//商品id
$result = $notify->GetPayUrl($input);

$url2 = $result["code_url"];
?>

<html>
<head>
    <meta http-equiv="content-type" content="text/html;charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1" /> 
    <title>微信支付样例-退款</title>
</head>
<body>
	<div style="margin-left: 10px;color:#556B2F;font-size:30px;font-weight: bolder;">扫描支付模式一</div><br/>
	<img alt="模式一扫码支付" src="http://paysdk.weixin.qq.com/example/qrcode.php?data=<?php echo urlencode($url1);?>" style="width:150px;height:150px;"/>
	<br/><br/><br/>
<?php echo urlencode($url1);?>
	<div style="margin-left: 10px;color:#556B2F;font-size:30px;font-weight: bolder;">0000000031f46074</div><br/>
	<img alt="coffee machine" src="http://paysdk.weixin.qq.com/example/qrcode.php?data=<?php echo urlencode($url3);?>" style="width:150px;height:150px;"/>
	<br/><br/><br/>

	<div style="margin-left: 10px;color:#556B2F;font-size:30px;font-weight: bolder;">0000000038c59c04</div><br/>
	<img alt="coffee machine" src="http://paysdk.weixin.qq.com/example/qrcode.php?data=<?php echo urlencode($url4);?>" style="width:150px;height:150px;"/>
	<br/><br/><br/>

	<div style="margin-left: 10px;color:#556B2F;font-size:30px;font-weight: bolder;">000000001805e209</div><br/>
	<img alt="coffee machine" src="http://paysdk.weixin.qq.com/example/qrcode.php?data=<?php echo urlencode($url5);?>" style="width:150px;height:150px;"/>
	<br/><br/><br/>



	<div style="margin-left: 10px;color:#556B2F;font-size:30px;font-weight: bolder;">扫描支付模式二</div><br/>
	<img alt="模式二扫码支付" src="http://paysdk.weixin.qq.com/example/qrcode.php?data=<?php echo urlencode($url2);?>" style="width:150px;height:150px;"/>
    <input type="hidden" name="out_trade_no" id="out_trade_no" value="<?php echo $out_trade_no;?>" />
    <script src="assets/js/jquery-1.9.1.min.js"></script>
	<script>
        $(function(){
           setInterval(function(){check()}, 10000);  //5秒查询一次支付是否成功
        })
        function check(){
            var url = "http://www.usertech.cn/app/acpay/example/orderquery2.php";
            var out_trade_no = $("#out_trade_no").val();
            var param = {'out_trade_no':out_trade_no};
            $.post(url, param, function(data){
                data = JSON.parse(data);
                if(data['trade_state'] == "SUCCESS"){
                    alert(JSON.stringify(data));
                    alert("订单支付成功,即将跳转...");
                    window.location.href = "http://www.usertech.cn/app/acpay/example/index.php";
                }else{
                    console.log(data);
                }
            });
        }
    </script>
</body>
</html>
