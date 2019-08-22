<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

//bom设计
class Design extends CI_Controller {

    public function __construct(){
        parent::__construct();
        //$this->purview_model->checkpurview(102);
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
            $where .= ' and (a.name like "%'.$key.'%" or b.name like "%' . $key .'%" or c.name like "%'.$key.'%" )';
        }

        $offset = $rows * ($page-1);
        $data['data']['page']      = $page;
        $list = $this->data_model->designList($where,' order by id desc limit '.$offset.','.$rows.'');
        foreach ($list as $arr=>$row) {
            $v[$arr]['pk_bom_desi_id']   = intval($row['pk_bom_desi_id']);
            $v[$arr]['name']    = $row['name'];
            $v[$arr]['desc']  = $row['desc'];
            $v[$arr]['wc_id']  = $row['wc_id'];
            $v[$arr]['upBom_id']  = intval($row['upBom_id']);
            $v[$arr]['downBom_id']       = $row['downBom_id'];
            $v[$arr]['downBom_amount']   = $row['downBom_amount'];
            $v[$arr]['method']   = $row['method'];
            $v[$arr]['formula']  = $row['formula'];
            $v[$arr]['des_coef']  = $row['des_coef'];
            $v[$arr]['creator_id']   = $row['creator_id'];
            $v[$arr]['create_date']   = $row['create_date'];
            $v[$arr]['modify_id']   = $row['modify_id'];
            $v[$arr]['modify_date']   = $row['modify_date'];
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
                    $v[$arr]['pk_bom_desi_id']= intval($row->pk_bom_desi_id);
                    $v[$arr]['name'] = $row->name;
                    $v[$arr]['desc'] = $row->desc;
                    $v[$arr]['wcId'] = $row->wcId;
                    $v[$arr]['up_bom_id'] = intval($row->up_bom_id);
                    $v[$arr]['down_bom_id'] = intval($row->down_bom_id);
                    $v[$arr]['down_bom_amount'] = (float)$row->down_bom_amount;
                    $v[$arr]['method'] = $row->method;
                    $v[$arr]['formula'] = $row->formula;
                    $v[$arr]['des_Coef'] = $row->des_Coef;
                    $v[$arr]['creator_id']   = $row->creator_id;
                    $v[$arr]['creator_date']   = $row->creator_date;
                    $v[$arr]['modify_id']   = $row->modify_id;
                    $v[$arr]['modify_date']   = $row->modify_date;
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
        $id   = intval($this->input->get('PK_BOM_Desi_ID',TRUE));
        $data = $this->input->post('postData',TRUE);
        if (strlen($data)>0) {
            $data = (array)json_decode($data);
            !isset($data['PK_BOM_Desi_ID']) && die('{"status":-1,"msg":"参数错误"}');
            $bomData = $this->mysql_model->db_select(BOM_DESIGN,'(id='.$data['PK_BOM_Desi_ID'].')');

            if(count($bomData) < 1) die('{"status":-1,"msg":"不存在该BOM设计"}');

            $v = array();
            $this->db->trans_begin();

            if (is_array($data['entries'])) {
                foreach ($data['entries'] as $arr=>$row) {
                    $v[$arr]['PK_BOM_Desi_ID']= intval($row->PK_BOM_Desi_ID);
                    $v[$arr]['Name'] = $row->Name;
                    $v[$arr]['Desc'] = $row->Desc;
                    $v[$arr]['wcId'] = $row->wcId;
                    $v[$arr]['up_bom_id'] = intval($row->up_bom_id);
                    $v[$arr]['down_bom_id'] = intval($row->down_bom_id);
                    $v[$arr]['down_bom_amount'] = (float)$row->down_bom_amount;
                    $v[$arr]['Method'] = $row->Method;
                    $v[$arr]['Formula'] = $row->Formula;
                    $v[$arr]['Des_Coef'] = $row->Des_Coef;
                    $v[$arr]['Creator_ID']   = $row->Creator_ID;
                    $v[$arr]['Creator_Date']   = $row->Creator_Date;
                    $v[$arr]['Modify_ID']   = $row->Modify_ID;
                    $v[$arr]['Modify_Date']   = $row->Modify_Date;
                }
            }
            $this->mysql_model->db_upd(BOM_DESIGN, $v, 'PK_BOM_Desi_ID');
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                die('');
            } else {
                $this->db->trans_commit();
                $this->cache_model->delsome(BOM_DESIGN);
                $this->data_model->logs('修改了BOM设计：'.$id);
                die('{"status":200,"msg":"success","data":{"id":'.$id.'}}');
            }
        } else {
            $data = $this->mysql_model->db_one(BOM_DESIGN,'(id='.$id.')');
            if (count($data)>0) {
                $this->load->view('design/edit',$data);
            }
        }
    }

    //BOM设计信息
    public function info(){
        $id   = intval($this->input->get_post('id',TRUE));
        $where = " and a.id = $id";
        $data = $this->data_model->designList($where);
        if (count($data)>0) {
            foreach ($data as $k=>$val){
                $data[$k]['name'] = $val['_name'];
                $data[$k]['desc']= $val['description'];
            }
            $info['status'] = 200;
            $info['msg']    = 'success';
            $info['data']['rows']  = $data;
            $info['data']['entries']     = $data;
            $info['data']['id'] = $data[0]['id'];
            die(json_encode($info));
        } else {
            alert('参数错误');
        }
    }

    //删除BOM设计
    public function del() {
        /*$this->purview_model->checkpurview(105);*/
        $id   = intval($this->input->get('id',TRUE));
        $data = $this->mysql_model->db_one(BOM_DESIGN,'(id='.$id.')');
        if (count($data)>0) {
            $this->db->trans_begin();
            $this->mysql_model->db_del(BOM_DESIGN,'(id='.$id.')');
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