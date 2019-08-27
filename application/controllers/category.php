<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

//往来单位类别管理controller
class Category extends CI_Controller {

    public function __construct(){
        parent::__construct();
		$this->purview_model->checkpurview(108);
		$this->load->model('data_model');
        $this->uid   = $this->session->userdata('uid');
    }
	
	public function index(){
		$this->load->view('category/index');
	}

	public function add(){
	    $this->load->view('category/add');
    }

    /**
     * showdoc
     * @catalog 开发文档
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
        $act  = str_enhtml($this->input->get('act',TRUE));
        $id   = intval($this->input->post('id',TRUE));
        $data['Name'] = str_enhtml($this->input->post('name',TRUE));
        $data['Desc'] = str_enhtml($this->input->post('desc',TRUE));
        if ($act=='add') {
            $this->purview_model->checkpurview(109);
            strlen($data['Name']) < 1 && die('{"status":-1,"msg":"名称不能为空"}');
            $this->mysql_model->db_count(INDUSTRY,'(Name="'.$data['Name'].'")') > 0 && die('{"status":-1,"msg":"已存在该往来单位类别"}');
            $data['id'] = $this->mysql_model->db_inst(INDUSTRY,$data);
            if ($data['id']) {
                $this->data_model->logs('新增往来单位类别:'.$data['Name']);
                $this->cache_model->delsome(INDUSTRY);
                die('{"status":200,"msg":"success","data":'.json_encode($data).'}');
            } else {
                die('{"status":-1,"msg":"添加失败"}');
            }
        } elseif ($act=='update') {
            $this->purview_model->checkpurview(110);
            strlen($data['Name']) < 1 && die('{"status":-1,"msg":"名称不能为空"}');
            $this->mysql_model->db_count(INDUSTRY,'(PK_Industry_ID<>'.$id.') and (Name="'.$data['Name'].'")') > 0 && die('{"status":-1,"msg":"已存在该往来单位类别"}');
            $data['Modify_ID'] = $this->uid;
            $data['Modify_Date'] = date('Y-m-d H:i:s',time());
            $sql = $this->mysql_model->db_upd(INDUSTRY,$data,'(PK_Industry_ID='.$id.')');
            if ($sql) {
                $data['id'] = $id;
                $this->data_model->logs('修改往来单位类别:'.$data['Name']);
                $this->cache_model->delsome(INDUSTRY);
                die('{"status":200,"msg":"success","data":'.json_encode($data).'}');
            } else {
                die('{"status":-1,"msg":"修改失败"}');
            }
        }
    }


    //往来单位类别列表
    public function lists() {
        $v = '';
        $data['status'] = 200;
        $data['msg']    = 'success';
        $list = $this->cache_model->load_data(INDUSTRY,'(1=1) order by PK_Industry_ID desc');
        foreach ($list as $arr=>$row) {
            $v[$arr]['id']      = intval($row['PK_Industry_ID']);
            $v[$arr]['Name']    = $row['Name'];
            $v[$arr]['Desc'] = $row['Desc'];
        }
        $data['data']['items']   = is_array($v) ? $v : '';
        $data['data']['totalsize']  = $this->cache_model->load_total(INDUSTRY);
        die(json_encode($data));
    }

    //删除
    public function del(){
        $this->purview_model->checkpurview(111);
        $id = intval($this->input->post('id',TRUE));
        $data = $this->mysql_model->db_one(INDUSTRY,'(PK_Industry_ID='.$id.')');
        if (count($data) > 0) {
            $this->mysql_model->db_count(BETWEENUNIT,'(Industry_ID='.$id.')')>0 && die('{"status":-1,"msg":"发生业务不可删除"}');
            $sql = $this->mysql_model->db_del(INDUSTRY,'(PK_Industry_ID='.$id.')');
            if ($sql) {
                $this->data_model->logs('删除往来单位类别:ID='.$id.' 名称：'.$data['Name']);
                $this->cache_model->delsome(INDUSTRY);
                die('{"status":200,"msg":"success"}');
            } else {
                die('{"status":-1,"msg":"修改失败"}');
            }
        }
    }



	
	
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */