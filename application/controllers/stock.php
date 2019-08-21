<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Stock extends CI_Controller {

    public function __construct(){
        parent::__construct();
		$this->purview_model->checkpurview(68);
		$this->load->model('data_model');
		$this->load->model('mysql_model');
    }
	
	public function index(){
		$this->load->view('stock/index');
	}

	public function add(){
	    $this->load->view('stock/add');
    }

    /**
     * showdoc
     * @catalog 开发文档/仓库
     * @title 商品管理新增修改
     * @description 商品添加修改的接口
     * @method get
     * @url https://www.2midcm.com/goods/save
     * @param categoryName 必选 string 商品类别
     * @param unitName 必选 string 计量单位
     * @param name 可选 string 商品名称
     * @param number 可选 string 编号
     * @param quantity 可选 string 库存数量
     * @param remark 可选 string 备注
     * @param salePrice 可选 string 销售价格
     * @return {"status":200,"msg":"success"}
     * @return_param status string 1："200"新增或修改成功,2:"-1"新增或修改失败
     * @remark 这里是备注信息
     * @number 3
     */
	public function save() {
	    $data = 


        $act = str_enhtml($this->input->get('act',TRUE));
        $info['pk_stock_id'] = $data['pk_stock_id'] = $this->input->post('pk_stock_id',TRUE);
        $info['stock_name']     = $data['stock_name'] = $this->input->post('stock_name',TRUE);
        $info['desc']       = $data['desc']   = $this->input->post('desc',TRUE);
        $info['head_id']     = $data['head_id'] = $this->input->post('head_id',TRUE);
        $info['creator_id']     = $data['creator_id'] = $this->input->post('creator_id',TRUE);
//		strlen($data['stock_id']) < 1 && die('{"status":-1,"msg":"名称不能为空"}');
//		strlen($data['head_id']) < 1  && die('{"status":-1,"msg":"请添加负责人"}');

		if ($act=='add') {
		    $this->purview_model->checkpurview(68);
			$this->mysql_model->db_count(STOCK,'(stock_name="'.$data['stock_name'].'")') > 0 && die('{"status":-1,"msg":"仓库编号重复"}');
		    $sql = $this->mysql_model->db_inst(STOCK,$data);
			if ($sql) {
			    $info['id'] = $sql;
				$this->cache_model->delsome(STOCK);
				$this->data_model->logs('新增仓库:'.$data['stock_name']);
				die('{"status":200,"msg":"success","data":'.json_encode($info).'}');
			} else {
			    die('{"status":-1,"msg":"添加失败"}');
			}
		}
	 }



    /**
     * showdoc
     * @catalog 开发文档/仓库
     * @title 商品删除
     * @description 商品删除的接口
     * @method get
     * @url https://www.2midcm.com/goods/del
     * @param id 可选 int 商品ID
     * @return {"status":200,"msg":"success"}
     * @return_param status string 1："200"删除成功,2:"-1"删除失败
     * @remark 这里是备注信息
     * @number 3
     */
//    public function del() {
//	    $this->purview_model->checkpurview(71);
//	    $id = str_enhtml($this->input->post('id',TRUE));
//		if (strlen($id) > 0) {
//		    $this->mysql_model->db_count(INVPU_INFO,'(goodsid in('.$id.'))')>0 && die('{"status":-1,"msg":"其中有商品发生业务不可删除"}');
//			$this->mysql_model->db_count(INVSA_INFO,'(goodsid in('.$id.'))')>0 && die('{"status":-1,"msg":"其中有商品发生业务不可删除"}');
//			$this->mysql_model->db_count(INVOI_INFO,'(goodsid in('.$id.'))')>0 && die('{"status":-1,"msg":"其中有商品发生业务不可删除"}');
//		    $sql = $this->mysql_model->db_del(GOODS,'(id in('.$id.'))');
//		    if ($sql) {
//			    $this->cache_model->delsome(GOODS);
//				$this->data_model->logs('删除商品:ID='.$id);
//				die('{"status":200,"msg":"success","data":{"msg":"","id":['.$id.']}}');
//			} else {
//			    die('{"status":-1,"msg":"删除失败"}');
//			}
//		}
//	}


}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */