<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller {

    public function __construct(){
        parent::__construct();
		$this->purview_model->checkpurview();
		$this->uid  = $this->session->userdata('uid');
		$this->name = $this->session->userdata('name');
    }
	
	public function index(){
	    $data['uid']      = $this->uid;
		$data['name']     = $this->name;
		$data['lever']    = $this->session->userdata('lever');
		$data['username'] = $this->session->userdata('username');
		$this->load->view('index',$data);
	}
	
	//右边内容
	public function main(){
		$this->load->view('main');
	}
	
	
	//密码修改
	public function editpwd(){
		$userpwd = str_enhtml($this->input->post('userpwd',TRUE));
		if (strlen($userpwd) > 0) {
			$data['userpwd'] = md6($userpwd);
		    $sql = $this->mysql_model->db_upd(USER,$data,'(PK_User_ID='.$this->uid.')');
			if ($sql) {
			    $this->cache_model->delsome(USER);
				$this->load->model('data_model');
				$this->data_model->logs('密码修改成功 用户名：'.$this->name);
				die('{"status":200,"msg":"密码修改成功"}');
			} else {
			    die('{"status":-1,"msg":"修改失败"}');  
			}
		} else {
		    $this->load->view('admin/edit');
		}
	}
	
	//清理缓存
	public function clear(){
        if($this->cache->get('inventory.dataLock')){
            $invCacheData = $this->cache->get('inventory.dataLock');
        }
		if ($this->cache_model->clean()) {
           isset($invCacheData) && $this->cache->save('inventory.datalock',$invCacheData,8);
		    die('1');
		} else {
		    die('0');
		}
	}
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */