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
     * @title 物料管理新增修改
     * @description 物料添加修改的接口
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
        $info['BOMModel']     = $data['BOMModel'] = $this->input->post('BOMModel',TRUE);
        $info['BOMName']       = $data['BOMName']   = str_enhtml($this->input->post('BOMName',TRUE));
        $info['IsVirt']     = $data['IsVirt'] = intval(str_enhtml($this->input->post('IsVirt',TRUE)));
        $info['BOMCat_ID1']   = $data['BOMCat_ID1'] = intval($this->input->post('BOMCat_ID1',TRUE));
        $info['BOMCat_ID2']     = $data['BOMCat_ID2'] = intval(str_enhtml($this->input->post('BOMCat_ID2',TRUE)));
        $info['Desc']  = $data['desc'] = str_enhtml($this->input->post('Desc',TRUE));
        $info['FK_UnitClass_ID']       = $data['FK_UnitClass_ID'] = intval(str_enhtml($this->input->post('fk_unitClass_id',TRUE)));
        $attr_key = str_enhtml($this->input->post('attr_key',TRUE));
        $attr_val = str_enhtml($this->input->post('attr_val',TRUE));
        strlen($data['BOMName']) < 1 && die('{"status":-1,"msg":"名称不能为空"}');
//		$info['categoryName']   = $data['categoryname'] = $this->mysql_model->db_one(CATEGORY,'(id='.$data['categoryid'].')','name');
//		$info['unitName']   = $data['unitname']     = $this->mysql_model->db_one(UNIT,'(id='.$data['unitid'].')','name');
//		!$data['categoryname'] || !$data['unitname']  && die('{"status":-1,"msg":"参数错误"}');
        /*		var_dump($info,$data);*/

        $key = array();//新属性
        if(is_array($attr_key) && is_array($attr_val) && count($attr_key) > 0 && count($attr_val) > 0){
            $i = 0;//var_dump($attr_key,$attr_val);exit;
            foreach ($attr_key as $k => $v){
                if ($v == '' || $v == '属性名'){//无效属性名
                    if(isset($attr_val[$k]))  unset($attr_val[$k]); //将属性名不规范的属性值剔除
                }else{//有效属性值
                    if($attr_val[$k] !== '' && $attr_val[$k] !== '属性值'){
                        $key[] = $v;
                        $attrname = 'BOMAttr' . $i;
                        $data[$attrname] = $attr_val[$k];
                        $i++;
                    }
                }
            }
            if (count($key) > 0) $data['BOMAttr'] = implode('|',$key);
        }
        if ($act=='add') {
            $this->purview_model->checkpurview(69);
            $sql = $this->mysql_model->db_inst(BOM_BASE,$data);
            if ($sql) {
                $info['pk_bom_id'] = $sql;
                $this->mysql_model->db_inst(BOM_STOCK,array('BOM_ID' => $sql, 'Account' => 0));//初始化仓库
                $this->cache_model->delsome(BOM_BASE);
                $this->data_model->logs('新增商品:'.$data['BOMName']);
                die('{"status":200,"msg":"success","data":'.json_encode($info).'}');
            } else {
                die('{"status":-1,"msg":"添加失败"}');
            }
        } elseif ($act=='update') {
            $this->purview_model->checkpurview(70);
            $oldData = $this->mysql_model->db_one(BOM_BASE,'(PK_BOM_ID='.$id.')');
            if(count($oldData) < 1){
                die('{"status":-1,"msg":"不存在该数据"}');
            }
            $oldAttr = explode('|',$oldData['BOMAttr']);
            if(count($oldAttr) > 0){//若原来存在属性的，则将多余的属性值置为null
                for($i = count($key) ; $i < count($oldAttr) ; $i++ ){
                    $data['BOMAttr' . $i ] = NULL;
                }
            }

            $sql = $this->mysql_model->db_upd(BOM_BASE,$data,'(PK_BOM_ID='.$id.')');
            if ($sql) {
                $info['id'] = $id;
                $this->cache_model->delsome(BOM_BASE);
                $this->data_model->logs('修改物料:'.$id);
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
		    $this->mysql_model->db_count(BOM_DESIGN,'(UpBOM_ID in('.$id.'))')>0 && die('{"status":-1,"msg":"其中有物料发生业务不可删除"}');
            $this->mysql_model->db_count(BOM_DESIGN,'(DownBOM_ID in('.$id.'))')>0 && die('{"status":-1,"msg":"其中有物料发生业务不可删除"}');
			$this->mysql_model->db_count(BOM_STOCK,'(BOM_ID in('.$id.'))')>0 && die('{"status":-1,"msg":"其中有物料发生业务不可删除"}');
			$this->mysql_model->db_count(PURORDER_DETAIL,'(BOM_ID in('.$id.'))')>0 && die('{"status":-1,"msg":"其中有物料发生业务不可删除"}');
            $this->mysql_model->db_count(SALEORDER_DETAIL,'(BOM_ID in('.$id.'))')>0 && die('{"status":-1,"msg":"其中有物料发生业务不可删除"}');
            $this->mysql_model->db_count(STOORDER_DETAIL,'(BOM_ID in('.$id.'))')>0 && die('{"status":-1,"msg":"其中有物料发生业务不可删除"}');
		    $sql = $this->mysql_model->db_del(BOM_BASE,'(PK_BOM_ID in('.$id.'))');
		    if ($sql) {
			    $this->cache_model->delsome(BOM_BASE);
				$this->data_model->logs('删除物料:ID='.$id);
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