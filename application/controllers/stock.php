<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Stock extends CI_Controller {

    public function __construct(){
        parent::__construct();
		$this->purview_model->checkpurview(124);
		$this->load->model('data_model');
		$this->load->model('mysql_model');
        $this->uid   = $this->session->userdata('uid');
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
    public function save(){
        $act  = str_enhtml($this->input->get('act',TRUE));
        $id   = intval($this->input->post('id',TRUE));
        $data['Stock_Name'] = str_enhtml($this->input->post('name',TRUE));
        $data['Desc'] = str_enhtml($this->input->post('desc',TRUE));
        $data['Head_ID'] = intval(str_enhtml($this->input->post('head_id',TRUE)));
        if ($act=='add') {
            $this->purview_model->checkpurview(125);
            strlen($data['Stock_Name']) < 1 && die('{"status":-1,"msg":"名称不能为空"}');
            $this->mysql_model->db_count(STOCK,'(Stock_Name="'.$data['Stock_Name'].'")') > 0 && die('{"status":-1,"msg":"已存在该工作中心"}');
            $data['id'] = $this->mysql_model->db_inst(STOCK,$data);
            $data['headName'] = str_enhtml($this->input->post('head_name',TRUE));
            if ($data['id']) {
                $this->data_model->logs('新增仓库:'.$data['Stock_Name']);
                $this->cache_model->delsome(STOCK);
                die('{"status":200,"msg":"success","data":'.json_encode($data).'}');
            } else {
                die('{"status":-1,"msg":"添加失败"}');
            }
        } elseif ($act=='update') {
            $this->purview_model->checkpurview(126);
            strlen($data['Stock_Name']) < 1 && die('{"status":-1,"msg":"名称不能为空"}');
            $this->mysql_model->db_count(STOCK,'(PK_Stock_ID<>'.$id.') and (Stock_Name="'.$data['Stock_Name'].'")') > 0 && die('{"status":-1,"msg":"已存在该仓库"}');
            $data['Modify_ID'] = $this->uid;
            $data['Modify_Date'] = date('Y-m-d H:i:s',time());
            $sql = $this->mysql_model->db_upd(STOCK,$data,'(PK_Stock_ID='.$id.')');
            if ($sql) {
                $data['id'] = $id;
                $data['headName'] = str_enhtml($this->input->post('head_name',TRUE));
                $this->data_model->logs('修改仓库:'.$data['Stock_Name']);
                $this->cache_model->delsome(STOCK);
                die('{"status":200,"msg":"success","data":'.json_encode($data).'}');
            } else {
                die('{"status":-1,"msg":"修改失败"}');
            }
        }
    }

    public function lists() {
        $v = '';
        $data['status'] = 200;
        $data['msg']    = 'success';
<<<<<<< HEAD
//        $type   = intval($this->input->get('type',TRUE));
//        $skey   = str_enhtml($this->input->get('skey',TRUE));
        $page = max(intval($this->input->get_post('page',TRUE)),1);
        $rows = max(intval($this->input->get_post('rows',TRUE)),100);
        $where  = '';
//        if ($skey) {
//            $where .= ' and ( Head_ID like "%'.$skey.'%"' . ' or Stock_Name like "%'.$skey.'%"' . ')';
//        }
//        if ($type) {
//            $where .= ' and Pk_Stock_ID IN ('.$type.',4)';
//        }
=======
        $skey   = str_enhtml($this->input->get('skey',TRUE));
        $page = max(intval($this->input->get_post('page',TRUE)),1);
        $rows = max(intval($this->input->get_post('rows',TRUE)),100);
        $where  = '';
        /*        if ($skey) {
                    $where .= ' and (PK_WC_ID like "%'.$skey.'%"' . ' or WC_Name like "%'.$skey.'%"' . ')';
                }*/

>>>>>>> 75b3f7b9f9287a303b937a199d246c39842cc7d5
        $offset = $rows * ($page-1);
        $data['data']['page']      = $page;                                                      //当前页
        $data['data']['records']   = $this->cache_model->load_total(STOCK,'(1=1) '.$where.'');     //总条数
        $data['data']['total']     = ceil($data['data']['records']/$rows);                       //总分页数
<<<<<<< HEAD
        $list = $this->data_model->stockList($where,' order by PK_Stock_ID desc' );
        foreach ($list as $arr=>$row) {
            $v[$arr]['PK_Stock_ID']       = intval($row['PK_Stock_ID']);
            $v[$arr]['Stock_Name']         = $row['Stock_Name'];
            $v[$arr]['beginDate']    = 1409500800000;
            $v[$arr]['Head_ID']       = $row['Head_ID'];
            $v[$arr]['Creator_ID']       = $row['Creator_ID'];
            $v[$arr]['Create_Date']       = $row['Create_Date'];
            $v[$arr]['Desc']       = $row['Desc'];
        }
        $data['data']['rows']   = is_array($v) ? $v : '';
//        $data['data']['totalsize']  = $this->cache_model->load_total(STOCK,' '.$where.' order by PK_Stock_ID desc');
        die(json_encode($data));
    }

=======
        $list = $this->data_model->stockList($where, ' order by PK_Stock_ID desc limit '.$offset.','.$rows.'');
        // $list = $this->cache_model->load_data(WORK_CERTER,'(Status=1) '.$where.' order by PK_WC_ID desc limit '.$offset.','.$rows.'');
        foreach ($list as $arr=>$row) {
            $v[$arr]['id']           = intval($row['PK_Stock_ID']);
            $v[$arr]['Stock_Name']         = $row['Stock_Name'];
            $v[$arr]['Desc']       = $row['Desc'];
            $v[$arr]['headName']       = $row['headName'];
            $v[$arr]['Head_ID']       = $row['Head_ID'];
        }
        $data['data']['items']   = is_array($v) ? $v : '';
        $data['data']['totalsize']  = $this->cache_model->load_total(STOCK,'(1=1) '.$where.' order by PK_Stock_ID desc');
        die(json_encode($data));
    }



    //删除
    public function del(){
        $this->purview_model->checkpurview(127);
        $id = intval($this->input->post('id',TRUE));
        $data = $this->mysql_model->db_one(STOCK,'(PK_Stock_ID='.$id.')');
        if (count($data) > 0) {
            $this->mysql_model->db_count(BOM_STOCK,'(Stock_ID='.$id.')')>0 && die('{"status":-1,"msg":"发生业务不可删除"}');
            $sql = $this->mysql_model->db_del(STOCK,'(PK_Stock_ID='.$id.')');
            if ($sql) {
                $this->data_model->logs('删除仓库:ID='.$id.' 名称：'.$data['Stock_Name']);
                $this->cache_model->delsome(STOCK);
                die('{"status":200,"msg":"success"}');
            } else {
                die('{"status":-1,"msg":"修改失败"}');
            }
        }
    }


>>>>>>> 75b3f7b9f9287a303b937a199d246c39842cc7d5
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */