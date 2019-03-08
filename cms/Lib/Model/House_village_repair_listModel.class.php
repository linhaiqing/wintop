<?php
class House_village_repair_listModel extends Model{
	
	public function getlist($column,$sizePage=20){
		if(!$column['village_id']){
			return '';
		}
		$condition_table  = array(C('DB_PREFIX').'house_village_repair_list'=>'r',C('DB_PREFIX').'house_village_user_bind'=>'b');
		$condition_where = " r.village_id = b.village_id  AND r.bind_id = b.pigcms_id  AND r.village_id=".$column['village_id'];
		if($column['type']){
			$condition_where .= " AND r.type = ".$column['type'];
		}
		if($column['bind_id']){
			$condition_where .= " AND r.bind_id = ".intval($column['bind_id']);
		}
		
		$condition_field = 'r.pigcms_id as pid,r.*,b.*';
		if($column['pigcms_id']){
			$condition_where .= " AND r.pigcms_id = ".intval($column['pigcms_id']);
		}
		$order = ' r.pigcms_id DESC,r.is_read ASC ';
		import('@.ORG.merchant_page');
		$count_repair = D('')->table($condition_table)->where($condition_where)->count();

		$p = new Page($count_repair,$sizePage,'page');
		$repair_list = D('')->field($condition_field)->table($condition_table)->where($condition_where)->order($order)->limit($p->firstRow.','.$p->listRows)->select();

		$return = array();
		if($repair_list){
			foreach ($repair_list as $k=>$v){
				if($v['pic']){
					$pic = explode('|', $v['pic']);
					$picArray = array();
					foreach ($pic as $picinfo){
						$picArray[] = C('config.site_url')."/upload/house/".$picinfo;
					}
					$repair_list[$k]['pic'] = $picArray;
				}
			}
			$return['pagebar'] = $p->show();
			$return['repair_list'] = $repair_list;
		}
		
		return $return;
	}
	
	 
}