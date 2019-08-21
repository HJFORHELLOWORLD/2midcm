<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Bom extends CI_Controller {

    public function __construct(){
        parent::__construct();
		$this->purview_model->checkpurview(68);
		$this->load->model('data_model');
    }
	
	public function index(){
		$this->load->view('bom/index');
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
        $id  = intval($this->input->post('id',TRUE));
        $act = str_enhtml($this->input->get('act',TRUE));
        $info['pk_bom_id'] = $data['pk_bom_id'] = intval($this->input->post('pk_bom_id',TRUE));
        $info['bomModel']     = $data['bomModel'] = intval($this->input->post('bomModel',TRUE));
        $info['bomName']       = $data['bomName']   = str_enhtml($this->input->post('bomName',TRUE));
        $info['isVirt']     = $data['isVirt'] = str_enhtml($this->input->post('isVirt',TRUE));
        $info['bomCat_id1']   = $data['bomCat_id1'] = (float)$this->input->post('bomCat_id1',TRUE);
        $info['bomCat_id2']     = $data['bomCat_id2'] = str_enhtml($this->input->post('bomCat_id2',TRUE));
        $info['desc']  = $data['desc'] = (float)$this->input->post('desc',TRUE);
        $info['fk_unitClass_id']       = $data['fk_unitClass_id'] = str_enhtml($this->input->post('fk_unitClass_id',TRUE));
        $info['bomAttr']   = $data['bomAttr'] = (float)$this->input->post('bomAttr',TRUE);
        $info['bomAttr1']       = $data['bomAttr1']   = str_enhtml($this->input->post('bomAttr1',TRUE));
        $info['bomAttr2']       = $data['bomAttr2']   = str_enhtml($this->input->post('bomAttr2',TRUE));
        $info['bomAttr3']       = $data['bomAttr3']   = str_enhtml($this->input->post('bomAttr3',TRUE));
        $info['bomAttr4']       = $data['bomAttr4']   = str_enhtml($this->input->post('bomAttr4',TRUE));
        $info['bomAttr5']       = $data['bomAttr5']   = str_enhtml($this->input->post('bomAttr5',TRUE));
        $info['bomAttr6']       = $data['bomAttr6']   = str_enhtml($this->input->post('bomAttr6',TRUE));
        $info['bomAttr7']       = $data['bomAttr7']   = str_enhtml($this->input->post('bomAttr7',TRUE));

        /*        $info['spec1']       = $data['spec1']   = str_enhtml($this->input->post('spec1',TRUE));
                $info['spec2']       = $data['spec2']   = str_enhtml($this->input->post('spec2',TRUE));
                $info['spec3']       = $data['spec3']   = str_enhtml($this->input->post('spec3',TRUE));
                $info['spec4']       = $data['spec4']   = str_enhtml($this->input->post('spec4',TRUE));
                $info['spec5']       = $data['spec5']   = str_enhtml($this->input->post('spec5',TRUE));
                $info['spec6']       = $data['spec6']   = str_enhtml($this->input->post('spec6',TRUE));
                $info['spec7']       = $data['spec7']   = str_enhtml($this->input->post('spec7',TRUE));
                $info['spec8']       = $data['spec8']   = str_enhtml($this->input->post('spec8',TRUE));
                $info['spec9']       = $data['spec9']   = str_enhtml($this->input->post('spec9',TRUE));
                $info['spec10']       = $data['spec10']   = str_enhtml($this->input->post('spec10',TRUE));*/

        strlen($data['bomName']) < 1 && die('{"status":-1,"msg":"名称不能为空"}');
//		$info['categoryName']   = $data['categoryname'] = $this->mysql_model->db_one(CATEGORY,'(id='.$data['categoryid'].')','name');
//		$info['unitName']   = $data['unitname']     = $this->mysql_model->db_one(UNIT,'(id='.$data['unitid'].')','name');
//		!$data['categoryname'] || !$data['unitname']  && die('{"status":-1,"msg":"参数错误"}');
        /*		var_dump($info,$data);*/
        if ($act=='add') {
            $this->purview_model->checkpurview(69);
            $this->mysql_model->db_count(BOM_BASE,'(pk_bom_id="'.$data['pk_bom_id'].'")') > 0 && die('{"status":-1,"msg":"物料编号重复"}');
            $sql = $this->mysql_model->db_inst(BOM_BASE,$data);
            if ($sql) {
                $info['pk_bom_id'] = $sql;
                $this->mysql_model->db_inst(BOM_BASE,array('pk_bom_id' => $sql, 'num' => 0));
                $this->cache_model->delsome(BOM_BASE);
                $this->data_model->logs('新增商品:'.$data['bomName']);
                die('{"status":200,"msg":"success","data":'.json_encode($info).'}');
            } else {
                die('{"status":-1,"msg":"添加失败"}');
            }
        } elseif ($act=='update') {
            $this->purview_model->checkpurview(70);
            $this->mysql_model->db_count(BOM_BASE,'(pk_bom_id<>'.$id.') and (number="'.$data['number'].'")') > 0 && die('{"status":-1,"msg":"商品编号重复"}');
            $name = $this->mysql_model->db_one(BOM_BASE,'(pk_bom_id='.$id.')','name');
            $sql = $this->mysql_model->db_upd(BOM_BASE,$data,'(pk_bom_id='.$id.')');
            if ($sql) {
                $info['pk_bom_id'] = $id;
                $info['propertys'] = array();
                $this->cache_model->delsome(BOM_BASE);
                $this->data_model->logs('修改商品:'.$name.' 修改为 '.$data['name']);
                die('{"status":200,"msg":"success","data":'.json_encode($info).'}');
            } else {
                die('{"status":-1,"msg":"修改失败"}');
            }
        }
    }


//    //物料列表
//    public function lists() {
//        $v = '';
//        $data['status'] = 200;
//        $data['msg']    = 'success';
//        $page = max(intval($this->input->get_post('page',TRUE)),1);
//        $rows = max(intval($this->input->get_post('rows',TRUE)),100);
//        $key  = str_enhtml($this->input->get_post('matchCon',TRUE));
//        $stt  = str_enhtml($this->input->get_post('beginDate',TRUE));
//        $ett  = str_enhtml($this->input->get_post('endDate',TRUE));
//        $review  = str_enhtml($this->input->get_post('review',TRUE));
//        $where = '';
//        if (strlen($key)>0) {
//            $where .= ' and (a.pk_bom_id like "%'.$key.'%" or a.bomName like "%'.$key.'%" )';
//        }
//        if (strlen($stt)>0) {
//            $where .= ' and a.Create_Date>="'.$stt.'"';
//        }
//        if (strlen($ett)>0) {
//            $where .= ' and a.Create_Date<="'.$ett.' 23:59:59"';
//        }
//
//        $offset = $rows * ($page-1);
//        $data['data']['page']      = $page;
////        $list = $this->cache_model->load_data(BETWEENUNIT,'(Status=1) '.$where.' order by PK_BU_ID desc limit '.$offset.','.$rows.'');
//        $list = $this->cache_model->load_data(BOM_BASE,'(Status=1)'.$where. ' order by pk_bom_id desc limit '.$offset.','.$rows.'');
//        foreach ($list as $arr=>$row) {
//            $v[$arr]['id']           = "$" . $row['pk_bom_id'] . "$";
//            $v[$arr]['pk_bom_id'] =  $row['pk_bom_id'];
//            $v[$arr]['bomModel']  = $row['bomModel'];
//            $v[$arr]['bomName']  = $row['bomName'];
//            $v[$arr]['isVirt']       = ($row['isVirt']);
//            $v[$arr]['bomCat_id1']     = $row['bomCat_id1'];
//            $v[$arr]['bomCat_id2']     = $row['bomCat_id2'];
//            $v[$arr]['desc'] = $row['desc'];
//            $v[$arr]['fk_unitClass_id'] = $row['fk_unitClass_id'];
//            $v[$arr]['bomAttr'] = $row['bomAttr'];
//            $v[$arr]['bomAttr1'] = $row['bomAttr1'];
//            $v[$arr]['bomAttr2'] = $row['bomAttr2'];
//            $v[$arr]['bomAttr3'] = $row['bomAttr3'];
//            $v[$arr]['bomAttr4'] = $row['bomAttr4'];
//            $v[$arr]['bomAttr5'] = $row['bomAttr5'];
//            $v[$arr]['bomAttr6'] = $row['bomAttr6'];
//            $v[$arr]['bomAttr7'] = $row['bomAttr7'];
//        }
//        $data['data']['records']   = count($list);  //总条数
//        $data['data']['total']     = ceil($data['data']['records']/$rows);    //总分页数
//        $data['data']['rows']      = is_array($v) ? $v : '';
//        die(json_encode($data));
//    }

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
    public function del() {
	    $this->purview_model->checkpurview(71);
	    $id = str_enhtml($this->input->post('id',TRUE));
		if (strlen($id) > 0) {
		    $this->mysql_model->db_count(INVPU_INFO,'(goodsid in('.$id.'))')>0 && die('{"status":-1,"msg":"其中有商品发生业务不可删除"}'); 
			$this->mysql_model->db_count(INVSA_INFO,'(goodsid in('.$id.'))')>0 && die('{"status":-1,"msg":"其中有商品发生业务不可删除"}'); 
			$this->mysql_model->db_count(INVOI_INFO,'(goodsid in('.$id.'))')>0 && die('{"status":-1,"msg":"其中有商品发生业务不可删除"}'); 
		    $sql = $this->mysql_model->db_del(GOODS,'(id in('.$id.'))');   
		    if ($sql) {
			    $this->cache_model->delsome(GOODS);
				$this->data_model->logs('删除商品:ID='.$id);
				die('{"status":200,"msg":"success","data":{"msg":"","id":['.$id.']}}');
			} else {
			    die('{"status":-1,"msg":"删除失败"}');
			}
		}
	}

    /**
     * showdoc
     * @catalog 开发文档/仓库
     * @title 商品导出
     * @description 商品导出的接口
     * @method get
     * @url https://www.2midcm.com/goods/export
     * @param skey 必选 array 商品ID数组
     * @return {"status":200,"msg":"success"}
     * @return_param status string 1："200"导出成功,2:"-1"导出失败
     * @remark 这里是备注信息
     * @number 3
     */
	public function export() {
        $this->purview_model->checkpurview(72);
        sys_xls('商品明细.xls');
        $skey         = str_enhtml($this->input->get('skey',TRUE));
        $categoryid   = intval($this->input->get('assistId',TRUE));
        $where = '';
        if ($skey) {
            $where .= ' and goods like "%'.$skey.'%"';
        }
        if ($categoryid > 0) {
            $cid = $this->cache_model->load_data(CATEGORY,'(1=1) and find_in_set('.$categoryid.',path)','id');
            if (count($cid)>0) {
                $cid = join(',',$cid);
                $where .= ' and categoryid in('.$cid.')';
            }
        }
        $this->data_model->logs('导出商品');

        $data['list'] = $this->cache_model->load_data(GOODS,'(status=1) '.$where.' order by id desc');
		$this->load->view('goods/export',$data);
	}	



}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */