<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Invsa extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->purview_model->checkpurview(6);
        $this->load->model('data_model');
        $this->uid = $this->session->userdata('uid');
        $this->name = $this->session->userdata('name');
        $this->SalePriceRefer1 = $this->session->userdata('SalePriceRefer1');
        $this->SalePriceRefer2 = $this->session->userdata('SalePriceRefer2');
        $this->SalePriceRefer3 = $this->session->userdata('SalePriceRefer3');
    }

    public function index()
    {
        $this->load->view('invsa/index');
    }

    /**
     * showdoc
     * @catalog 开发文档/销售
     * @title 销售新增
     * @description 销售新增的接口
     * @method get
     * @url https://www.2midcm.com/invsa/add
     * @param contactno 必选 string 客户编号
     * @param contactid 必选 string 客户ID
     * @param contactname 必选 string 客户名称
     * @param billno 必选 string 单据编号
     * @param type 必选 string 1销售 2退货
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
    //弃用
    public function add()
    {
        $this->purview_model->checkpurview(7);
        $data = $this->input->post('postData', TRUE);
        if (strlen($data) > 0) {
            $data = (array)json_decode($data);
            (!isset($data['buId']) && $data['buId'] < 1) && die('{"status":-1,"msg":"请选择客户"}');
            $contact = $this->mysql_model->db_one(CONTACT, '(id=' . intval($data['buId']) . ')');
            count($contact) < 1 && die('{"status":-1,"msg":"请选择客户"}');
            $info['billno'] = str_no('XS');
            $info['type'] = intval($data['transType']);
            $info['contactid'] = $data['buId'];
            $info['contactname'] = $contact['number'] . ' ' . $contact['name'];
            $info['billdate'] = date('Y-m-d H:i:s', time());
            $info['disamount'] = $data['disAmount'];
            $info['disrate'] = $data['disRate'];
            $info['description'] = $data['description'];
            $info['totalamount'] = $data['totalAmount'];
            $info['totalqty'] = $data['totalQty'];
            $info['amount'] = $info['type'] == 1 ? $data['amount'] : -$data['amount'];  //折扣后金额
            $info['rpamount'] = $info['type'] == 1 ? $data['rpAmount'] : -$data['rpAmount'];  //已付款
            $info['arrears'] = $info['type'] == 1 ? $data['arrears'] : -$data['arrears'];  //欠款
            $info['totalarrears'] = (float)$data['totalArrears'];
            $info['uid'] = $this->uid;
            $info['username'] = $this->name;
            $info['update_time'] = $info['billdate'];
            $this->db->trans_begin();
            $invsaid = $this->mysql_model->db_inst(INVSA, $info);
            $v = array();
            if (is_array($data['entries'])) {
                foreach ($data['entries'] as $arr => $row) {
                    $v[$arr]['invsaid'] = $invsaid;
                    $v[$arr]['billno'] = $info['billno'];
                    $v[$arr]['contactid'] = $info['contactid'];
                    $v[$arr]['contactname'] = $info['contactname'];
                    $v[$arr]['type'] = $info['type'];
                    $v[$arr]['goodsid'] = $row->invId;
                    $v[$arr]['qty'] = $info['type'] == 1 ? $row->qty : -($row->qty);
                    $v[$arr]['amount'] = $info['type'] == 1 ? $row->amount : -($row->amount);
                    $v[$arr]['price'] = (float)$row->price;
                    $v[$arr]['discountrate'] = (float)$row->discountRate;
                    $v[$arr]['description'] = $row->description;
                    $v[$arr]['deduction'] = $row->deduction;
                    $v[$arr]['description'] = $row->description;
                    $v[$arr]['goodsno'] = $row->invNumber;
                    $v[$arr]['billdate'] = $info['billdate'];
                }
            }
            $this->mysql_model->db_inst(INVSA_INFO, $v);
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                die();
            } else {
                $this->db->trans_commit();
                $this->cache_model->delsome(GOODS);
                $this->cache_model->delsome(INVSA);
                $this->cache_model->delsome(INVSA_INFO);
                $this->data_model->logs('新增销货单 单据编号：' . $info['billno']);
                die('{"status":200,"msg":"success","data":{"id":' . intval($invsaid) . '}}');
            }
        } else {
            $data['billno'] = str_no('XS');
            $this->load->view('invsa/add', $data);
        }
    }


    /**
     * showdoc
     * @catalog 开发文档/销售
     * @title 销售修改
     * @description 销售修改的接口
     * @method get
     * @url https://www.2midcm.com/invsa/edit
     * @param contactno 必选 string 客户编号
     * @param contactid 必选 string 客户ID
     * @param contactname 必选 string 客户名称
     * @param billno 必选 string 单据编号
     * @param type 必选 string 1销售 2退货
     * @param billdate 必选 string 单据日期
     * @param description 必选 string 备注
     * @param goodsno 必选 string 商品编号
     * @param goodsid 必选 string 商品ID
     * @param price 必选 string 单价
     * @return {"status":200,"msg":"success","data":{"id":'.$id.'}}
     * @return_param status int 1：'200'修改成功;2："-1"修改失败
     * @remark 这里是备注信息
     * @number 2
     */
    //修改
    public function edit()
    {
        $this->purview_model->checkpurview(87);
        $id = $this->input->get('id', TRUE);
        $data = $this->input->post('postData', TRUE);
        if (strlen($data) > 0) {
            $data = (array)json_decode($data);
            !isset($data['id']) && die('{"status":-1,"msg":"参数错误"}');
            (!isset($data['buId']) && $data['buId'] < 1) && die('{"status":-1,"msg":"请选择销售单位"}');

            /*			 $contact = $this->mysql_model->db_one(CONTACT,'(id='.intval($data['buId']).')');

                         count($contact)<1 && die('{"status":-1,"msg":"请选择报价单位"}');*/



            $sheetData = $this->mysql_model->db_select(SALEORDER, '(PK_BOM_Sale_ID="' . $data['id'] . '")');

            if (count($sheetData) != 1) die('{"status":-1,"msg":"销售单有误"}');

            if (in_array($sheetData[0]['Status'], array(6, 9))) die('{"status":-1,"msg":"该销售单已审核通过，不能修改！"}');

            //$this->mysql_model->db_count(SHEET,'(id<>'.$id.') and (billno="'.$info['billno'].'")')>0 && die('{"status":-1,"msg":"报价单已存在"}');

            $info['PK_BOM_Sale_ID'] = $data['billNo'];
            $info['Customer_ID'] = intval($data['buId']);
            $info['Name'] = $data['orderName'];
            $info['SaleOrder_Total'] = (float)$data['totalAmount']; //订单总金额
            $info['SaleOrder_Payment'] = $data['paymentType'];
            $info['SaleOrder_TotalCost'] = $data['totalCost'];
            $info['Modify_ID'] = $this->uid;
            $info['Modify_Date'] = date('Y-m-d H:i:s', time());
            $info['Status'] = 4;

            $v = array();
            $this->db->trans_begin();

            $oldData = $this->mysql_model->db_select(SALEORDER_DETAIL, '(SaleOrder_ID="' . $data['id'] . '")');
            $oldArr = array();
            foreach ($oldData as $val) {
                $oldArr[$val['BOM_ID']] = $val;
            }
            $this->mysql_model->db_upd(SALEORDER, $info, '(PK_BOM_Sale_ID= "' . $info['PK_BOM_Sale_ID'] . '")');
            $this->mysql_model->db_del(SALEORDER_DETAIL, '(SaleOrder_ID= "' . $info['PK_BOM_Sale_ID'] . '")');

            $change = array();
            if (is_array($data['entries'])) {
                foreach ($data['entries'] as $arr => $row) {
                    $v[$arr]['SaleOrder_ID'] = $info['PK_BOM_Sale_ID'];
                    $v[$arr]['BOM_ID'] = $row->invId;
                    $v[$arr]['BOM_Accountt'] = $row->qty;
                    $v[$arr]['Sale_Price'] = $row->price;
                    $v[$arr]['SaleOrder_De'] = str_pad($arr + 1, 5, "0", STR_PAD_LEFT);
                    $v[$arr]['ReferPrice0']       = isset($oldArr[$row->invId]) ? $oldArr[$row->invId]['ReferPrice0'] : 0;
                    $v[$arr]['ReferPrice1']       = isset($oldArr[$row->invId]) ? $oldArr[$row->invId]['ReferPrice0'] * $this->SalePriceRefer1 : 0;
                    $v[$arr]['ReferPrice4'] = $row->specialPrice;
                    $v[$arr]['Sale_SubTotal'] = $row->amount;
                    $v[$arr]['Creator_ID'] = $sheetData[0]['Creator_ID'];
                    $v[$arr]['Create_Date'] = $sheetData[0]['Create_Date'];
                    $v[$arr]['Modify_ID'] = $info['Modify_ID'];
                    $v[$arr]['Modify_Date'] = $info['Modify_Date'];
                    if ($row->unitcost == 0) {
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

            $this->mysql_model->db_inst(SALEORDER_DETAIL, $v);

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                die('');
            } else {
                $this->db->trans_commit();

                if (isset($updateCost) && $updateCost == 1) {
                    $procedureRes = $this->data_model->updateCost($info['PK_BOM_Sale_ID']);
                    if ($procedureRes['code'] != 1) {
                        $this->data_model->logs('报价单' . $info['PK_BOM_Sale_ID'] . '更新成本失败：' . json_encode($procedureRes));
                    }
                }

                $this->cache_model->delsome(SALEORDER);
                $this->cache_model->delsome(SALEORDER_DETAIL);
                $this->data_model->logs('修改销售单 单据编号：'.$info['PK_BOM_Sale_ID'] .',修改了：' . json_encode($change,JSON_UNESCAPED_UNICODE));
                die('{"status":200,"msg":"success","data":{"id":"' . $id . '"}}');
            }

        } else {
            $data = $this->mysql_model->db_one(SALEORDER, '(PK_BOM_Sale_ID="' . $id . '")');
            if (count($data) > 0) {
                $this->load->view('invsa/edit', $data);
            } else {
                $data['billno'] = str_no('P');
                $this->load->view('invsa/add', $data);
            }
        }
    }



    //销售单列表
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
            $where .= ' and a.Status > 3 ';
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

	//已经出库的销售列表
    public function outlists() {
        $v = '';
        $data['status'] = 200;
        $data['msg']    = 'success';
        $page = max(intval($this->input->get_post('page',TRUE)),1);
        $rows = max(intval($this->input->get_post('rows',TRUE)),100);
        $key  = str_enhtml($this->input->get_post('matchCon',TRUE));
        $review  = str_enhtml($this->input->get_post('review',TRUE));
        $where = ' and review = 3';
        if (strlen($key)>0) {
            $where .= ' and (billno like "%'.$key.'%" 
		    or contactname like "%'.$key.'%" 
		    or username like "%' . $key . '%")';
        }

        $offset = $rows*($page-1);
        $data['data']['page']      = $page;
        $data['data']['records']   = $this->cache_model->load_total(INVSA,'(1=1) '.$where);   //总条数
        $data['data']['total']     = ceil($data['data']['records']/$rows);                    //总分页数
        $list = $this->cache_model->load_data(INVSA,'(1=1) '.$where.' order by id desc limit '.$offset.','.$rows.'');
        foreach ($list as $arr=>$row) {
            $v[$arr]['amount']       = (float)abs($row['amount']);
            $v[$arr]['id']           = intval($row['id']);
            $v[$arr]['contactName']  = $row['contactname'];
            $v[$arr]['description']  = $row['description'];
            $v[$arr]['billNo']       = $row['billno'];
            $v[$arr]['billDate']     = $row['billdate'];
            $v[$arr]['totalAmount']  = (float)abs($row['totalamount']);
            $v[$arr]['userName']     = $row['username'];
        }
        $data['data']['rows']        = is_array($v) ? $v : '';
        die(json_encode($data));
    }


    //修改单据数据回显
    public function info(){
        $id   = $this->input->get_post('id',TRUE);
        $list = $this->data_model->saleOrderList('and PK_BOM_Sale_ID = "' . $id . '"');
        if (count($list)>0) {
            $data = $list[0];
            $v = '';
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
            $list = $this->data_model->invsa_info(' and (a.SaleOrder_ID="'.$id.'")','order by Order_ID desc');
            foreach ($list as $arr=>$row) {
                $v[$arr]['invSpec']           = $row['BOMModel'];
                $v[$arr]['goods']             = $row['BOMName'].' '.$row['BOMModel'];
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
            $info['data']['entries']     = is_array($v) ? $v : '';
            $info['data']['accId']       = 0;
            $info['data']['accounts']    = array();
            die(json_encode($info));
        } else {
            alert('参数错误');
        }
    }

    /**
     * showdoc
     * @catalog 开发文档/销售
     * @title 销售删除
     * @description 销售删除的接口
     * @method get
     * @url https://www.2midcm.com/invas/export
     * @param id 必选 int 购货单ID
     * @return "{"status":200,"msg":"success","id":"1"}
     * @return_param status int 1：'200'导出成功;2："-1"导出失败
     * @remark 这里是备注信息
     * @number 2
     */
	public function export() {
	    $this->purview_model->checkpurview(10);
        sys_xls('销售记录.xls');
        $id  = str_enhtml($this->input->get_post('id',TRUE));
        if (strlen($id)>0) {
            $data['list1'] = $this->data_model->saleOrderList(' and (a.PK_BOM_Sale_ID in('.str_replace("$",'"',$id).'))');
            $data['list2'] = $this->data_model->sheet_info(' and (a.SaleOrder_ID in('.str_replace("$",'"',$id).'))');
            $this->data_model->logs('导出销售记录');
            $this->load->view('invsa/export',$data);
        }
	}


    /**
     * showdoc
     * @catalog 开发文档/销售
     * @title 销售
     * @description 销售删除的接口
     * @method get
     * @url https://www.2midcm.com/invsa/del
     * @param id 必选 int 购货单ID
     * @return "{"status":200,"msg":"success","id":"1"}
     * @return_param status int 1：'200'导出成功;2："-1"导出失败
     * @remark 这里是备注信息
     * @number 1
     */
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
                $this->data_model->logs('删除销售单 订单编号：'.$data['PK_BOM_Sale_ID']);
                die('{"status":200,"msg":"success"}');
            }
        }
        die('{"status":-1,"msg":"删除失败"}');
    }

    //物流显示销售单信息
    public function infoDetail(){
        $id   = $this->input->get_post('id',TRUE);
        $list = $this->data_model->invsa_info(' and (a.SaleOrder_ID="'.$id.'")','order by Order_ID desc');
        foreach ($list as $arr=>$row) {
            $v[$arr]['invSpec']           = $row['BOMModel'];
            $v[$arr]['goods']             = $row['BOMName'].'（'.$row['BOMModel'] . '）';
            $v[$arr]['invName']      = $row['BOMName'];
            $v[$arr]['qty']          = (float)abs($row['BOM_Accountt']);
            $v[$arr]['price']       = (float)abs($row['Sale_Price']);
            $v[$arr]['mainUnit']     = $row['unitName'];
            $v[$arr]['unitId']       = intval($row['FK_UnitClass_ID']);
            $v[$arr]['amount'] = (float)abs($row['Sale_SubTotal']);//小计
        }
            die(json_encode($v));
    }

    //销货单审核首页
    public function reviewIndex(){
        $this->purview_model->checkpurview(97);
        $this->load->view('invsa/reviewList');
    }

    //销货单审核信息列表
    public function salesReviewList(){
        $v = '';
        $data['status'] = 200;
        $data['msg']    = 'success';
        $page = max(intval($this->input->get_post('page',TRUE)),1);
        $rows = max(intval($this->input->get_post('rows',TRUE)),100);
        $key  = str_enhtml($this->input->get_post('matchCon',TRUE));
        $stt  = str_enhtml($this->input->get_post('beginDate',TRUE));
        $ett  = str_enhtml($this->input->get_post('endDate',TRUE));
        $where = 'and a.Status = 4 ';
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

    //销货单详情
    public function infoDetails(){
        $id   = $this->input->get_post('id',TRUE);
        $list = $this->data_model->invsa_info(' and (a.SaleOrder_ID="'.$id.'")','order by Order_ID desc');
        foreach ($list as $arr=>$row) {
            $v[$arr]['invSpec']           = $row['BOMModel'];
            $v[$arr]['goods']             = $row['BOMName'].' '.$row['BOMModel'];
            $v[$arr]['invName']      = $row['BOMName'];
            $v[$arr]['qty']          = (float)abs($row['BOM_Accountt']);
            $v[$arr]['price']       = (float)abs($row['Sale_Price']);
            $v[$arr]['mainUnit']     = $row['unitName'];
            $v[$arr]['unitId']       = intval($row['FK_UnitClass_ID']);
            $v[$arr]['amount'] = (float)abs($row['Sale_SubTotal']);//小计
       }
        die(json_encode($v));
    }

    //审核销货单
    public function review(){
        $this->purview_model->checkpurview(99);
        $review   = intval($this->input->get_post('r',TRUE));
        $billno   = $this->input->get_post('billno',TRUE);
        if(in_array($review,array(6,7))) {
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
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */