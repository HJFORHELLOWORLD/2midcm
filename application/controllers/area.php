<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Area extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->purview_model->checkpurview(116);
        $this->load->model('data_model');
        $this->uid   = $this->session->userdata('uid');
    }

    public function index(){
    $this->load->view('area/index');
    }

    public function add()
    {
        $this->load->view('area/add');
    }


    /**
     * showdoc
     * @catalog 开发文档/地区
     * @title 地区新增
     * @description 商品添加修改的接口
     * @method get
     * @url https://www.2midcm.com/area/save
     * @param  pk_area_id 可选 string 地区编码
     * @param  upArea_id 可选 string  上级区域
     * @param  name  必选 string  地区名称
     * @param  creator_id   可选 string  创建人
     * @param number 可选 string 编号
     * @return {"status":200,"msg":"success"}
     * @return_param status string 1："200"新增或修改成功,2:"-1"新增或修改失败
     * @remark 这里是备注信息
     * @number 3
     */
    public function save(){
        $act  = str_enhtml($this->input->get('act',TRUE));
        $id   = intval($this->input->post('id',TRUE));
        $data['Name'] = str_enhtml($this->input->post('name',TRUE));
        $data['UpArea_ID'] = str_enhtml($this->input->post('uparea_id',TRUE));
        if ($act=='add') {
            $this->purview_model->checkpurview(117);
            strlen($data['Name']) < 1 && die('{"status":-1,"msg":"名称不能为空"}');
            $this->mysql_model->db_count(AREA,'(Name="'.$data['Name'].'")') > 0 && die('{"status":-1,"msg":"已存在该地区分类"}');
            $data['id'] = $this->mysql_model->db_inst(AREA,$data);
            $data['upareaName'] = str_enhtml($this->input->post('uparea_name',TRUE));
            if ($data['id']) {
                $this->data_model->logs('新增地区分类:'.$data['Name']);
                $this->cache_model->delsome(AREA);
                die('{"status":200,"msg":"success","data":'.json_encode($data).'}');
            } else {
                die('{"status":-1,"msg":"添加失败"}');
            }
        } elseif ($act=='update') {
            $this->purview_model->checkpurview(118);
            strlen($data['Name']) < 1 && die('{"status":-1,"msg":"名称不能为空"}');
            $this->mysql_model->db_count(AREA,'(PK_Area_ID<>'.$id.') and (Name="'.$data['Name'].'")') > 0 && die('{"status":-1,"msg":"已存在该地区分类"}');
            $data['Modify_ID'] = $this->uid;
            $data['Modify_Date'] = date('Y-m-d H:i:s',time());
            $sql = $this->mysql_model->db_upd(AREA,$data,'(PK_Area_ID='.$id.')');
            if ($sql) {
                $data['id'] = $id;
                $data['upareaName'] = str_enhtml($this->input->post('uparea_name',TRUE));
                $this->data_model->logs('修改地区分类:'.$data['Name']);
                $this->cache_model->delsome(AREA);
                die('{"status":200,"msg":"success","data":'.json_encode($data).'}');
            } else {
                die('{"status":-1,"msg":"修改失败"}');
            }
        }
    }

    //删除
    public function del(){
        $this->purview_model->checkpurview(119);
        $id = intval($this->input->post('id',TRUE));
        $data = $this->mysql_model->db_one(AREA,'(PK_Area_ID='.$id.')');
        if (count($data) > 0) {
            $this->mysql_model->db_count(BETWEENUNIT,'(Area_ID='.$id.')')>0 && die('{"status":-1,"msg":"发生业务不可删除"}');
            $sql = $this->mysql_model->db_del(AREA,'(PK_Area_ID='.$id.')');
            if ($sql) {
                $this->data_model->logs('删除往来单位类别:ID='.$id.' 名称：'.$data['Name']);
                $this->cache_model->delsome(AREA);
                die('{"status":200,"msg":"success"}');
            } else {
                die('{"status":-1,"msg":"修改失败"}');
            }
        }
    }





}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */