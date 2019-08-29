<?php
/**
 * app错误码
 *
 */
class app_api
{
	public static function errorTip($code){
		$array = array(
			'10000001' => '系统错误，请稍后重试',
			'10000002' => '数据库获取数据失败',
			//店铺
			'10011001' => '店铺ID不能为空',
			'10011002' => '店铺不存在',
			'10011003' => '店铺参数不合法',
			//支付
			'10021001' => '管理员没有开启任何一种支付方式',
			'10021002' => '支付方式不存在',
			//优惠价
			'10031001' => '优惠卷不存在',
			//账户中心
			'10042001' => '手机号不能为空',
			'10042002' => '密码不能为空',
			'10044003' => '注册失败',
			'10044004' => '设备ID不能为空',
			'10044005' => '手机号已注册',
			'10044006' => '登录失败',
			'10044007' => '短信数据写入失败',
			'10044008' => '验证码不能为空',
			'10044009' => '验证码错误',
			'10044010' => '账号未登录',
			'10044011' => '账号不存在',
			'10044012' => '修改密码失败',
			'10045001' => '微信open_id不能为空',
			'10045002' => '微信union_id不能为空',
			'10045003' => '绑定微信失败',
			'10045004' => '微信注册失败',
			'10045005' => '账号不存在',
			'10045006' => '手机号绑定失败',
			'10045007' => '该手机已绑定其他账号',
			//个人中心
			'10052001' => '该用户不存在',
			'10052002' => '该地址不存在',
			'10052010' => '地址添加失败',
			'10052011' => '地址修改失败',
			'10052012' => '地址达到上限',
			'10052013' => '地址删除失败',
			'10052014' => '地址列表为空',
			//短信通道错误
			'10060001' => '提交失败',
			'10060002' => '非法ip访问',
			'10060003' => '帐号不能为空',
			'10060004' => '密码不能为空',
			'10060005' => '手机号码不能为空',
			'10060006' => '手机号码已被列入黑名单',
			'10060007' => '短信内容不能为空',
			'10060008' => '用户名或密码不正确',
			'10060009' => '账号被冻结',
			'10060010' => '剩余条数不足',
			'10060011' => '访问ip与备案ip不符',
			'10060012' => '手机格式不正确',
			'10060013' => '短信内容含有敏感字符',
			'10060014' => '签名格式不正确',
			'10060015' => '没有提交备案模板',
			'10060016' => '短信内容与模板不匹配',
			'10060017' => '短信内容超出长度限制',
			'10060018' => '您的帐户疑被恶意利用，已被自动冻结，如有疑问请与客服联系。',
			'10060019' => '验证码短信每天只能发五个',
			'10060020' => '同内容短信每天只发4次',
			//支付
			'10070001' => '订单来源无法识别',
			'10070002' => '非法订单',
			'10070003' => '支付方式未开启',
			'10070004' => '支付方式不存在',
			'10070005' => '调用支付发生错误',
			'10070006' => '订单不存在',
			//配置
			'10080001' => '终端无法识别',
			'10080002' => '版本号不能为空',
			'10080003' => '客户端类型不能为空',
			//订单
			'10100001' => '该订单不存在',
			'10100002' => '店铺ID不能为空',
			'10100003' => '购物车读取错误',
			'10100004' => '用户地址信息有误',
			'10100005' => '支付方式不能为空',
			'10100006' => '商品信息有误',
			'10100007' => '商品信息更新失败',
			'10100008' => '订单不足起送金额',
			'10100009' => '订单添加失败',
			'10100010' => '订单日志写入失败',
			'10100011' => '保存订单失败',
			'10100100' => '评论重复提交',
			'10100101' => '评论添加失败',
			'10100102' => '商品ID不能为空',
			'10100103' => '点赞失败',
			'10100201' => '红包功能已关闭',
			'10100202' => '订单ID不能为空',
			'10100203' => '红包生成失败',
			'10100301' => '后台没有开启任何支付方式',
			// 商家
			'10061001' => '商家经纬度不能为空',
			// 骑士
			'10090001' => '订单id不能为空',
			'10090002' => '配送员不存在',
			'10090003' => '还没有配送员接单',
			'10090004' => '配送员还没有开始配送',
            //  APP版O2O 启动页面配置
            '20000001' => '系统错误，请稍后重试',
            '20000002' => '数据库获取数据失败',
            '20000003' => '无数据',
            //	用户相关
            '20042001' => '手机号不能为空',
			'20042002' => '密码不能为空',
			'20042003' => '请输入有效的手机号',
			'20044003' => '注册失败',
			'20044004' => '设备ID不能为空',
			'20044005' => '手机号已注册',
			'20044006' => '登录失败',
			'20044007' => '短信数据写入失败',
			'20044008' => '验证码不能为空',
			'20044009' => '验证码错误',
			'20044010' => '账号未登录',
			'20044011' => '账号不存在',
			'20044012' => '修改密码失败',
			'20045001' => '微信open_id不能为空',
			'20045002' => '微信union_id不能为空',
			'20045003' => '绑定微信失败',
			'20045004' => '微信注册失败',
			'20045005' => '账号不存在',
			'20045006' => '手机号绑定失败',
			'20045007' => '该手机已绑定其他账号',
		);
		return $array[$code];
	}
	

}