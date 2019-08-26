<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

/**
 *物流信息控制器
 */
class logistics extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->purview_model->checkpurview(89);
        $this->load->model('data_model');
        $this->uid = $this->session->userdata('uid');
        $this->name = $this->session->userdata('name');
    }

    public function index(){
        $this->load->view('logistics/index');
    }


    /**
     * @url /logistics/add
     * @param billno 必选 string 单据编号
     * @param logistics_no 必选 string 物流编号
     * @param num 必选 int 货物件数
     * @param operator 必选 int 操作人
     * @param remark  备注
     * @return {"status":200,"msg":"succes"}
     * @return_param status static 1：'200'注册成功;2："-1"注册失败
     */
    public function add(){
        $this->purview_model->checkpurview(90);
        $data = $this->input->post('postData', TRUE);
        if (strlen($data) > 0) {
            $data = (array)json_decode($data);
            if (is_array($data['entries'])) {
                foreach ($data['entries'] as $arr => $row) {
                    $v[$arr]['pk_bom_log_id'] = $row->pk_bom_log_id;
                    $v[$arr]['fk_supplier_id'] = $row->fk_supplier_id;
                    $v[$arr]['name'] = $row->name;
                    $v[$arr]['desc'] = $row->desc;
                    $v[$arr]['signPer_id'] = $row->signPer_id;
                    $v[$arr]['sign_date'] = $row->sign_date;
                    $v[$arr]['status'] = $row->status;
                    $v[$arr]['review_id'] = $row->review_id;
                    $v[$arr]['purOrder_amount'] = $row->purOrder_amount;
                    $v[$arr]['purOrder_payment'] = $row->purOrder_payment;
                    $v[$arr]['creator_id'] = $row->creator_id;
                    $v[$arr]['create_date'] = date('Y-m-d H:i:s', time());
                    $v[$arr]['modify_id'] =  $row->modify_id;
                    $v[$arr]['modify_date'] = date('Y-m-d H:i:s', time());
                }
                $name= $v[$arr]['name'];
                $this->mysql_model->db_inst(BOM_LOGORDER, $v);
                $this->cache_model->delsome(BOM_LOGORDER) ;
                $this->data_model->logs('操作人：ID_' . $name .'新增物流信息');
                die('{"status":200,"msg":"success"}');
               }
            }
        else {
            $this->load->view('logistics/add', $data);
        }
    }

    //物流信息列表
    public function lists() {
        $v = '';
        $data['status'] = 200;
        $data['msg']    = 'success';
        $page = max(intval($this->input->get_post('page',TRUE)),1);
        $rows = max(intval($this->input->get_post('rows',TRUE)),100);
        $key  = str_enhtml($this->input->get_post('matchCon',TRUE));
        $stt  = str_enhtml($this->input->get_post('beginDate',TRUE));
        $ett  = str_enhtml($this->input->get_post('endDate',TRUE));
        $where = '';
        if (strlen($key)>0) {
            $where .= ' and (a.PK_BOM_Log_ID like "%'.$key.'%" or a.FK_Supplier_ID like "%' . $key .'%" )';
        }
        if (strlen($stt)>0) {
            $where .= ' and Create_Date>="'.$stt.'"';
        }
        if (strlen($ett)>0) {
            $where .= ' and Create_Date<="'.$ett.' 23:59:59"';
        }

        $offset = $rows * ($page-1);
        $data['data']['page']= $page;
        //$list = $this->cache_model->load_data(LOGISTICS_INFO,'(1=1) '.$where.' order by id desc limit '.$offset.','.$rows.'');
        $list = $this->data_model->logisticsList($where,' order by PK_BOM_Log_ID desc limit '.$offset.','.$rows.'');
        foreach ($list as $arr=>$row) {
            $v[$arr]['PK_BOM_Log_ID'] = $row['PK_BOM_Log_ID'];
            $v[$arr]['FK_Supplier_ID'] = $row['FK_Supplier_ID'];
            $v[$arr]['Name'] = $row['Name'];
            $v[$arr]['Desc'] = $row['Desc'];
            $v[$arr]['SignPer_ID'] = $row['SignPer_ID'];
            $v[$arr]['Sign_Date'] = $row['Sign_Date'];
            $v[$arr]['Status'] = $row['Status'];
            $v[$arr]['Review_ID'] = $row['Review_ID'];
            $v[$arr]['PurOrder_Amount'] = $row['PurOrder_Amount'];
            $v[$arr]['PurOrde_Payment'] = $row['PurOrde_Payment'];
            $v[$arr]['Creator_ID'] = $row['Creator_ID'];
            $v[$arr]['Create_Date'] = $row['Create_Date'];
//            $v[$arr]['modify_id'] =  $row['modify_id'];
//            $v[$arr]['modify_date'] = date('Y-m-d H:i:s', time());
        }
        $data['data']['records']   = count($list);   //总条数
        $data['data']['total']     = ceil($data['data']['records']/$rows);    //总分页数
        $data['data']['rows']      = is_array($v) ? $v : '';
        die(json_encode($data));
    }

    /**
     * showdoc
     * @catalog 开发文档/采购
     * @title 物流导出
     * @description 物流导出的接口
     * @method get
     * @url https://www.2midcm.com/logistics/export
     * @param id 必选 int 物流信息ID
     * @return "{"status":200,"msg":"success","id":"1"}
     * @return_param status int 1：'200'导出成功;2："-1"导出失败
     * @remark 这里是备注信息
     * @number 1
     */
    public function export()
    {
        $this->purview_model->checkpurview(93);
        sys_xls('物流信息记录.xls');
        $id = str_enhtml($this->input->get_post('pk_bom_log_id', TRUE));
        if (strlen($id) > 0) {
            $where = 'and a.id in (' . $id . ')';
            $list = $this->data_model->logisticsList($where, ' order by id desc ');
            $dataList['data'] = $list;
            $this->load->view('logistics/export', $dataList);
            $this->data_model->logs('导出物流信息记录');
        }
    }

    /**
     * showdoc
     * @catalog 开发文档/采购
     * @title 物流信息删除
     * @description 物流信息删除的接口
     * @method get
     * @url https://www.2midcm.com/invpu/del
     * @param id 必选 int 物流ID
     * @return "{"status":200,"msg":"success","id":"1"}
     * @return_param status int 1：'200'删除成功;2："-1"删除失败
     * @number 1
     */
    public function del() {
        $this->purview_model->checkpurview(92);
        $id   = intval($this->input->get('pk_bom_log_id',TRUE));
        $data = $this->mysql_model->db_one(BOM_LOGORDER,'(id='.$id.')');
        if (count($data)>0) {
            $this->db->trans_begin();
            $this->mysql_model->db_del(BOM_LOGORDER,'(id='.$id.')');
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                die('{"status":-1,"msg":"删除失败"}');
            } else {
                $this->db->trans_commit();
                $this->cache_model->delsome(BOM_LOGORDER);
                $this->data_model->logs('删除物流信息 单据编号：'.json_encode($data));
                die('{"status":200,"msg":"success"}');
            }
        }
        die('{"status":-1,"msg":"删除失败"}');
    }
}

