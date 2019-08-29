<?php
/*
 * 团购管理
 *
 */
class ProductAction extends BaseAction{
    public function index(){
        $product=M('product');
        $keyword=$_GET['keyword'];
        if($keyword)$where['chinesetitle|englishtitle']=array('like','%'.$keyword.'%');
        $product_count=$product->where($where)->count();
        import('@.ORG.system_page');
        $p = new Page($product_count,20);
        $list=$product->where($where)->order('id asc')->limit($p->firstRow.','.$p->listRows)->select();
        foreach ($list as $k => $v) {
            // $product[$k]['adminname']=M('admin')->where('id='.$v['adminid'])->getfield('account');
        }
        $this->assign('product',$list);
        $pagebar=$p->show();
        $this->assign('pagebar',$pagebar);
        $this->display();
    }
     public function add() {
        $id=$_GET['id'];
        if($id){
            $product=M('product')->where('id='.$id)->find();
            $this->assign('ac',$product);
        }
        $this->display();
    }
    public function add_modify(){
        if(IS_POST){
            $_POST['addtime']=date('Y-m-d H:i:s',time());
            $_POST['desc'] = fulltext_filter($_POST['desc']);
            $_POST['r_title'] = nl2br($_POST['r_title']);
            $_POST['r_info'] = nl2br($_POST['r_info']);
            $product = M('product');
            $id=$_POST['id'];
            unset($_POST['id']);
            if($id){
                $result=$product->where('id='.$id)->data($_POST)->save();
                if($result){
                    $this->success('修改成功');
                }else{
                    $this->error('修改失败！请重试~');
                }
            }else{
                if($product->data($_POST)->add()){
                    $this->success('添加成功！');
                }else{
                    $this->error('添加失败！请重试~');
                }
            }
        }else{
            $this->error('非法提交,请重新提交~');
        }
    }
    public function ajax_upload_pic(){
        if($_FILES['imgFile']['error'] != 4){
            $image = D('Image')->handle($this->system_session['id'], 'product', 0, array('size' => 3), false);
            if (!$image['error']) {
                exit(json_encode(array('error' => 0,'url' => $image['url']['imgFile'], 'title' => $image['title']['imgFile'])));
            }
            exit(json_encode($image));
        }else{
            exit(json_encode(array('error' => 1,'message' =>'没有选择图片')));
        }
    }
     public function product_del(){
        if(IS_POST){
            $product = M('product');
            $id['id'] = intval($_POST['id']);
            $now_product = $product->where($id)->find();
            if($product->where($id)->delete()){
                $this->success('删除成功！');
            }else{
                $this->error('删除失败！请重试~');
            }
        }else{
            $this->error('非法提交,请重新提交~');
        }
    }
}