<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Sheet extends CI_Controller {

    public function __construct(){
        parent::__construct();
		$this->purview_model->checkpurview(85);
		$this->load->model('data_model');
		$this->uid  = $this->session->userdata('uid');
		$this->name = $this->session->userdata('name');
        $this->SalePriceRefer1 = $this->session->userdata('SalePriceRefer1');
        $this->SalePriceRefer2 = $this->session->userdata('SalePriceRefer2');
        $this->SalePriceRefer3 = $this->session->userdata('SalePriceRefer3');
    }
	
	public function index(){
		$this->load->view('sheet/index');
	}
    /**
     * showdoc
     * @catalog 开发文档/报价销售
     * @title 报价新增
     * @description 报价新增的接口
     * @method get
     * @url https://www.2midcm.com/sheet/add
     * @param contactno 必选 string 客户编号
     * @param contactid 必选 string 客户ID
     * @param contactname 必选 string 客户名称
     * @param billno 必选 string 单据编号
     * @param type 必选 string 报价
     * @param billdate 必选 string 单据日期
     * @param description 必选 string 备注
     * @param goodsno 必选 string 商品编号
     * @param goodsid 必选 string 商品ID
     * @param price 必选 string 单价
     * @return "{"status":200,"msg":"success","data":'.json_encode($data).'}
     * @return_param status int 1：'200'新增成功;2："-1"新增失败
     * @remark 这里是备注信息
     * @number 2
     */
	public function add(){
	    $this->purview_model->checkpurview(86);
	    $data = $this->input->post('postData',TRUE);
		if (strlen($data)>0) {
		     $data = (array)json_decode($data);
/*			 (!isset($data['buId']) && $data['buId']<1) && die('{"status":-1,"msg":"请选择销货单位"}');
			 $contact = $this->mysql_model->db_one(CONTACT,'(id='.intval($data['buId']).')');
			 count($contact)<1 && die('{"status":-1,"msg":"请选择购货单位"}');*/
            $info['PK_BOM_Sale_ID']      = $data['billNo'];//str_no('P');
            $info['Customer_ID']   = intval($data['buId']);
            $info['Name']   = $data['orderName'];
            $info['SaleOrder_TotalCost']   = $data['totalCost'] ? (float)$data['totalCost'] : 0;
            $info['SaleOrder_Total']      = $data['totalAmount'] ? (float)$data['totalAmount'] : 0; //订单总金额
            $info['SaleOrder_Payment'] = $data['paymentType'];
            $info['Creator_ID']         = $this->uid;
            $info['Status']         = 0;

			 $this->db->trans_begin();
			 $this->mysql_model->db_inst(SALEORDER,$info);
			 $v = array();
			 if (is_array($data['entries'])) {

                 $repeat = array();
                 $tmpArr = array();

			     foreach ($data['entries'] as $arr=>$row) {
                     $v[$arr]['SaleOrder_ID']       = $info['PK_BOM_Sale_ID'];
                     $v[$arr]['SaleOrder_De']     = str_pad($arr+1,5,"0",STR_PAD_LEFT);
                     $v[$arr]['BOM_ID']       = intval($row->invId);
                     $v[$arr]['BOM_Accountt']           = (float)$row->qty;
                     $v[$arr]['Sale_SubTotal']        = $row->amount ? (float)$row->amount : 0;
                     $v[$arr]['Sale_Price']         = $row->price? (float)$row->price : 0;
                     $v[$arr]['Creator_ID']  = $this->uid;
                     if(isset($tmpArr[intval($row->invId)])){//检查是否有重复的bom
                         $repeat[] = $row->invName;
                     }else{
                         $tmpArr[intval($row->invId)] = $row->invName;
                     }
				}
                 if (count($repeat) > 0){
                     $this->db->trans_rollback();//回滚数据
                     die('{"status":-1,"msg":"物品：'. implode("，",$repeat).' 重复提交，请筛选处理后再提交"}');
                 }
			 }
			 $this->mysql_model->db_inst(SALEORDER_DETAIL,$v);

			 if ($this->db->trans_status() === FALSE) {
			    $this->db->trans_rollback();
				die();
			 } else {
			    $this->db->trans_commit();

			    //放在外面的话，会因为执行成本存储过程失败而导致整个报价单添加不了
                 $procedureRes = $this->data_model->updateCost($info['PK_BOM_Sale_ID']);
                 if($procedureRes['code'] != 1){
                     $this->data_model->logs('报价单'.$info['PK_BOM_Sale_ID'] . '更新成本失败：'. json_encode($procedureRes));
                 }

				$this->cache_model->delsome(SALEORDER);
				$this->cache_model->delsome(SALEORDER_DETAIL);
				$this->data_model->logs('新增报价单 单据编号：'.$v[$arr]['SaleOrder_ID']);
				die('{"status":200,"msg":"success","data":{"id":"'.$info['PK_BOM_Sale_ID'].'"}}');
			 }
		} else {
		    $data['billno'] = str_no('P');
		    $this->load->view('sheet/add',$data);
		}
	}
	
	
	//修改
	public function edit(){
	    $this->purview_model->checkpurview(87);
	    $id   = $this->input->get('id',TRUE);
	    $data = $this->input->post('postData',TRUE);
		if (strlen($data)>0) {
		     $data = (array)json_decode($data);
			 !isset($data['id']) && die('{"status":-1,"msg":"参数错误"}');
			 (!isset($data['buId']) && $data['buId']<1) && die('{"status":-1,"msg":"请选择报价单位"}');

/*			 $contact = $this->mysql_model->db_one(CONTACT,'(id='.intval($data['buId']).')');

			 count($contact)<1 && die('{"status":-1,"msg":"请选择报价单位"}');*/

            $sheetData = $this->mysql_model->db_select(SALEORDER,'(PK_BOM_Sale_ID="'.$data['id'].'")');

            if(count($sheetData) != 1) die('{"status":-1,"msg":"报价单有误"}');

            if(in_array($sheetData[0]['Status'], array(2))) die('{"status":-1,"msg":"该报价已审核通过，不能修改！"}');

            //$this->mysql_model->db_count(SHEET,'(id<>'.$id.') and (billno="'.$info['billno'].'")')>0 && die('{"status":-1,"msg":"报价单已存在"}');

            $info['PK_BOM_Sale_ID']      = $data['billNo'];
            $info['Customer_ID']   = intval($data['buId']);
            $info['Name']   = $data['orderName'];
            $info['SaleOrder_Total']      = (float)$data['totalAmount']; //订单总金额
            $info['SaleOrder_Payment'] = $data['paymentType'];
            $info['SaleOrder_TotalCost']         = $data['totalCost'];
            $info['Modify_ID']         = $this->uid;
            $info['Modify_Date']    = date('Y-m-d H:i:s',time());
            $info['Status']         = 1;

			 $v = array();
			 $this->db->trans_begin();

			 $oldData = $this->mysql_model->db_select(SALEORDER_DETAIL,'(SaleOrder_ID="'.$data['id'].'")');
			 $oldArr = array();
			 foreach ($oldData as $val){
			     $oldArr[$val['BOM_ID']] = $val;
             }
            $this->mysql_model->db_upd(SALEORDER,$info,'(PK_BOM_Sale_ID= "'.$info['PK_BOM_Sale_ID'].'")');
            $this->mysql_model->db_del(SALEORDER_DETAIL,'(SaleOrder_ID= "'.$info['PK_BOM_Sale_ID'].'")');
            $change = array();
			 if (is_array($data['entries'])) {
                 //检查是否有重复的bom，后续操作复杂，先检查
                 $repeat = array();
                 $tmpArr = array();

                 foreach ($data['entries'] as $arr=>$row){
                     if(isset($tmpArr[intval($row->invId)])){
                         $repeat[] = $row->invName;
                     }else{
                         $tmpArr[intval($row->invId)] = $row->invName;
                     }
                 }

                 if (count($repeat) > 0){
                     $this->db->trans_rollback();//回滚数据
                     die('{"status":-1,"msg":"物品：'. implode("，",$repeat).' 重复提交，请筛选处理后再提交"}');
                 }
                 //检查结束

			     foreach ($data['entries'] as $arr=>$row) {
                     $v[$arr]['SaleOrder_ID']        = $info['PK_BOM_Sale_ID'];
                     $v[$arr]['BOM_ID']   = $row->invId;
                     $v[$arr]['BOM_Accountt']          = $row->qty;
                     $v[$arr]['Sale_Price']       = $row->price;
                     $v[$arr]['SaleOrder_De']       = str_pad($arr+1,5,"0",STR_PAD_LEFT);
                     $v[$arr]['ReferPrice0']       = isset($oldArr[$row->invId]) ? $oldArr[$row->invId]['ReferPrice0'] : 0;
                     $v[$arr]['ReferPrice1']       = isset($oldArr[$row->invId]) ? $oldArr[$row->invId]['ReferPrice0'] * $this->SalePriceRefer1 : 0;
                     $v[$arr]['ReferPrice4'] = (float)$row->specialPrice;
                     $v[$arr]['Sale_SubTotal']           = $row->amount;
                     $v[$arr]['Creator_ID']        = $sheetData[0]['Creator_ID'];
                     $v[$arr]['Create_Date']         = $sheetData[0]['Create_Date'];
                     $v[$arr]['Modify_ID']  = $info['Modify_ID'] ;
                     $v[$arr]['Modify_Date']   = $info['Modify_Date'];
                     if($row->unitcost == 0){
                         $updateCost = 1;
                     }

                     //变更记录
                     if(isset($oldArr[$row->invId])) {
                         if ((float)$oldArr[$row->invId]['Sale_Price'] != (float)$row->price ) {//变过价格的

                             if((float)$row->specialPrice > 0){
                                 $change['data'][$row->invId] = '：（特别价）单价从' . $oldArr[$row->invId]['Sale_Price'] . '改为' . $row->price;
                             }else{
                                 $change['data'][$row->invId] = '：单价从' . $oldArr[$row->invId]['Sale_Price'] . '改为' . $row->price;
                             }
                             unset($oldArr[$row->invId]);
                         }else{//没变过价格的
                             unset($oldArr[$row->invId]);
                         }
                     } else{
                             $change['data'][ $row->invId] = '（新增）' . '单价为：' . $row->price ;
                     }
			     }
				if(count($oldArr) > 0){//unset完之后剩下的就是被删掉的报价
                    foreach ($oldArr as $key => $del){
                        $change['data'][$key] = '（删除）' . '单价为：' . $del['Sale_Price'];
                    }
                }

                 //记录变更完成
			 }

			 $this->mysql_model->db_inst(SALEORDER_DETAIL,$v);

			 if ($this->db->trans_status() === FALSE) {
			    $this->db->trans_rollback();
				die('');
			 } else {
			    $this->db->trans_commit();

			    if(isset($updateCost) && $updateCost ==1){
                    $procedureRes = $this->data_model->updateCost($info['PK_BOM_Sale_ID']);
                    if($procedureRes['code'] != 1){
                        $this->data_model->logs('报价单'.$info['PK_BOM_Sale_ID'] . '更新成本失败：'. json_encode($procedureRes));
                    }
                }

				$this->cache_model->delsome(SALEORDER);
				$this->cache_model->delsome(SALEORDER_DETAIL);
				$this->data_model->logs('修改报价单 单据编号：'.$info['PK_BOM_Sale_ID'] .',修改了：' . json_encode($change,JSON_UNESCAPED_UNICODE));
				die('{"status":200,"msg":"success","data":{"id":"'.$id.'"}}');
			 }

		} else {
		    $data = $this->mysql_model->db_one(SALEORDER,'(PK_BOM_Sale_ID="'.$id.'")');
			if (count($data)>0) {
				$this->load->view('sheet/edit',$data);
			} else {
			    $data['billno'] = str_no('P');
			    $this->load->view('sheet/add',$data);
			}
		}
	}
	
	//修改单据数据回显
	public function info(){
	    $id   = $this->input->get_post('id',TRUE);
        $list = $this->data_model->saleOrderList('and PK_BOM_Sale_ID = "' . $id . '"');
		if (count($list)>0) {
		    $data = $list[0];
			$v = array();
            $info['status'] = 200;
            $info['msg']    = 'success';
            $info['data']['id']                 = $data['PK_BOM_Sale_ID'];
            $info['data']['buId']               = intval($data['Customer_ID']);
            $info['data']['contactName']        = $data['Customer_Name'];
            $info['data']['date']               = $data['Create_Date'];
            $info['data']['billNo']             = $data['PK_BOM_Sale_ID'];
            //$info['data']['totalQty']           = (float)$data['totalqty'];
            $info['data']['totalAmount']        = (float)abs($data['SaleOrder_Total']);
            $info['data']['userName']           = $data['Username'];
            $info['data']['status']             = 'edit';
            $info['data']['PK_BOM_Sale_ID']      = $data['PK_BOM_Sale_ID'];//str_no('P');
            $info['data']['orderName']   = $data['orderName'];
            $info['data']['paymentType'] = $data['SaleOrder_Payment'];
            $info['data']['totalUnitCost'] = $data['SaleOrder_TotalCost'];
            $info['data']['Creator_ID']         = $this->uid;
			$list = $this->data_model->sheet_info(' and (a.SaleOrder_ID="'.$id.'")','order by Order_ID desc');
			foreach ($list as $arr=>$row) {
/*                if($row['ReferPrice0'] <= 0){//成本价
                    $this->data_model->updateCost($id);
                }*/
                $v[$arr]['invSpec']           = $row['BOMModel'];
                $v[$arr]['goods']             = $row['BOMName'];
                $v[$arr]['bomModel'] = $row['BOMModel'];
                $v[$arr]['invName']      = $row['BOMName'];
                $v[$arr]['qty']          = (float)abs($row['BOM_Accountt']);
                $v[$arr]['price']       = (float)abs($row['Sale_Price']);
                $v[$arr]['mainUnit']     = $row['unitName'];
                $v[$arr]['PK_BOM_ID']        = intval($row['PK_BOM_ID']);
                $v[$arr]['unitId']       = intval($row['FK_UnitClass_ID']);
                $v[$arr]['amount'] = (float)abs($row['Sale_SubTotal']);//小计
				$v[$arr]['salePrice'] = (float)abs($row['ReferPrice0'] * $this->SalePriceRefer1);  //成本价
				$v[$arr]['unitCost'] = $this->purview_model->checkpurview(98,true) == true ? $row['ReferPrice0'] : 0;
				$v[$arr]['specialPrice'] = $row['ReferPrice4'];

			}
			$info['data']['entries']     = $v;
			$info['data']['accId']       = 0;
			$info['data']['accounts']    = array();
			die(json_encode($info));
		} else { 
			alert('参数错误');
		}
	}
	
	
	//报价单列表
	public function lists() {
	    $v = '';
	    $data['status'] = 200;
		$data['msg']    = 'success'; 
		$page = max(intval($this->input->get_post('page',TRUE)),1);
		$rows = max(intval($this->input->get_post('rows',TRUE)),100);
		$key  = str_enhtml($this->input->get_post('matchCon',TRUE));
		$stt  = str_enhtml($this->input->get_post('beginDate',TRUE));
		$ett  = str_enhtml($this->input->get_post('endDate',TRUE));
        $review  = str_enhtml($this->input->get_post('review',TRUE));
		$where = '';
		if (strlen($key)>0) {
            $where .= ' and (a.PK_BOM_Sale_ID like "%'.$key.'%" or a.Name like "%'.$key.'%" or a.SaleOrder_Payment like "%'.$key.'%")';
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
            $where .= ' and a.Status < 4 ';
        }
		$offset = $rows * ($page-1);
		$data['data']['page']      = $page;
        $list = $this->data_model->saleOrderList($where, ' order by a.Create_Date desc limit '.$offset.','.$rows.'');
        foreach ($list as $arr=>$row) {
            $v[$arr]['id']           = "$" . $row['PK_BOM_Sale_ID'] . "$";
            $v[$arr]['PK_BOM_Sale_ID'] =  $row['PK_BOM_Sale_ID'];
            $v[$arr]['Customer_Name']  = $row['Customer_Name'];
            $v[$arr]['orderName']  = $row['orderName'];
            $v[$arr]['SaleOrder_Total']       = (float)abs($row['SaleOrder_Total']);
            $v[$arr]['Create_Date']     = $row['Create_Date'];
            $v[$arr]['Username']     = $row['Username'];
            $v[$arr]['SaleOrder_Payment'] = $row['SaleOrder_Payment'];
            $v[$arr]['reviewDes'] = $row['Status'] == 2 ? '审核通过' : ($row['Status'] == 3 ? '审核不通过' : '未审核');
            $v[$arr]['review'] = $row['Status'];
        }
        $data['data']['records']   = count($list);  //总条数
        $data['data']['total']     = ceil($data['data']['records']/$rows);    //总分页数
        $data['data']['rows']      = is_array($v) ? $v : '';
        die(json_encode($data));
	}
	
	//导出报价记录
	public function export() {
	    $this->purview_model->checkpurview(107);
	    sys_xls('报价记录.xls');
		$id  = str_enhtml($this->input->get_post('id',TRUE));
        if (strlen($id)>0) {
            $data['list1'] = $this->data_model->saleOrderList(' and (a.PK_BOM_Sale_ID in('.str_replace("$",'"',$id).'))');
            $data['list2'] = $this->data_model->sheet_info(' and (a.SaleOrder_ID in('.str_replace("$",'"',$id).'))');
            $this->data_model->logs('导出报价记录');
            $this->load->view('sheet/export',$data);
        }
    }
	
	//报价单删除
    public function del() {
	    $this->purview_model->checkpurview(88);
        $id   = $this->input->get('id',TRUE);
        $data = $this->mysql_model->db_one(SALEORDER,'(PK_BOM_Sale_ID= "'.$id.'")');
        if (count($data)>0) {
            $this->db->trans_begin();
            $this->mysql_model->db_del(SALEORDER,'(PK_BOM_Sale_ID= "'.$id.'")');
            $this->mysql_model->db_del(SALEORDER_DETAIL,'(SaleOrder_ID= "'.$id.'")');
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                die('{"status":-1,"msg":"删除失败"}');
            } else {
                $this->db->trans_commit();
                $this->cache_model->delsome(SALEORDER);
                $this->cache_model->delsome(SALEORDER_DETAIL);
                $this->data_model->logs('删除报价单 订单编号：'.$data['PK_BOM_Sale_ID']);
                die('{"status":200,"msg":"success"}');
            }
        }
        die('{"status":-1,"msg":"删除失败"}');
	}

    //报价单审核首页
    public function reviewIndex(){
        $this->purview_model->checkpurview(96);
        $this->load->view('sheet/reviewList');
    }

    //报价单审核信息列表
    public function sheetReviewList(){
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
            $where .= ' and (a.PK_BOM_Sale_ID like "%'.$key.'%" or a.Name like "%'.$key.'%" or a.SaleOrder_Payment like "%'.$key.'%")';
        }
        if (strlen($stt)>0) {
            $where .= ' and a.Create_Date>="'.$stt.'"';
        }
        if (strlen($ett)>0) {
            $where .= ' and a.Create_Date<="'.$ett.' 23:59:59"';
        }


        $offset = $rows * ($page-1);
        $data['data']['page']      = $page;
/*        $data['data']['records']   = $this->cache_model->load_total(SALEORDER,'(1=1) '.$where);   //总条数
        $data['data']['total']     = ceil($data['data']['records']/$rows);    //总分页数*/

        $list = $this->data_model->saleOrderList($where, ' order by a.Create_Date desc limit '.$offset.','.$rows.'');
        foreach ($list as $arr=>$row) {
            $v[$arr]['PK_BOM_Sale_ID']           = $row['PK_BOM_Sale_ID'];
            $v[$arr]['Customer_Name']  = $row['Customer_Name'];
            $v[$arr]['orderName']  = $row['orderName'];
            $v[$arr]['SaleOrder_Total']       = (float)abs($row['SaleOrder_Total']);
            $v[$arr]['SaleOrder_Payment'] = $row['SaleOrder_Payment'];
            $v[$arr]['Create_Date']     = $row['Create_Date'];
            $v[$arr]['Username']     = $row['Username'];
            $v[$arr]['review'] = $row['Status'];
        }
        $data['data']['records']   = count($list);  //总条数
        $data['data']['total']     = ceil($data['data']['records']/$rows);    //总分页数
        $data['data']['total']     = ceil($data['data']['records']/$rows);    //总分页数
        $data['data']['rows']      = is_array($v) ? $v : '';
        die(json_encode($data));
    }

    //报价单详情
    public function infoDetail(){
        $id   = $this->input->get_post('id',TRUE);
        $list = $this->data_model->sheet_info(' and (a.SaleOrder_ID= "'.$id.'")','order by a.Create_Date desc');
        foreach ($list as $arr=>$row) {
            $v[$arr]['BOMName']      = $row['BOMName'];
            $v[$arr]['BOMModel']      = $row['BOMModel'];
            $v[$arr]['BOM_Accountt']          = (float)abs($row['BOM_Accountt']);
            $v[$arr]['Sale_Price']       = (float)abs($row['Sale_Price']);
            $v[$arr]['Sale_SubTotal']        = (float)$row['Sale_SubTotal'];
            $v[$arr]['unitName']     = $row['unitName'];
        }
        die(json_encode($v));
    }

    //审核报价单
    public function review(){
        $this->purview_model->checkpurview(99);
        $review   = intval($this->input->get_post('r',TRUE));
        $billno   = $this->input->get_post('billno',TRUE);
        if(in_array($review,array(2,3))) {
            $info = array('Status' => $review, 'Review_ID' => $this->uid);
            $this->db->trans_begin();
            $this->mysql_model->db_upd(SALEORDER, $info, '(PK_BOM_Sale_ID="' . $billno . '")');
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                die();
            } else {
                $this->db->trans_commit();
                $this->cache_model->delsome(SALEORDER);
                $this->data_model->logs($this->name.'审核了报价单：'.$billno);
                die('{"status":200,"msg":"审核成功"}');
            }
        }
    }

    //是否有查看单品成本价权限,false显示true隐藏
    public function unitcostHide(){
        $data['data'] = $this->purview_model->checkpurview(98,true) ? false : true;
        $data['status'] = 200;
        die(json_encode($data));
    }

    //报价单生成销售单
    public function sales(){
        $this->purview_model->checkpurview(100);
        $sheetId = $this->input->get_post('id', TRUE);
        if($sheetId ){
            $sheet = $this->mysql_model->db_select(SALEORDER, 'PK_BOM_Sale_ID ="' . $sheetId .'"');
            if (count($sheet) < 1 || intval($sheet[0]['Status']) != 2) {//不是审核通过
                die('{"status":-1,"msg":"报价单（' . $sheetId . '）状态有误"}');
            }
            $dataList = $this->mysql_model->db_select(SALEORDER_DETAIL, 'SaleOrder_ID = "' . $sheetId .'"');
            if(count($dataList) < 1){
                die('{"status":-1,"msg":"报价单（' . $sheetId . '）报价数据有误"}');
            }
            $this->db->trans_begin();
            $newId = str_no('P');;
            $this->mysql_model->db_upd(SALEORDER,array('Status' => 4, 'PK_BOM_Sale_ID' => $newId), 'PK_BOM_Sale_ID = "'. $sheetId .'"'); //4:生成销售单
            $this->mysql_model->db_upd(SALEORDER_DETAIL,array('SaleOrder_ID' => $newId), 'SaleOrder_ID = "'. $sheetId .'"'); //4:生成销售单
            $v = array();
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                die();
            } else {
                $this->db->trans_commit();
                $this->cache_model->delsome(SALEORDER);
                $this->data_model->logs('报价单（'. $sheetId . '）生成销售单（' . $newId . '）');
                die('{"status":200,"msg":"success","data":{"id":"'.$sheetId.'"}}');
            }
        }
    }

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */