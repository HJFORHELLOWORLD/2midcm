<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Invpu extends CI_Controller {

    public function __construct(){
        parent::__construct();
		$this->purview_model->checkpurview(1);
		$this->load->model('data_model');
		$this->uid  = $this->session->userdata('uid');
		$this->name = $this->session->userdata('name');

    }
	
	public function index(){
		$this->load->view('invpu/index');
	}
    /**
     * showdoc
     * @catalog 开发文档/采购
     * @title 采购新增
     * @description 采购新增的接口
     * @method get
     * @url https://www.2midcm.com/invpu/add
     * @param contactno 必选 string 供应商编号
     * @param contactid 必选 string 供应商ID
     * @param contactname 必选 string 供应商名称
     * @param billno 必选 string 单据编号
     * @param type 必选 string 1采购 2退货
     * @param billdate 必选 string 单据日期
     * @param description 必选 string 备注
     * @param goodsno 必选 string 商品编号
     * @param goodsid 必选 string 商品ID
     * @param price 必选 string 单价
     * @return "{"status":200,"msg":"success","data":'.json_encode($data).'}
     * @return_param status int 1：'200'新增成功;2："-1"新增失败
     * @remark 这里是备注信息
     * @number 1
     */
	public function add(){
	    $this->purview_model->checkpurview(2);
	    $data = $this->input->post('postData',TRUE);
		if (strlen($data)>0) {
		     $data = (array)json_decode($data);
			 (!isset($data['buId']) && $data['buId']<1) && die('{"status":-1,"msg":"请选择供应商"}');
			 $contact = $this->mysql_model->db_one(BETWEENUNIT,'(PK_BU_ID='.intval($data['buId']).')');
			 count($contact)<1 && die('{"status":-1,"msg":"请选择供应商"}');
			 $info['PK_BOM_Pur_ID']      = $data['billNo'];//str_no('P');
			 $info['Supplier_ID']   = intval($data['buId']);
			 $info['Name']   = $data['orderName'];
			 $info['PurOrder_Total']      = (float)$data['totalAmount']; //订单总金额
			 $info['PurOrder_Payment'] = $data['paymentType'];
			 $info['Creator_ID']         = $this->uid;
			 $info['Status']         = 1;

			 $this->db->trans_begin();
			 $this->mysql_model->db_inst(PURORDER,$info);
			 $v = array();
			 if (is_array($data['entries'])) {
			     foreach ($data['entries'] as $arr=>$row) {
				     $v[$arr]['PurOrder_ID']       = $info['PK_BOM_Pur_ID'];
                     $v[$arr]['PurOrder_De']     = str_pad($arr+1,5,"0",STR_PAD_LEFT);
					 $v[$arr]['BOM_ID']       = intval($row->invId);
					 $v[$arr]['BOM_Accountt']           = (float)$row->qty;
					 $v[$arr]['Pur_SubTotal']        = (float)$row->amount;
					 $v[$arr]['Pur_Price']         = (float)$row->price;
					 $v[$arr]['Creator_ID']  = $this->uid;
				} 
			 }
			 $this->mysql_model->db_inst(PURORDER_DETAIL,$v);
			 if ($this->db->trans_status() === FALSE) {
			    $this->db->trans_rollback();
				die();
			 } else {
			    $this->db->trans_commit();
				$this->cache_model->delsome(BOM_BASE);
				$this->cache_model->delsome(PURORDER);
				$this->cache_model->delsome(PURORDER_DETAIL);
				$this->data_model->logs('新增采购单：'.$info['PK_BOM_Pur_ID']);
				die('{"status":200,"msg":"success","data":{"id":"'.$info['PK_BOM_Pur_ID'].'"}}');
			 }
		} else {
		    $data['billno'] = str_no('P');
		    $this->load->view('invpu/add',$data);
		}
	}


    /**
     * showdoc
     * @catalog 开发文档/采购
     * @title 采购修改
     * @description 采购修改的接口
     * @method get
     * @url https://www.2midcm.com/invpu/edit
     * @param contactno 必选 string 供应商编号
     * @param contactid 必选 string 供应商ID
     * @param contactname 必选 string 供应商名称
     * @param billno 必选 string 单据编号
     * @param type 必选 string 1采购 2退货
     * @param billdate 必选 string 单据日期
     * @param description 必选 string 备注
     * @param goodsno 必选 string 商品编号
     * @param goodsid 必选 string 商品ID
     * @param price 必选 string 单价
     * @return "{"status":200,"msg":"success","data":'.json_encode($data).'}
     * @return_param status int 1：'200'修改成功;2："-1"修改失败
     * @remark 这里是备注信息
     * @number 1
     */
	public function edit(){
	    $this->purview_model->checkpurview(3);
	    $id   = $this->input->get('id',TRUE);
	    $data = $this->input->post('postData',TRUE);
		if (strlen($data)>0) {
		     $data = (array)json_decode($data);
			 !isset($data['id']) && die('{"status":-1,"msg":"参数错误"}');
            $invpuData = $this->mysql_model->db_select(PURORDER,'(PK_BOM_Pur_ID= "'.$data['id']. '")');

            //判断是否采购计划生成的，记录日志
            $oldArr = array();
            if($invpuData[0]['Name'] == '采购计划生成的采购单' && $invpuData[0]['Supplier_ID'] == 0){
                $oldData = $this->mysql_model->db_select(PURORDER_DETAIL,'(PurOrder_ID= "'. $data['billNo'] .'")');
                foreach ($oldData as $key => $val){
                    $oldArr[intval($val['BOM_ID'])] = $val['BOM_Accountt'];
                }
            }

            if(in_array($invpuData[0]['Status'], array(2,5,9))) die('{"status":-1,"msg":"该采购单已审核通过，不能修改！"}');
            if(count($invpuData) > 1) die('{"status":-1,"msg":"采购单已存在"}');

			 //(!isset($data['buId']) && $data['buId']<1) && die('{"status":-1,"msg":"请选择采购单位"}');
			 //$contact = $this->mysql_model->db_one(BETWEENUNIT,'(PK_BU_ID='.intval($data['buId']).')');
			//count($contact)<1 && die('{"status":-1,"msg":"请选择采购单位"}');
            $info['PK_BOM_Pur_ID']      = $data['billNo'];
            $info['Supplier_ID']   = intval($data['buId']);
            $info['Name']   = $data['orderName'];
            $info['PurOrder_Total']      = (float)$data['totalAmount']; //订单总金额
            $info['PurOrder_Payment'] = $data['paymentType'];
            $info['Modify_ID']         = $this->uid;
            $info['Modify_Date']    = date('Y-m-d H:i:s',time());
            $info['Status']         = 1;
			 $v = array();
			 $this->db->trans_begin();
			 //$this->mysql_model->db_count(INVPU,'(id<>'.$id.') and (billno="'.$info['billno'].'")')>0 && die('{"status":-1,"msg":"购货单已存在"}');

            $changeStr = '';

            $this->mysql_model->db_upd(PURORDER,$info,'(PK_BOM_Pur_ID= "'.$info['PK_BOM_Pur_ID'].'")');
            $this->mysql_model->db_del(PURORDER_DETAIL,'(PurOrder_ID= "'.$info['PK_BOM_Pur_ID'].'")');
			 if (is_array($data['entries'])) {
			     foreach ($data['entries'] as $arr=>$row) {
				     $v[$arr]['PurOrder_ID']        = $info['PK_BOM_Pur_ID'];
                     $v[$arr]['PurOrder_De']     = str_pad($arr+1,5,"0",STR_PAD_LEFT);
					 $v[$arr]['BOM_ID']   = $row->invId;
					 $v[$arr]['BOM_Accountt']          = $row->qty;
					 $v[$arr]['Pur_Price']       = $row->price;
					 $v[$arr]['Pur_SubTotal']           = $row->amount;
					 $v[$arr]['Creator_ID']        = $invpuData[0]['Creator_ID'];
					 $v[$arr]['Create_Date']         = $invpuData[0]['Create_Date'];
					 $v[$arr]['Modify_ID']  = $info['Modify_ID'] ;
					 $v[$arr]['Modify_Date']   = $info['Modify_Date'];

					 if(count($oldArr) > 0 && isset($oldArr[intval($row->invId)])){
					         if((float)$row->qty != (float)$oldArr[intval($row->invId)]){ //和原来的采购计划数量不同
					             $changeStr .= '物品' . $row->invId . '：数量从'. $oldArr[intval($row->invId)] . '改为' . $row->qty .'；';
                                 unset($oldArr[intval($row->invId)]);
					         }else{
                                 unset($oldArr[intval($row->invId)]);
                             }


                     }else if(!isset($oldArr[intval($row->invId)])){
                         $changeStr .= '新增物品' . $row->invId . '数量为'. $row->qty .'；';
                         unset($oldArr[intval($row->invId)]);
                     }
				}
				if (count($oldArr) > 0){
			         $changeStr .= '删除了：' . json_encode($oldArr);
                }
			 }
			 $this->mysql_model->db_inst(PURORDER_DETAIL,$v);
			 if ($this->db->trans_status() === FALSE) {
			    $this->db->trans_rollback();
				die('');
			 } else {
			    $this->db->trans_commit();
				$this->cache_model->delsome(BOM_BASE);
				$this->cache_model->delsome(PURORDER);
				$this->cache_model->delsome(PURORDER_DETAIL);
				$logStr = $changeStr == '' ? '修改采购单 订单编号：'.$info['PK_BOM_Pur_ID'] : '修改采购单 订单编号：'.$info['PK_BOM_Pur_ID'] . ',与原采购计划不同：' . $changeStr;
                $this->data_model->logs($logStr);
				die('{"status":200,"msg":"success","data":{"id":"'.$info['PK_BOM_Pur_ID'].'"}}');
			 }
		} else {
		    $data = $this->mysql_model->db_one(PURORDER,'(PK_BOM_Pur_ID= "'.$id.'")');
			if (count($data)>0) {
				$this->load->view('invpu/edit',$data);
			} else {
			    $data['billno'] = str_no('P');
			    $this->load->view('invpu/add',$data);
			}
		}
	}
	
	//修改单据数据回显
	public function info(){
	    $id   = $this->input->get_post('id',TRUE);
		//$data = $this->mysql_model->db_one(PURORDER,'(id='.$id.')');
        $list = $this->data_model->purOrderList('and PK_BOM_Pur_ID = "' . $id . '"');
		if (count($list)>0) {
		    $data = $list[0];
			$v = '';
			$info['status'] = 200;
			$info['msg']    = 'success'; 
			$info['data']['id']                 = $data['PK_BOM_Pur_ID'];
			$info['data']['buId']               = intval($data['Supplier_ID']);
			$info['data']['contactName']        = $data['Supplier_Name'];
			$info['data']['date']               = $data['Create_Date'];
			$info['data']['billNo']             = $data['PK_BOM_Pur_ID'];
			//$info['data']['totalQty']           = (float)$data['totalqty'];
			$info['data']['totalAmount']        = (float)abs($data['PurOrder_Total']);
			$info['data']['userName']           = $data['Username'];
			$info['data']['status']             = 'edit';
            $info['data']['PK_BOM_Pur_ID']      = $data['PK_BOM_Pur_ID'];//str_no('P');
            $info['data']['Supplier_ID']   = intval($data['Supplier_ID']);
            $info['data']['orderName']   = $data['orderName'];
            $info['data']['paymentType'] = $data['PurOrder_Payment'];
            $info['data']['Creator_ID']         = $this->uid;
			$list = $this->data_model->invpu_info(' and (a.PurOrder_ID= "'.$id.'")','order by id desc');
			foreach ($list as $arr=>$row) {
				$v[$arr]['invSpec']           = $row['BOMModel'];
				$v[$arr]['goods']             = $row['BOMName'].' '.$row['BOMModel'];
				$v[$arr]['invName']      = $row['BOMName'];
				$v[$arr]['qty']          = (float)abs($row['BOM_Accountt']);
				$v[$arr]['price']       = (float)abs($row['Pur_Price']);
				$v[$arr]['mainUnit']     = $row['unitName'];
				$v[$arr]['PK_BOM_ID']        = intval($row['PK_BOM_ID']);
				$v[$arr]['unitId']       = intval($row['FK_UnitClass_ID']);
				$v[$arr]['amount'] = (float)abs($row['Pur_SubTotal']);//小计
			}
			$info['data']['entries']     = is_array($v) ? $v : '';
			$info['data']['accId']       = 0;
			$info['data']['accounts']    = array();
			die(json_encode($info));
		} else { 
			alert('参数错误');
		}
	}
	
	
	//采购单列表
	public function lists() {
	    $v = '';
	    $data['status'] = 200;
		$data['msg']    = 'success'; 
		$page = max(intval($this->input->get_post('page',TRUE)),1);
		$rows = max(intval($this->input->get_post('rows',TRUE)),100);
		$key  = str_enhtml($this->input->get_post('matchCon',TRUE));
        $review  = str_enhtml($this->input->get_post('review',TRUE));
		$stt  = str_enhtml($this->input->get_post('beginDate',TRUE));
		$ett  = str_enhtml($this->input->get_post('endDate',TRUE));
		$where = '';
		if (strlen($key)>0) {
		    $where .= ' and (a.PK_BOM_Pur_ID like "%'.$key.'%" or a.Name like "%'.$key.'%" or a.PurOrder_Payment like "%'.$key.'%")';
		}
		if (strlen($stt)>0) {
		    $where .= ' and a.Create_Date>="'.$stt.'"';
		}
		if (strlen($ett)>0) {
		    $where .= ' and a.Create_Date<="'.$ett.' 23:59:59"';
		}
		if (intval($review) > 0){
		    $where .= ' and a.Status = ' .$review;
        }else{
		    $where .= ' and a.Status != 0';   //排除掉是采购计划的数据
        }

		$offset = $rows * ($page-1);
		$data['data']['page']      = $page;
/*		$data['data']['records']   = $this->cache_model->load_total(PURORDER,'(1=1) '.$where);   //总条数
		$data['data']['total']     = ceil($data['data']['records']/$rows);    //总分页数*/
		//$list = $this->cache_model->load_data(PURORDER,'(1=1) '.$where.' order by id desc limit '.$offset.','.$rows.'');
		$list = $this->data_model->purOrderList($where, ' order by a.Create_Date desc limit '.$offset.','.$rows.'');
		foreach ($list as $arr=>$row) {
            $v[$arr]['id']           = "$" . $row['PK_BOM_Pur_ID'] . "$";
			$v[$arr]['PK_BOM_Pur_ID'] =  $row['PK_BOM_Pur_ID'];
			$v[$arr]['Supplier_Name']  = $row['Supplier_Name'];
			$v[$arr]['orderName']  = $row['orderName'];
            $v[$arr]['PurOrder_Total']       = (float)abs($row['PurOrder_Total']);
			$v[$arr]['Create_Date']     = $row['Create_Date'];
			$v[$arr]['Username']     = $row['Username'];
			$v[$arr]['PurOrder_Payment'] = $row['PurOrder_Payment'];
			$v[$arr]['reviewDes'] = $row['Status'] == 1 ? '未审核' : ($row['Status'] == 3 ? '审核不通过' : '审核通过');
            $v[$arr]['review'] = $row['Status'];
		}
        $data['data']['records']   = count($list);  //总条数
        $data['data']['total']     = ceil($data['data']['records']/$rows);    //总分页数
		$data['data']['rows']      = is_array($v) ? $v : '';
		die(json_encode($data));
	}

    /**
     * showdoc
     * @catalog 开发文档/采购
     * @title 采购导出
     * @description 采购导出的接口
     * @method get
     * @url https://www.2midcm.com/invpu/export
     * @param id 必选 int 采购单ID
     * @return "{"status":200,"msg":"success","id":"1"}
     * @return_param status int 1：'200'导出成功;2："-1"导出失败
     * @remark 这里是备注信息
     * @number 1
     */
	public function export() {
	    $this->purview_model->checkpurview(5);
		sys_xls('采购记录.xls');
		$id  = str_enhtml($this->input->get_post('id',TRUE));
		if (strlen($id)>0) {
			$data['list1'] = $this->data_model->purOrderList(' and (a.PK_BOM_Pur_ID in('.str_replace("$",'"',$id).'))');
			$data['list2'] = $this->data_model->invpu_info(' and (a.PurOrder_ID in('.str_replace("$",'"',$id).'))');
			$this->data_model->logs('导出采购记录');
			$this->load->view('invpu/export',$data);
		}	
	}

    /**
     * showdoc
     * @catalog 开发文档/采购
     * @title 采购删除
     * @description 采购删除的接口
     * @method get
     * @url https://www.2midcm.com/invpu/del
     * @param id 必选 int 采购单ID
     * @return "{"status":200,"msg":"success","id":"1"}
     * @return_param status int 1：'200'删除成功;2："-1"删除失败
     * @remark 这里是备注信息
     * @number 1
     */
    public function del() {
	    $this->purview_model->checkpurview(4);
	    $id   = $this->input->get('id',TRUE);
		$data = $this->mysql_model->db_one(PURORDER,'(PK_BOM_Pur_ID= "'.$id.'")');
		if (count($data)>0) {
		    $this->db->trans_begin();
			$this->mysql_model->db_del(PURORDER,'(PK_BOM_Pur_ID= "'.$id.'")');
			$this->mysql_model->db_del(PURORDER_DETAIL,'(PurOrder_ID= "'.$id.'")');
			if ($this->db->trans_status() === FALSE) {
			    $this->db->trans_rollback();
				die('{"status":-1,"msg":"删除失败"}');
			} else {
			    $this->db->trans_commit();
				$this->cache_model->delsome(PURORDER);
				$this->cache_model->delsome(PURORDER_DETAIL);
				$this->data_model->logs('删除采购单 订单编号：'.$data['PK_BOM_Pur_ID']);
			    die('{"status":200,"msg":"success"}');	 
			}
		}
		die('{"status":-1,"msg":"删除失败"}');
	}

    /**
     * showdoc
     * @catalog 开发文档/采购
     * @title 采购计划定时任务
     * @description 采购计划定时任务的接口
     * @url https://www.2midcm.com/invpu/purchasePlan
     * @return
     */
    public function purchasePlan(){
        //计算昨日16:00-今日16:00的所有销售单所需产品数量与仓库产成品数量作差，算出目前缺多少产成品AList---一条sql得出
        //根据AList的名单，开始算他们的BOM树，最后得出所需物料一共是多少，对比仓库+原料要求量，算出目前缺多少物料BList
        //最终将BList输出到当天16:00生成的采购计划里
        //Q:生成采购计划的时候是否要对出入库做个限制？

/*        $today = date('Y-m-d',time()) ." 16:00:00";
        $yesterday = date('Y-m-d', strtotime("-1 day")) . " 16:00:00";
        $sql = 'SELECT sale.bom_id AS bom_id , (CASE WHEN IFNULL(stock.num,0) > sale.qty THEN 0 ELSE sale.qty-IFNULL(stock.num,0) END ) AS needs FROM 
(SELECT b.goodsid AS bom_id, SUM(b.qty) AS qty
 FROM '. INVSA .' a 
 LEFT JOIN '. INVSA_INFO .' b 
 ON a.id = b.invsaid 
 WHERE a.review = 1 AND a.billdate>= "'.$yesterday.'" AND a.billdate <= "'.$today.'" GROUP BY goodsid) sale
 LEFT JOIN '. BOM_STOCK .' stock
 ON sale.bom_id= stock.bom_id GROUP BY sale.bom_id';
        $needsList = $this->mysql_model->db_sql($sql,2);//昨天16点至今日16点销售货物与仓库数量作差后仍需数量列表

        //bom设计列表
        $sql = 'SELECT up_bom_id AS parentId, down_bom_id AS childId ,down_bom_number AS num  FROM '.BOM_DESIGN;
        $bomList =$this->mysql_model->db_sql($sql,2);
        $bomArr = array();
        foreach ($bomList as $val){
            $bomArr[$val['parentId']][]=array($val['childId'] => $val['num']);//上位物料指向下位物料Arr,下位物料指向对应所需数量
        }
        //库存列表
        $sql = 'SELECT bom_id , IFNULL(num,0) as num FROM  '. BOM_STOCK;
        $stockList = $this->mysql_model->db_sql($sql,2);
        $stockArr = array();
        foreach ($stockList as $val){
            $stockArr[$val['bom_id']] = $val['num'];//bomid指向各自的库存数
        }
        $list = array();
        foreach ($needsList as $val){
            $number = $val['needs'];//缺的数
            //凉凉。。。不知道有多少层。。搞不下去啊

        }*/
    $date = date('Y-m-d',time());
    if($this->input->get_post('type', TRUE) == 2){
        $date = '';
    }
    $planRes = $this->data_model->purchasePlan($date);
    if($planRes['code'] == 1 && $planRes['data']['@PlanRes'] == 0){
        die('{"status":200,"msg":"success"}');
    }else{
        die('{"status":-1,"msg":"实时更新失败"}');
    }

    }


    public function purchaseIndex(){
        $this->load->view('invpu/purchasePlanList');
    }

    /**
     * showdoc
     * @catalog 开发文档/采购
     * @title 采购计划信息
     * @description 采购计划信息接口
     * @method get
     * @url https://www.2midcm.com/invpu/purchaseInfo
     * @return
     */
    public function purchasePlanInfo()
    {
        $data['status'] = 200;
        $data['msg']    = 'success';

        $planId = $this->input->get('id', TRUE);
        if ($planId) {
            $list = $this->data_model->invpu_info('and a.PurOrder_ID = "' . $planId .'"');
            $v = array();
            foreach ($list as $arr=>$row) {
                $v[$arr]['Create_Date'] = $row['Create_Date'];
                $v[$arr]['BOM_Accountt'] = (float)abs($row['BOM_Accountt']);
                $v[$arr]['BOMName'] = $row['BOMName'];
                $v[$arr]['unitName'] = $row['unitName'];
                $v[$arr]['BOMModel'] = $row['BOMModel'];
            }
            die(json_encode($v));
        }else{
            $page = max(intval($this->input->get_post('page',TRUE)),1);
            $rows = max(intval($this->input->get_post('rows',TRUE)),100);
            $stt  = str_enhtml($this->input->get_post('beginDate',TRUE));
            $ett  = str_enhtml($this->input->get_post('endDate',TRUE));
            $where = 'and Status = 0';//0为采购计划
            if (strlen($stt)>0) {
                $where .= ' and Create_Date>="'.$stt.'"';
            }
            if (strlen($ett)>0) {
                $where .= ' and Create_Date<="'.$ett.' 23:59:59"';
            }

            $offset = $rows * ($page-1);
            $data['data']['page']      = $page;
            $data['data']['records']   = $this->cache_model->load_total(PURORDER,'(1=1) '.$where);   //总条数
            $data['data']['total']     = ceil($data['data']['records']/$rows);    //总分页数
            $list = $this->cache_model->load_data(PURORDER,'(1=1) '.$where .' order by Create_Date desc limit '.$offset.','.$rows.'');
            $data['data']['rows']      = $list;
            die(json_encode($data));
        }
    }

    //生成购货单
    public function planImport(){
        $this->purview_model->checkpurview(95);

        $planId   = $this->input->get_post('planId',TRUE);
        $list = $this->mysql_model->db_select(PURORDER, 'PK_BOM_Pur_ID = "' . $planId . '" and Status = 0');
        if (count($list)>0) {
            $info['PK_BOM_Pur_ID']      = str_no('P');
            $info['Create_Date']    = date('Y-m-d H:i:s',time());
            //$info['totalamount'] = 0;
            //$info['totalqty']    = 0;
            //$info['description'] = $list[0]['create_time'].' 采购计划生成的购货单';
            $info['Creator_ID']         = $this->uid;
            $info['Status'] = 1;
            $info['Name'] = '采购计划生成的采购单';
            $this->db->trans_begin();
            $this->mysql_model->db_upd(PURORDER,$info, 'PK_BOM_Pur_ID = "'. $planId .'"');
            $this->mysql_model->db_upd(PURORDER_DETAIL, array('PurOrder_ID' => $info['PK_BOM_Pur_ID']), 'PurOrder_ID = "'. $planId .'"');
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                die();
            } else {
                $this->db->trans_commit();
                $this->cache_model->delsome(PURORDER);
                $this->data_model->logs('新增采购单 订单编号：'.$info['PK_BOM_Pur_ID']. '（来自' . $list[0]['Create_Date'] . '生成的采购计划：' . $list[0]['PK_BOM_Pur_ID']. '）');
                die('{"status":200,"msg":"success","data":{"id":"'.$info['PK_BOM_Pur_ID'].'"}}');
            }
        }else {
            die('{"status":-1,"msg":"无法生成该采购计划（刷新页面看是否已生成采购单）"}');
        }
    }

    //编辑采购计划（弃用）
    public function planEdit()
    {
        $this->purview_model->checkpurview(101);
        $planId = $this->input->get_post('id', TRUE);
        $data = $this->input->post('postData',TRUE);
        if (strlen($data)>0){
            $data = (array)json_decode($data,true);
            if(count($data) > 0){
                $info = array();
                $planId = $data['entries'][0]['planId'];
                foreach ($data['entries']  as $k => $val){
                    $info[$k]['id'] = $val['id'];
                    $info[$k]['extra_qty'] = $val['extraQty'];
                    $info[$k]['reason'] = $val['reason'];
                    $info[$k]['total_qty'] = $val['totalQty'];
                    $info[$k]['update_time'] = date('Y-m-d H:i:s',time());;
                    $info[$k]['editorId'] = $this->name;
                }

                $this->db->trans_begin();
                $this->mysql_model->db_upd(PURCHASE_PLAN, $info, 'id');
                if ($this->db->trans_status() === FALSE) {
                    $this->db->trans_rollback();
                    die();
                } else {
                    $this->db->trans_commit();
                    $this->cache_model->delsome(PURCHASE_PLAN);
                    $this->data_model->logs($this->name.'编辑了编号为：'.$planId . '的采购计划');
                    die('{"status":200,"msg":"编辑成功"}');
                }
            }
        }else{
            $list = $this->data_model->invpu_info('and a.PurOrder_ID = "' . $planId .'"');
            foreach ($list as $arr=>$row) {
            //$v[$arr]['PurOrder_De']      = $row['PurOrder_De'];
            $v[$arr]['Create_Date'] = $row['Create_Date'];
            $v[$arr]['BOM_Accountt'] = (float)abs($row['BOM_Accountt']);
            //$v[$arr]['extraQty'] = (float)abs($row['extra_qty']);
            //$v[$arr]['totalQty'] = intval($row['total_qty']) == 0 ? (float)abs($row['qty']) : (float)abs($row['total_qty']);
            $v[$arr]['BOMName'] = $row['BOMName'];
            $v[$arr]['unitName'] = $row['unitName'];
            $v[$arr]['BOMModel'] = $row['BOMModel'];
            //$v[$arr]['reason'] = $row['reason'];
        }
            $info['status'] = 200;
            $info['msg']    = 'success';
            $info['data']['billNo'] = $list[0]['PurOrder_ID'];
            $info['data']['entries'] = is_array($v) ? $v : '';
            $info['type'] = $this->input->get_post('type', TRUE);
            if(intval($this->input->get_post('show',TRUE)) == 1){
                die(json_encode($info));
            }
            $this->load->view('invpu/planEdit');
        }
    }

    //采购单审核首页
    public function reviewIndex(){
        $this->purview_model->checkpurview(96);
            $this->load->view('invpu/reviewList');
    }

    //采购单审核信息列表
    public function purchaseReviewList(){
        $v = '';
        $data['status'] = 200;
        $data['msg']    = 'success';
        $page = max(intval($this->input->get_post('page',TRUE)),1);
        $rows = max(intval($this->input->get_post('rows',TRUE)),100);
        $key  = str_enhtml($this->input->get_post('matchCon',TRUE));
        $stt  = str_enhtml($this->input->get_post('beginDate',TRUE));
        $ett  = str_enhtml($this->input->get_post('endDate',TRUE));
        $where = 'and a.Status = 1 ';
        if (strlen($key)>0) {
            $where .= ' and (a.PK_BOM_Pur_ID like "%'.$key.'%" or a.Name like "%'.$key.'%" or a.PurOrder_Payment like "%'.$key.'%")';
        }
        if (strlen($stt)>0) {
            $where .= ' and a.Create_Date>="'.$stt.'"';
        }
        if (strlen($ett)>0) {
            $where .= ' and a.Create_Date<="'.$ett.' 23:59:59"';
        }

        $offset = $rows * ($page-1);
        $data['data']['page']      = $page;


        $list = $this->data_model->purOrderList($where, ' order by a.Create_Date desc limit '.$offset.','.$rows.'');
        foreach ($list as $arr=>$row) {
            $v[$arr]['PK_BOM_Pur_ID']           = $row['PK_BOM_Pur_ID'];
            $v[$arr]['Supplier_Name']  = $row['Supplier_Name'];
            $v[$arr]['orderName']  = $row['orderName'];
            $v[$arr]['PurOrder_Total']       = (float)abs($row['PurOrder_Total']);
            $v[$arr]['PurOrder_Payment'] = $row['PurOrder_Payment'];
            $v[$arr]['Create_Date']     = $row['Create_Date'];
            $v[$arr]['Username']     = $row['Username'];
            $v[$arr]['review'] = $row['Status'];
        }
        $data['data']['records']   = count($list);  //总条数
        $data['data']['total']     = ceil($data['data']['records']/$rows);    //总分页数
        $data['data']['rows']      = is_array($v) ? $v : '';
        die(json_encode($data));
    }

    //购货单详情
    public function infoDetail(){
        $id   = $this->input->get_post('id',TRUE);
        $list = $this->data_model->invpu_info(' and (a.PurOrder_ID= "'.$id.'")','order by a.Create_Date desc');
        foreach ($list as $arr=>$row) {
            $v[$arr]['BOMName']      = $row['BOMName'];
            $v[$arr]['BOMModel']      = $row['BOMModel'];
            $v[$arr]['BOM_Accountt']          = (float)abs($row['BOM_Accountt']);
            $v[$arr]['Pur_Price']       = (float)abs($row['Pur_Price']);
            $v[$arr]['Pur_SubTotal']        = (float)$row['Pur_SubTotal'];
            $v[$arr]['unitName']     = $row['unitName'];
        }
        die(json_encode($v));
    }

    //审核购货单
    public function review(){
        $this->purview_model->checkpurview(96);
            //$id   = $this->input->get_post('id',TRUE);
            $review   = intval($this->input->get_post('r',TRUE));
            $billno   = $this->input->get_post('billno',TRUE);
            if(in_array($review,array(2,3))) {
                $info = array('Status' => $review, 'Review_ID' => $this->uid);
                $this->db->trans_begin();
                $this->mysql_model->db_upd(PURORDER, $info, '(PK_BOM_Pur_ID="' . $billno . '")');
                if ($this->db->trans_status() === FALSE) {
                    $this->db->trans_rollback();
                    die();
                } else {
                    $this->db->trans_commit();
                    $this->cache_model->delsome(PURORDER);
                    $this->data_model->logs($this->name.'审核了采购单：'.$billno);
                    die('{"status":200,"msg":"审核成功"}');
                }
            }
    }


}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */