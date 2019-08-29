<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class User extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->purview_model->checkpurview(68);
        $this->load->model('data_model');
    }

    public function index(){
    $this->load->view('user/index');
    }

    public function add()
    {
        $this->load->view('user/add');
    }


    /**
     * showdoc
     * @catalog 开发文档/地区
     * @title 地区新增
     * @description 商品添加修改的接口
     * @method get
     * @url https://www.2midcm.com/area/save
     * @param  PK_User_ID 可选 string 用户编号
     * @param  Part_ID 可选 string  部门编号
     * @param  Username  必选 string  地区名称
     * @param  creator_id   可选 string  创建人
     * @param Status 可选 string 编号
     * @return {"status":200,"msg":"success"}
     * @return_param status string 1："200"新增或修改成功,2:"-1"新增或修改失败
     * @remark 这里是备注信息
     * @number 3
     */
    public function save(){
        $act  = str_enhtml($this->input->get('act',TRUE));
        $PK_User_ID   = intval($this->input->post('id',TRUE));
        $data['Username'] = str_enhtml($this->input->post('Username',TRUE));
        if ($act=='add') {
            $this->purview_model->checkpurview(78);
            strlen($data['Username']) < 1 && die('{"status":-1,"msg":"名称不能为空"}');
            $this->mysql_model->db_count(USER,'(Username="'.$data['Username'].'")') > 0 && die('{"status":-1,"msg":"姓名重复"}');
            $data['PK_User_ID'] = $this->mysql_model->db_inst(USER,$data);
            if ($data['PK_User_ID']) {
                $this->data_model->logs('新增人员:'.$data['Username']);
                $this->cache_model->delsome(USER);
                die('{"status":200,"msg":"success","data":'.json_encode($data).'}');
            } else {
                die('{"status":-1,"msg":"添加失败"}');
            }
        } elseif ($act=='update') {
            $this->purview_model->checkpurview(79);
            strlen($data['Username']) < 1 && die('{"status":-1,"msg":"名称不能为空"}');
            $this->mysql_model->db_count(USER,'(PK_User_ID<>'.$PK_User_ID.') and (Username="'.$data['Username'].'")') > 0 && die('{"status":-1,"msg":"姓名重复"}');
            $sql = $this->mysql_model->db_upd(USER,$data,'(PK_User_ID='.$PK_User_ID.')');
            if ($sql) {
                $data['PK_User_ID'] = $PK_User_ID;
                $this->data_model->logs('修改人员:'.$data['Username']);
                $this->cache_model->delsome(USER);
                die('{"status":200,"msg":"success","data":'.json_encode($data).'}');
            } else {
                die('{"status":-1,"msg":"修改失败"}');
            }
        }
    }

    //删除
    public function del(){
        $this->purview_model->checkpurview(80);
        $PK_User_ID = intval($this->input->post('PK_User_ID',TRUE));
        $data = $this->mysql_model->db_one(USER,'(PK_User_ID='.$PK_User_ID.')');
        if (count($data) > 0) {
            $this->mysql_model->db_count(BOM_BASE,'(Creator_ID='.$PK_User_ID.')')>0 && die('{"status":-1,"msg":"发生业务不可删除"}');
            $sql = $this->mysql_model->db_del(USER,'(PK_User_ID='.$PK_User_ID.')');
            if ($sql) {
                $this->data_model->logs('删除人员:ID='.$PK_User_ID.' 名称：'.$data['Username']);
                $this->cache_model->delsome(USER);
                die('{"status":200,"msg":"success"}');
            } else {
                die('{"status":-1,"msg":"修改失败"}');
            }
        }
    }





}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */