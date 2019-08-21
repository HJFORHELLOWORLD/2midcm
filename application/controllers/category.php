<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Category extends CI_Controller {

    public function __construct(){
        parent::__construct();
		$this->purview_model->checkpurview(73);
		$this->load->model('data_model');
    }
	
	public function index(){
		$this->load->view('category/index');
	}

	public function add(){
	    $this->load->view('category/add');
    }

    /**
     * showdoc
     * @catalog 开发文档/仓库
     * @title 往来单位类别新增
     * @description 类别添加的接口
     * @method get
     * @url https://www.2midcm.com/category/add
     * @param name 可选 string 类别名称
     * @return "status":200,"msg":"success","data":{"id":'.$sql.',"name":"'.$data['name'].'","parentId":'.$data['pid']}
     * @return_param status static 1：'200'新增成功;2："-1"新增失败
     * @remark 这里是备注信息
     * @number 3
     */
	public function save(){
	    $data = $this->input->post('data',TRUE);
	    $data = json_decode($data,true);
        $act = str_enhtml($this->input->get('act', TRUE));
	    $this->purview_model->checkpurview(74);
        $info['pk_industry_id'] = $data['pk_industry_id'];
        $info['name'] = $data['name'];
        $info['desc'] = $data['desc'] ;
        $info['creator_id'] = $data['creator_id'] ;
        $info['create_time'] = $data['create_time'] ;
		$this->mysql_model->db_count(INDUSTRY,'(name="'.$data['name'].'") ') > 0 && die('{"status":-1,"msg":"单位名称重复"}');
        if ($act == 'add') {
            $this->purview_model->checkpurview(69);
            $this->mysql_model->db_count(INDUSTRY, '(name="' . $data['name'] . '")') > 0 && die('{"status":-1,"msg":"单位名称重复"}');
            $sql = $this->mysql_model->db_inst(INDUSTRY, $data);
            if ($sql) {
                $info['id'] = $sql;
                $this->cache_model->delsome(INDUSTRY);
                $this->data_model->logs('新增行业:' . $data['name']);
                die('{"status":200,"msg":"success","data":' . json_encode($info) . '}');
            } else {
                die('{"status":-1,"msg":"添加失败"}');
            }
        }
	}




	
	
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */