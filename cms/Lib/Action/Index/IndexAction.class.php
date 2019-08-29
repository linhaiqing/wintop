<?php
/*
 * 首页
 *
 */
class IndexAction extends BaseAction {
    public function index(){
		//顶部广告
		$index_top_adver = D('Adver')->get_adver_by_key('index_top');
		$this->assign('index_top_adver',$index_top_adver);
		//轮播
		$index_lunbo_adver = D('Adver')->get_adver_by_key('index_right');
		$this->assign('index_lunbo_adver',$index_lunbo_adver);
		//产品
		$index_product_adver = D('Adver')->get_adver_by_key('cat_1_top');
		$this->assign('index_product_adver',$index_product_adver);
		$this->display();
    }
    public function brand(){
    	$this->display();
    }
    public function partners(){
        //轮播
        $partners_ad = D('Adver')->get_adver_by_key('partners');
        $this->assign('partners_ad',$partners_ad);
        //产品
        $this->display();
    }
    public function products(){
        //顶部广告
        $top_adver = D('Adver')->get_one_adver('cat_2_top');
        $this->assign('top_adver',$top_adver);
        //轮播
        $index_lunbo_adver = D('Adver')->get_adver_by_key('index_right');
        $this->assign('index_lunbo_adver',$index_lunbo_adver);
        //产品
        $products=M('product')->select();
        $this->assign('products',$products);
             
    	$this->display();
    }
    public function exhibition(){
        $this->display();
    }
    public function handicrafts(){
         //广告图
        $jiangxin = D('Adver')->get_adver_by_key('jiangxin');
             
        $this->assign('jiangxin',$jiangxin);
        $this->display();
    }
    public function brand_profile(){
        //轮播
        $lunbo_adver = D('Adver')->get_adver_by_key('index_center');
        $this->assign('lunbo_adver',$lunbo_adver);
        //广告图
        $brandprofile = D('Adver')->get_adver_by_key('brandprofile');
             
        $this->assign('brandprofile',$brandprofile);
             
        $this->display();
    }
    public function exhibition_profile(){
        //广告图
        $exhibitionprofile = D('Adver')->get_adver_by_key('exhibitionprofile');
             
        $this->assign('exhibitionprofile',$exhibitionprofile);
        $this->display();
    }
    public function detail(){
        //产品
        $id=I('id');
        $product=M('product')->where('id='.$id)->find();
        $this->assign('product',$product);
             
        $this->display();
    }
    public function contactus(){
    	$this->display();
    }
	
	
}