<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Unit extends CI_Controller {

    public function __construct(){
        parent::__construct();
		$this->purview_model->checkpurview(77);
		$this->load->model('data_model');
    }
	
	public function index(){
		$this->load->view('unit/index');
	}

	public function save(){
		$act  = str_enhtml($this->input->get('act',TRUE));
		$id   = intval($this->input->post('id',TRUE));
		$data['name'] = str_enhtml($this->input->post('name',TRUE));
		if ($act=='add') {
		    $this->purview_model->checkpurview(78);
		    strlen($data['name']) < 1 && die('{"status":-1,"msg":"名称不能为空"}'); 
			$this->mysql_model->db_count(UNIT,'(name="'.$data['name'].'")') > 0 && die('{"status":-1,"msg":"单位名称重复"}');
		    $data['id'] = $this->mysql_model->db_inst(UNIT,$data);
			if ($data['id']) {
			    $this->data_model->logs('新增单位:'.$data['name']);
				$this->cache_model->delsome(UNIT);
				die('{"status":200,"msg":"success","data":'.json_encode($data).'}');
			} else {
			    die('{"status":-1,"msg":"添加失败"}');
			}
		} elseif ($act=='update') {
		    $this->purview_model->checkpurview(79);
			strlen($data['name']) < 1 && die('{"status":-1,"msg":"名称不能为空"}'); 
			$this->mysql_model->db_count(UNIT,'(id<>'.$id.') and (name="'.$data['name'].'")') > 0 && die('{"status":-1,"msg":"单位名称重复"}');
		    $sql = $this->mysql_model->db_upd(UNIT,$data,'(id='.$id.')');
			if ($sql) {
			    $data['id'] = $id;
			    $this->data_model->logs('修改单位:'.$data['name']);
			    $this->cache_model->delsome(UNIT);
				die('{"status":200,"msg":"success","data":'.json_encode($data).'}');
			} else {
				die('{"status":-1,"msg":"修改失败"}');
			}
		}
	}

	//删除
    public function del(){
	    $this->purview_model->checkpurview(80);
	    $id = intval($this->input->post('id',TRUE));
		$data = $this->mysql_model->db_one(UNIT,'(id='.$id.')');   
		if (count($data) > 0) {
		    $this->mysql_model->db_count(BOM_BASE,'(FK_UnitClass_ID='.$id.')')>0 && die('{"status":-1,"msg":"发生业务不可删除"}');
		    $sql = $this->mysql_model->db_del(UNIT,'(id='.$id.')');   
		    if ($sql) {
			    $this->data_model->logs('删除单位:ID='.$id.' 名称：'.$data['name']);
			    $this->cache_model->delsome(UNIT);
				die('{"status":200,"msg":"success"}');
			} else {
			    die('{"status":-1,"msg":"修改失败"}');
			}
		}
	}


}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */