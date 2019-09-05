<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

//bom设计
class Design extends CI_Controller {

    public function __construct(){
        parent::__construct();
        $this->purview_model->checkpurview(102);
        $this->load->model('data_model');
        $this->uid = $this->session->userdata('uid');
        $this->name = $this->session->userdata('name');
    }

    public function index(){
        $this->load->view('design/index');
    }

    //bom设计列表
    public function lists(){
        $v = '';
        $data['status'] = 200;
        $data['msg']    = 'success';
        $page = max(intval($this->input->get_post('page',TRUE)),1);
        $rows = max(intval($this->input->get_post('rows',TRUE)),100);
        $key  = str_enhtml($this->input->get_post('matchCon',TRUE));
        $where = '';
        if (strlen($key)>0) {
            $where .= ' and (a.Name like "%'.$key.'%" or b.BOMName like "%' . $key .'%" or c.BOMName like "%'.$key.'%" )';
        }

        $offset = $rows * ($page-1);
        $data['data']['page']      = $page;
        $list = $this->data_model->designList($where,' order by PK_BOM_Desi_ID desc limit '.$offset.','.$rows.'');
        foreach ($list as $arr=>$row) {
            $v[$arr]['id']   = intval($row['PK_BOM_Desi_ID']);
            $v[$arr]['Name']    = $row['Name'];
            $v[$arr]['WC_ID']  = $row['WC_ID'];
            $v[$arr]['WC_Name']  = $row['WC_Name'];
            $v[$arr]['UpBOM_ID']  = intval($row['UpBOM_ID']);
            $v[$arr]['DownBOM_ID']       = $row['DownBOM_ID'];
            $v[$arr]['UpBOM_Name']  = $row['UpBOM_Name'];
            $v[$arr]['DownBOM_Name']       = $row['DownBOM_Name'];
            $v[$arr]['DownBOM_Amount']   = $row['DownBOM_Amount'];
            $v[$arr]['Desc']   = $row['Desc'];
            $v[$arr]['Method']   = $row['Method'];
            $v[$arr]['Formula']  = $row['Formula'];
            $v[$arr]['Des_Coef']  = $row['Des_Coef'];
        }
        $data['data']['records']   = count($list);   //总条数
        $data['data']['total']     = ceil($data['data']['records']/$rows);    //总分页数
        $data['data']['rows']      = is_array($v) ? $v : '';
        die(json_encode($data));
    }

    //添加Bom设计
    public function add()
    {
        $this->purview_model->checkpurview(103);
        $data = $this->input->post('postData', TRUE);
        if (strlen($data) > 0) {
            $data = (array)json_decode($data);
            if (is_array($data['entries'])) {
                foreach ($data['entries'] as $arr => $row) {
                    $v[$arr]['Name'] = $row->name;
                    $v[$arr]['Desc'] = $row->desc;
                    $v[$arr]['WC_ID'] = intval($row->wc_id);
                    $v[$arr]['UpBOM_ID'] = intval($row->up_bom_id);
                    $v[$arr]['DownBOM_ID'] = intval($row->down_bom_id);
                    $v[$arr]['DownBOM_Amount'] = (float)$row->down_bom_number;
                   //$v[$arr]['method'] = $row->method;
                   // $v[$arr]['formula'] = $row->formula;
                    //$v[$arr]['des_Coef'] = $row->des_Coef;
                    $v[$arr]['creator_id']   = $this->uid;
                }
                $designId = $this->mysql_model->db_inst(BOM_DESIGN, $v);

                if ($this->db->trans_status() === FALSE) {
                    $this->db->trans_rollback();
                    die();
                } else {
                    $this->db->trans_commit();
                    $this->cache_model->delsome(BOM_DESIGN);
                    $this->data_model->logs('操作人：' . $this->name .'新增bom设计：'. $designId);
                    die('{"status":200,"msg":"success"}');
                }
            }
        }else {
            $this->load->view('design/add', $data);
        }
    }

    //修改BOM设计
    public function edit(){
        $this->purview_model->checkpurview(104);
        $id   = intval($this->input->get('id',TRUE));
        $data = $this->input->post('postData',TRUE);
        if (strlen($data)>0) {
            $data = (array)json_decode($data);
            !isset($data['id']) && die('{"status":-1,"msg":"参数错误"}');
            $bomData = $this->mysql_model->db_select(BOM_DESIGN,'(PK_BOM_Desi_ID='.$data['id'].')');

            if(count($bomData) < 1) die('{"status":-1,"msg":"不存在该BOM设计"}');

            $v = array();
            $this->db->trans_begin();

            if (is_array($data['entries'])) {
                foreach ($data['entries'] as $arr=>$row) {
                    $v[$arr]['PK_BOM_Desi_ID']= intval($data['id']);
                    $v[$arr]['Name'] = $row->name;
                    $v[$arr]['Desc'] = $row->desc;
                    $v[$arr]['WC_ID'] = $row->wc_id;
                    $v[$arr]['UpBOM_ID'] = intval($row->up_bom_id);
                    $v[$arr]['DownBOM_ID'] = intval($row->down_bom_id);
                    $v[$arr]['DownBOM_Amount'] = (float)$row->down_bom_number;
                    //$v[$arr]['Method'] = $row->Method;
                    //$v[$arr]['Formula'] = $row->Formula;
                    //$v[$arr]['Des_Coef'] = $row->Des_Coef;
                    $data['Modify_ID'] = $this->uid;
                    $data['Modify_Date'] = date('Y-m-d H:i:s',time());
                }
            }
            $this->mysql_model->db_upd(BOM_DESIGN, $v, 'PK_BOM_Desi_ID');
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                die('');
            } else {
                $this->db->trans_commit();
                $this->cache_model->delsome(BOM_DESIGN);
                $this->data_model->logs('修改了BOM设计：'.$data['id']);
                die('{"status":200,"msg":"success","data":{"id":'.$data['id'].'}}');
            }
        } else {
            $data = $this->mysql_model->db_one(BOM_DESIGN,'(PK_BOM_Desi_ID='.$id.')');
            if (count($data)>0) {
                $this->load->view('design/edit',$data);
            }
        }
    }

    //BOM设计信息
    public function info(){
        $id   = intval($this->input->get_post('id',TRUE));
        $where = " and a.PK_BOM_Desi_ID = $id";
        $data = $this->data_model->designList($where);
        $v = array();
        if (count($data)>0) {
            foreach ($data as $arr=>$row){
                $v[$arr]['id']   = intval($row['PK_BOM_Desi_ID']);
                $v[$arr]['name']    = $row['Name'];
                $v[$arr]['WC_ID']  = $row['WC_ID'];
                $v[$arr]['WC_Name']  = $row['WC_Name'];
                $v[$arr]['up_bom_id']  = intval($row['UpBOM_ID']);
                $v[$arr]['down_bom_id']       = $row['DownBOM_ID'];
                $v[$arr]['up_bom_name']  = $row['UpBOM_Name'];
                $v[$arr]['down_bom_name']       = $row['DownBOM_Name'];
                $v[$arr]['downbom_Amount']   = $row['DownBOM_Amount'];
                $v[$arr]['desc']   = $row['Desc'];
                $v[$arr]['Method']   = $row['Method'];
                $v[$arr]['Formula']  = $row['Formula'];
                $v[$arr]['Des_Coef']  = $row['Des_Coef'];
            }
            $info['status'] = 200;
            $info['msg']    = 'success';
            $info['data']['rows']  = $v;
            $info['data']['entries']     = $v;
            $info['data']['id'] = $v[0]['id'];
            die(json_encode($info));
        } else {
            alert('参数错误');
        }
    }

    //删除BOM设计
    public function del() {
        $this->purview_model->checkpurview(105);
        $id   = intval($this->input->get('id',TRUE));
        $data = $this->mysql_model->db_one(BOM_DESIGN,'(PK_BOM_Desi_ID='.$id.')');
        if (count($data)>0) {
            $this->db->trans_begin();
            $this->mysql_model->db_del(BOM_DESIGN,'(PK_BOM_Desi_ID='.$id.')');
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                die('{"status":-1,"msg":"删除失败"}');
            } else {
                $this->db->trans_commit();
                $this->cache_model->delsome(BOM_DESIGN);
                $this->data_model->logs('删除BOM设计：'.json_encode($data,JSON_UNESCAPED_UNICODE));
                die('{"status":200,"msg":"success"}');
            }
        }
        die('{"status":-1,"msg":"删除失败"}');
    }

}