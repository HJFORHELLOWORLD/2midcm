<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Area extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->purview_model->checkpurview(68);
        $this->load->model('data_model');
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
    public function save()
    {
        $data = ($this->input->post('data',TRUE));
        $data= json_decode($data,true);
        $act = str_enhtml($this->input->get('act', TRUE));
        $info['pk_area_id'] = $data['pk_area_id'];
        $info['upArea_id'] = $data['upArea_id'];
        $info['name'] = $data['name'];
        $info['creator_id'] = $data['creator_id'];
        if ($act == 'add') {
            $this->purview_model->checkpurview(69);
            $this->mysql_model->db_count(AREA, '(name="' . $data['name'] . '")') > 0 && die('{"status":-1,"msg":"地区编号重复"}');
            $sql = $this->mysql_model->db_inst(AREA, $data);
            if ($sql) {
                $info['id'] = $sql;
                $this->cache_model->delsome(AREA);
                $this->data_model->logs('新增地区:' . $data['name']);
                die('{"status":200,"msg":"success","data":' . json_encode($info) . '}');
            } else {
                die('{"status":-1,"msg":"添加失败"}');
            }
        }
    }





}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */