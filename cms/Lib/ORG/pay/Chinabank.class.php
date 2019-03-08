<?php
class Chinabank{
	protected $order_info;
	protected $pay_money;
	protected $pay_type;
	protected $is_mobile;
	protected $pay_config;
	protected $user_info;
	
	public function __construct($order_info,$pay_money,$pay_type,$pay_config,$user_info,$is_mobile=0){
		$this->order_info = $order_info;
		$this->pay_money  = $pay_money;
		$this->pay_type   = $pay_type;
		$this->is_mobile   = $is_mobile;
		$this->pay_config = $pay_config;
		$this->user_info  = $user_info;
	}
	public function pay(){
		if(empty($this->pay_config['pay_chinabank_account']) || empty($this->pay_config['pay_chinabank_key'])){
			return array('error'=>1,'msg'=>'网银在线支付缺少配置信息！请联系管理员处理或选择其他支付方式。');
		}
		
		/*if($this->is_mobile){
			return $this->mobile_pay();
		}else{
			return $this->web_pay();
		}*/
		
		//创建支付表单并显示
		$data_vid           = trim($this->pay_config['pay_chinabank_account']);
		$data_orderid       = $this->order_info['order_type'].'_'.$this->order_info['order_id'].($this->pay_config['is_own'] ? '_1' : '');
		$data_vamount       = $this->order_info['order_total_money'];
		$data_vmoneytype    = 'CNY';
		$data_vpaykey       = trim($this->pay_config['pay_chinabank_key']);
		if($this->pay_config['is_own'] && $this->order_info['mer_id']){
			$data_vreturnurl    = C('config.site_url').'/index.php?c=Pay&a=return_url&pay_type=chinabank&is_mobile='.$this->is_mobile.'&own_mer_id='.$this->order_info['mer_id'];
		}else{
			$data_vreturnurl    = C('config.site_url').'/index.php?c=Pay&a=return_url&pay_type=chinabank&is_mobile='.$this->is_mobile;
		}
		if ($this->is_mobile == 2) {
			if($this->pay_config['is_own'] && $this->order_info['mer_id']){
				$data_vreturnurl    = C('config.site_url').'/api.php?g=Mobile&c=Pay&a=return_url&pay_type=chinabank&is_mobile=2&Device-Id=200&own_mer_id='.$this->order_info['mer_id'];
			}else{
				$data_vreturnurl    = C('config.site_url').'/api.php?g=Mobile&c=Pay&a=return_url&pay_type=chinabank&is_mobile=2&Device-Id=200';
			}
		}

		$MD5KEY = strtoupper(md5($data_vamount.$data_vmoneytype.$data_orderid.$data_vid.$data_vreturnurl.$data_vpaykey));
		
		
		$def_url  = '<span style="display:none;">';
		$def_url .= '<form  method="post" action="https://pay3.chinabank.com.cn/PayGate" id="chinabanksubmit" name="chinabanksubmit">';
		$def_url .= "<input type=HIDDEN name='v_mid' value='".$data_vid."'>";
		$def_url .= "<input type=HIDDEN name='v_oid' value='".$data_orderid."'>";
		$def_url .= "<input type=HIDDEN name='v_amount' value='".$data_vamount."'>";
		$def_url .= "<input type=HIDDEN name='v_moneytype'  value='".$data_vmoneytype."'>";
		$def_url .= "<input type=HIDDEN name='v_url'  value='".$data_vreturnurl."'>";
		$def_url .= "<input type=HIDDEN name='v_md5info' value='".$MD5KEY."'>";
		$def_url .= "<input type=HIDDEN name='remark1' value='".$remark1."'>";
		$def_url .= "<input type=submit class='button' value='去付款...'>";
		$def_url .= "</form>";
		$def_url .= "</span>";
		$def_url .= "<script>document.forms['chinabanksubmit'].submit();</script>";
		
		return array('error'=>0,'form'=>$def_url);
	}
	public function notice_url(){
		if(empty($this->pay_config['pay_chinabank_account']) || empty($this->pay_config['pay_chinabank_key'])){
			return array('error'=>1,'msg'=>'网银在线支付缺少配置信息！请联系管理员处理或选择其他支付方式。');
		}
		exit('success');
	}
	public function return_url(){
		if(empty($this->pay_config['pay_chinabank_account']) || empty($this->pay_config['pay_chinabank_key'])){
			return array('error'=>1,'msg'=>'网银在线支付缺少配置信息！请联系管理员处理或选择其他支付方式。');
		}
		
		$v_oid          = trim($_POST['v_oid']); //订单编号
		$v_pmode        = trim($_POST['v_pmode']); //支付方式
		$v_pstatus      = trim($_POST['v_pstatus']); //支付状态 20（表示支付成功）30（表示支付失败）
		$v_pstring      = trim($_POST['v_pstring']); //支付结果信息
		$v_amount       = trim($_POST['v_amount']); //订单总金额
		$v_moneytype    = trim($_POST['v_moneytype']); //币种
		$remark1        = trim($_POST['remark1' ]); //备注字段1
		$remark2        = trim($_POST['remark2' ]); //备注字段2
		$v_md5str       = trim($_POST['v_md5str' ]); //订单MD5校验码

		/**
		 * 重新计算md5的值
		*/
		$key            = $this->pay_config['pay_chinabank_key'];
		
		$md5string=strtoupper(md5($v_oid.$v_pstatus.$v_amount.$v_moneytype.$key));
		
		/* 检查秘钥是否正确 */
		if ($v_md5str==$md5string){
			if ($v_pstatus == '20'){
				$order_id_arr = explode('_',$v_oid);
					
				$order_param['pay_type'] = 'chinabank';
				$order_param['is_mobile'] = $this->is_mobile;
				$order_param['order_type'] = $order_id_arr[0];
				$order_param['order_id'] = $order_id_arr[1];
				$order_param['third_id'] = $order_id_arr[1];
				$order_param['is_own'] = intval($order_id_arr[2]);
				return array('error'=>0,'order_param'=>$order_param);
			}
		}else{
			return array('error'=>1,'msg'=>'支付时发生错误！请检查。');
		}
	}
}
?>