<?php
ini_set('date.timezone','Asia/Shanghai');
error_reporting(E_ERROR);

$PATH = explode('example', dirname(__FILE__)) ;
$PATH = str_replace('\\', '/', $PATH[0]);

require_once $PATH."lib/WxPay.Api.php";
require_once $PATH.'lib/WxPay.Notify.php';
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
			return true;
		}
		return false;
	}
	
	/**
	 * 重写回调处理函数
	 * out_trade_no:商户订单号
	 * total_fee:操作金额（单位：分）
	 * openid:微信用户在商户对应appid下的唯一标识
	 * transaction_id:微信订单号
	 */
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

		// 处理业务逻辑BEGIN------------------------------
		F('wx_flish', $data);
		$data['out_transaction_id'] = $data['transaction_id'];
		//D('KhJoinjin')->kh_joinjin_cal($data);
		global $class, $fun;
		$class->$fun($data);
		// 处理业务逻辑END--------------------------------
		return true;
	}
}

Log::DEBUG("begin notify");
$notify = new PayNotifyCallBack();
$notify->Handle(false);
