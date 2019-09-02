<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Inventory extends CI_Controller {

    public function __construct(){
        parent::__construct();
		$this->purview_model->checkpurview(11);
		$this->load->model('data_model');
		$this->uid  = $this->session->userdata('uid');
		$this->name = $this->session->userdata('name');
        $this->path = $this->config->item('cache_path');
        $this->load->driver('cache', array('adapter' => 'file'));
    }
	
	public function index(){
		$this->load->view('inventory/index');
	}
	
	public function query() {
		$id  = intval($this->input->get_post('invId',TRUE));
	    $v   = '';
		$order = ' order by a.id desc';
		$where = ' and a.id='.$id;
	    $data['status'] = 200;
		$data['msg']    = 'success'; 
		$data['data']['page']        = 1;
		$data['data']['records']     = 1;                                                       
		$data['data']['total']       = 1;                                                       
		$list = $this->data_model->inventory($where,$order);  
		foreach ($list as $arr=>$row) {
		    $v[$arr]['invId']         = intval($row['id']);
			$v[$arr]['locationId']    = 0;
			$v[$arr]['qty']           = $row['qty'];
			$v[$arr]['locationName']  = $row['goods'];
		}
		$data['data']['rows']         = is_array($v) ? $v : '';
		die(json_encode($data)); 
	}


    /**
     * showdoc
     * @catalog 开发文档/仓库
     * @title 库存查询
     * @description 库存查询的接口
     * @method get
     * @url https://www.2midcm.com/inventory/lists
     * @param contactno 必选 string 供应商编号
     * @param contactid 可选 int 供应商ID
     * @param contactname 可选 string 供应商名称
     * @param billno 必选 string 单据编号
     * @param billdate 必选 int 单据日期
     * @param type 可选 int 1其他入库 2盘盈 3其他出库 4盘亏
     * @return "{"status":200,"msg":"success","data":{"categoryId":1,"goods":"true","showZero":"true"}}
     * @remark 这里是备注信息
     * @number 3
     */
	public function lists() {
		$page        = max(intval($this->input->get_post('page',TRUE)),1);
		$rows        = max(intval($this->input->get_post('rows',TRUE)),100);
//		$categoryid  = intval($this->input->get_post('categoryId',TRUE));
//		$goods       = str_enhtml($this->input->get_post('goods',TRUE));
//		$qty         = intval($this->input->get_post('showZero',TRUE));
	    $v = '';
		$where = '';
		$order = 'order by a.id desc';
	    $data['status'] = 200;
		$data['msg']    = 'success';
//		if ($categoryid > 0) {
//		    $cid = $this->cache_model->load_data(BOM_CATEGORY1,'(1=1) and find_in_set('.$categoryid.',path)','id');
//			if (count($cid)>0) {
//			    $cid = join(',',$cid);
//			    $where .= ' and a.categoryid in('.$cid.')';
//			}
//		}
//		if ($goods) {
//		    $where .= ' and a.name like "%'.$goods.'%"';
//		}
//		if ($qty>0) {
//		    $order = ' HAVING (qty<=0)';
//		}

		$offset = $rows * ($page-1);
		$data['data']['page']        = $page;
		$data['data']['records']     = 1000;                                                       //总条数
		$data['data']['total']       = ceil($data['data']['records']/$rows);                       //总分页数
		$list = $this->data_model->inventory($where,$order);  
		foreach ($list as $arr=>$row) {
			$v[$arr]['qty']          = number_format($row['qty'],2);
			$v[$arr]['locationName'] = $row['stock_id'];
			$v[$arr]['invCost']      = (float)($row['cost']);
			$v[$arr]['invNumber']    = $row['account'];
		}
		$data['data']['rows']        = is_array($v) ? $v : '';
		die(json_encode($data));
	}

    /**
     * showdoc
     * @catalog 开发文档/仓库
     * @title 盘点单据生成
     * @description 盘点生成单据的接口
     * @method get
     * @url https://www.2midcm.com/inventory/generator
     * @param postData 可选 array 盘点数据
     * @return "{"status":200,"msg":"success","data":{"type":1,"typename":"盘盈","billtype":"1"}}
     * @remark 这里是备注信息
     * @number 3
     */
	public function generator() {
	    $this->purview_model->checkpurview(12);
	    $cacheData = $this->cache->get('inventory.dataLock');
	    if($cacheData['lock'] != 1){
            die('{"status":400,"msg":"请先点击开始盘点！"}');
        }
		$data = $this->input->post('postData',TRUE);
		if (strlen($data)>0) {
		     $i = 0;
			 $n = 0;
			 $a = 0;
			 $b = 0;
			 $qty1 = 0;
			 $qty2 = 0;
			 $amount1 = 0;
			 $amount2 = 0;
			 $msg = '';
			 $v1 = array();
			 $v2 = array();
		     $data = (array)json_decode($data);
			 $this->db->trans_begin();
			 $updateArr = array();
			 if (is_array($data['entries'])) {
			     foreach ($data['entries'] as $arr=>$row) {
					 if ($row->checkInventory<0) {
					     die('{"status":400,"msg":"盘点库存要为数字，请输入有效数字！"}');
					 }
				     if ($row->change>0) {  //盘盈
					     $i += 1;
						 $qty1 += (float)$row->change;                     //盘盈总数量
						 $amount1 += $row->invCost*(float)$row->change;    //盘盈总价
					 } elseif ($row->change<0) {    //盘亏
					     $n += 1;
						 $qty2 += abs($row->change);    //盘亏总数量
						 $amount2 += $row->invCost*abs($row->change);   //盘亏总价
					 } 
				} 
			 }
			 
			 if ($i==0 && $n==0) {//没有盘盈盘亏
			     die('{"status":400,"msg":"请先进行盘点！"}');
			 }
			 //盘盈
			 if ($i>0) {
				 $info1['pk_bom_so_id']      = str_no('QTRK');
				 $info1['stock_id']    = date('Y-m-d');
				 $info1['order_id']    = date('Y-m-d');
				 $info1['type']        = 2;
				 $info1['status']    = '盘盈';
				 $info1['review_id'] = $data['description'];
				 $info1['creator_id'] = $amount1;
				 $info1['create_date']    = $qty1;
				 $info1['modify_id']    = $this->name;
				 $info1['modify_date']    = 1;
				 $invoiid = $this->mysql_model->db_inst(BOM_STOCK_ORDER,$info1);
				 if (is_array($data['entries'])) {
					 foreach ($data['entries'] as $arr=>$row) {
					     if ($row->change>0) {
							 $v1[$a]['invoiid']       = $invoiid;
							 $v1[$a]['billno']        = $info1['billno'];
							 $v1[$a]['type']          = $info1['type'];
							 $v1[$a]['billtype']      = $info1['billtype'];   
							 $v1[$a]['typename']      = $info1['typename'];
							 $v1[$a]['goodsid']       = $row->invId;
							 $v1[$a]['goodsno']       = $row->invNumber; 
							 $v1[$a]['qty']           = abs($row->change); 
							 $v1[$a]['amount']        = $row->invCost*abs($row->change); 
							 $v1[$a]['price']         = $row->invCost; 
							 $v1[$a]['billdate']      = date('Y-m-d H:i:s',time());
							 $a += 1;
							 $updateArr[] =array('bom_id' => intval($row->invId), 'num' => $row->qty+$row->change);
						 }
					} 
				 }
				 $this->mysql_model->db_inst(BOM_STOCK_ORDER,$v1);
			 }
			 //盘亏
			 if ($n>0) {
				 $info2['billno']      = str_no('QTCK');
				 $info2['billdate']    = date('Y-m-d');
				 $info2['type']        = 4;
				 $info2['typename']    = '盘亏';
				 $info2['description'] = $data['description'];
				 $info2['totalamount'] = $amount2;
				 $info2['totalqty']    = $qty2;
				 $info2['username']    = $this->name;
				 $info2['billtype']    = 2;
				 $invoiid = $this->mysql_model->db_inst(INVOI,$info2);
				 if (is_array($data['entries'])) {
					 foreach ($data['entries'] as $arr=>$row) {
					     if ($row->change>=0) {
						 } else {
							 $v2[$b]['invoiid']       = $invoiid;
							 $v2[$b]['billno']        = $info2['billno'];
							 $v2[$b]['type']          = $info2['type'];
							 $v2[$b]['billtype']      = $info2['billtype'];   
							 $v2[$b]['typename']      = $info2['typename'];
							 $v2[$b]['goodsid']       = $row->invId;
							 $v2[$b]['goodsno']       = $row->invNumber; 
							 $v2[$b]['qty']           = $row->change; 
							 $v2[$b]['amount']        = $row->invCost*abs($row->change); 
							 $v2[$b]['price']         = $row->invCost; 
							 $v2[$b]['billdate']      = $info2['billdate']; 
							 $b += 1;
                             $updateArr[] =array('bom_id' => intval($row->invId), 'num' => $row->qty+$row->change);
					     }	 
					} 
				 }
				 $this->mysql_model->db_inst(INVOI_INFO,$v2);
			 }

			 $this->mysql_model->db_upd(BOM_STOCK, $updateArr, 'bom_id');
			 $this->inventoryUnLock();
			 if ($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
				die('{"status":400,"msg":"盘点失败！"}');
			 } else {
				$this->db->trans_commit();
				$this->cache_model->delsome(BOM_BASE);
				$this->cache_model->delsome(INVOI);
				$this->cache_model->delsome(INVOI_INFO); 
				if ($i>0) {
				   $msg .= '成功生成其他入库单 单据编号为'.$info1['billno'].' ';
				}
				if ($n>0) {
				   $msg .= '其他出库单 单据编号为'.$info2['billno'].'';
				}
				$this->data_model->logs('生成盘点记录');
				die('{"status":200,"msg":"'.$msg.'"}');
			 }
		}
		die('{"status":400,"msg":"请先进行盘点！"}');
	}

    /**
     * showdoc
     * @catalog 开发文档/仓库
     * @title 库存导出
     * @description 库存导出的接口
     * @method get
     * @url https://www.2midcm.com/inventory/export
     * @param contactno 必选 string 供应商编号
     * @param contactid 可选 int 供应商ID
     * @param contactname 可选 string 供应商名称
     * @param billno 必选 string 单据编号
     * @param billdate 必选 int 单据日期
     * @param type 可选 int 1其他入库 2盘盈 3其他出库 4盘亏
     * @return "{"status":200,"msg":"success","data":{"categoryId":1,"goods":"true","showZero":"true"}}
     * @remark 这里是备注信息
     * @number 3
     */
	public function export() {
	    $this->purview_model->checkpurview(13);
	    sys_xls('盘点表.xls');
		$categoryid  = intval($this->input->get_post('categoryId',TRUE));
		$goods = str_enhtml($this->input->get_post('goods',TRUE));
		$qty = intval($this->input->get_post('showZero',TRUE));
		$where = '';
		$order = 'order by a.id desc';
		if ($categoryid > 0) {
		    $cid = $this->cache_model->load_data(CATEGORY,'(1=1) and find_in_set('.$categoryid.',path)','id'); 
			if (count($cid)>0) {
			    $cid = join(',',$cid);
			    $where .= ' and a.categoryid in('.$cid.')';
			} 
		}
		if ($qty>0) {
		    $order = ' HAVING (qty<=0)';
		}
		if ($goods)  $where .= ' and a.goods like "%'.$goods.'%"';   
		$this->data_model->logs('导出盘点记录');    
		$data['list'] = $this->data_model->inventory($where,$order);  
		$this->load->view('inventory/export',$data);
	}

    //盘点时的锁
    public function inventoryLock(){
        $data['lock'] = 1;
        $this->cache->save('inventory.datalock',$data,86400);
    }

    //盘点完解锁
    public function inventoryUnLock(){
        $cacheData = $this->cache->get('inventory.dataLock');
        $values = array();
        $bomArr = array();
        if(isset($cacheData['data']) && count($cacheData['data']) > 0){
            $dataList = $cacheData['data'];
            foreach ($dataList as $vals){
                foreach ($vals as $value){
                    if(!isset($values[$value['bom_id']])) {
                        $bomArr[] = $value['bom_id'];
                        $values[$value['bom_id']]['bom_id'] = $value['bom_id'];
                        $values[$value['bom_id']]['num'] = (float)$value['num'];
                    }else{
                        $values[$value['bom_id']]['num'] += (float)$value['num'];
                    }
                }
            }
            $bomStr = implode(',',$bomArr);
            $sql = 'select bom_id, num from '.BOM_STOCK .' where bom_id in (' . $bomStr .')';
            $list = $this->mysql_model->db_sql($sql,2);
            $updateArr = array();
            foreach ($list as $val){
                if(isset($values[$val['bom_id']])) {
                    $values[$val['bom_id']]['num'] += (float)$val['num'];
                    $updateArr[] = $values[$val['bom_id']];
                    unset($values[$val['bom_id']]);
                }
            }

            $this->db->trans_begin();
            if(count($updateArr) >1) {
                $this->mysql_model->db_upd(BOM_STOCK, $updateArr, 'bom_id');
            }
            if (count($values)>1) {
                $this->mysql_model->db_inst(BOM_STOCK, array_values($values));
            }

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                die();
            } else {
                $this->db->trans_commit();
                $this->cache_model->delsome(BOM_STOCK);
            }
        }

        $this->cache->delete('inventory.dataLock');
    }

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */