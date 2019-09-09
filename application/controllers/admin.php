<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends CI_Controller {
    
    public function __construct(){
        parent::__construct();
		$this->purview_model->checkpurview(82);
        $this->load->model('data_model');
		$this->uid   = $this->session->userdata('uid');
    }
	
	public function index(){
/*        $this->load->model('mysql_model');
        $this->mysql_model->countQty();
        exit;*/
		$this->load->view('admin/index');
	}
    /**
     * showdoc
     * @catalog 开发文档/用户
     * @title 用户信息
     * @description 用户信息回显的接口
     * @method get
     * @url https://www.2midcm.com/login/index
     * @param username 必选 string 用户名
     * @param userpwd 必选 string 密码
     * @return {"status":200,"msg":"success,"share":"true","userid":1,"name":"小阳","username":"admin"}
     * @return_param data array 用户回显数据
     * @remark 这里是备注信息
     * @number 1
     */
	public function lists() {
	    $v = array();
	    $data['status'] = 200;
		$data['msg']    = 'success'; 
		$list = $this->data_model->userList('',' order by roleid');
		foreach ($list as $arr=>$row) {
		    $v[$arr]['share']       = true;
			$v[$arr]['admin']       = $row['roleid'] > 0 ? false : true;
		    $v[$arr]['userId']      = intval($row['PK_User_ID']);
			$v[$arr]['isCom']       = intval($row['Status']);
			$v[$arr]['role']        = intval($row['roleid']);
			$v[$arr]['userName']    = $row['Username'];
            $v[$arr]['deptName']    = $row['deptName'];
			$v[$arr]['realName']    = $row['Username'];
			$v[$arr]['shareType']   = 0;
			//$v[$arr]['mobile']      = $row['mobile'];
		}
		$data['data']['items']      = $v;
		$data['data']['shareTotal'] = $this->cache_model->load_total(USER);
		$data['data']['totalsize']  = 3;
		$data['data']['corpID']     = 0;
		die(json_encode($data));
	}
    /**
     * showdoc
     * @catalog 开发文档/用户
     * @title 用户注册
     * @description 用户注册的接口
     * @method get
     * @url https://www.2midcm.com/admin/add
     * @param username 必选 string 用户名
     * @param userpwd 必选 string 密码
     * @param name 必选 string 真实姓名
     * @param mobile 必选 int 手机
     * @return {"status":200,"msg":"success,"share":"true","userid":1,"name":"小阳","username":"admin"}
     * @return_param status static 1：'200'注册成功;2："-1"注册失败
     * @remark 这里是备注信息
     * @number 1
     */
	public function add(){
        $this->purview_model->checkpurview(122);

		$data = str_enhtml($this->input->post(NULL,TRUE));
		if (is_array($data)&&count($data)>0) {
			!isset($data['username']) || strlen($data['username'])<1 && die('{"status":-1,"msg":"用户名不能为空"}'); 
			!isset($data['userpwd']) || strlen($data['userpwd'])<1 && die('{"status":-1,"msg":"密码不能为空"}'); 
			$this->mysql_model->db_count(USER,'(Username="'.$data['username'].'")')>0 && die('{"status":-1,"msg":"用户名已经存在"}');

			$info['Userpwd'] = md6($data['userpwd']);
			$info['Username'] = $data['username'];

		    $sql = $this->mysql_model->db_inst(USER,$info);
			if ($sql) {
			    $this->cache_model->delsome(USER);
				die('{"status":200,"msg":"注册成功","userNumber":"'.$data['username'].'"}');
			} else {
			    die('{"status":-1,"msg":"添加失败"}');  
			}
		} else {
		    $this->load->view('admin/add');
		}
	}

    /**
     * showdoc
     * @catalog 开发文档/用户
     * @title 用户密码
     * @description 用户密码修改的接口
     * @method get
     * @url https://www.2midcm.com/admin/edit
     * @param username 必选 string 用户名
     * @param userpwd 必选 string 密码
     * @param name 必选 string 真实姓名
     * @param mobile 必选 int 手机
     * @return {"status":200,"msg":"success,"share":"true","userid":1,"name":"小阳","username":"admin"}
     * @return_param status static 1：'200'修改成功;2："-1"修改失败
     * @remark 这里是备注信息
     * @number 1
     */
	public function edit(){
        $this->purview_model->checkpurview(122);

		$data = str_enhtml($this->input->post(NULL,TRUE));
		if (is_array($data)&&count($data)>0) {
			!isset($data['userpwd']) || strlen($data['userpwd'])<1 && die('{"status":-1,"msg":"密码不能为空"}');

			$data['Userpwd'] = md6($data['userpwd']);
		    $sql = $this->mysql_model->db_upd(USER,$data,'(PK_User_ID='.$this->uid.')');
			if ($sql) {
			    $this->cache_model->delsome(USER);
				die('{"status":200,"msg":"密码修改成功","userNumber":""}');
			} else {
			    die('{"status":-1,"msg":"修改失败"}');  
			}
		} else {
		    $this->load->view('admin/edit');
		}
	}

	public function authority(){
        $this->purview_model->checkpurview(122);

		$username = str_enhtml($this->input->get_post('username',TRUE));
		$lever    = str_enhtml($this->input->get_post('rightid',TRUE));
		$act = str_enhtml($this->input->get_post('act',TRUE));
		if ($act == 'ok') {
		    $sql = $this->mysql_model->db_upd(USER,array('lever'=>$lever),'(Username="'.$username.'")');
			if ($sql) {
			    $this->cache_model->delsome(USER);
			    die('{"status":200,"msg":"success"}');
			} else {
			    die('{"status":400,"msg":"操作失败"}');
			}
		} else {
		    $data['username'] = $username;
		    $this->load->view('admin/authority',$data);
		}
	}
	
	
	//权限树
	public function tree(){
        $this->purview_model->checkpurview(122);

		$username = str_enhtml($this->input->get_post('username',TRUE));
		if (strlen($username)>0) {
		    $lever = $this->cache_model->load_one(USER,'(Username="'.$username.'")','lever');
			$lever = strlen($lever)>0 ? explode(',',$lever) : array();
		} else {
		    $lever = array();	
		}
		$v = '';
		$data['status'] = 200;
		$data['msg']    = 'success'; 
		$data['data']['totalsize']   = $this->cache_model->load_total(MENU,'(status=1)');   //总条数
		$list = $this->cache_model->load_data(MENU,'(status=1) order by path');  
		foreach ($list as $arr=>$row) {
		    $v[$arr]['fobjectid']  = intval($row['pid']);
			$v[$arr]['fobject']    = $row['title'];
			$v[$arr]['faction']    = $row['pid']==$row['path']? '' : $row['title'];
			$v[$arr]['fright']     = in_array($row['id'],$lever) ? 1 : 0;
			$v[$arr]['frightid']   = intval($row['id']);
		}
		$data['data']['items']      = is_array($v) ? $v : '';
		die(json_encode($data));
	}
	
	//启用停用
	public function doset(){
        $this->purview_model->checkpurview(122);

	    $act = $this->input->get('act',TRUE);
	    $username = str_enhtml($this->input->get('username',TRUE));
		//$username == 'admin' && die('{"status":-1,"msg":"管理员不可操作"}');
		switch ($act) { 
			case 'isstatus': $data['status'] = 1; break;   
			case 'nostatus': $data['status'] = 0; break; 
			default:die('{"status":-1,"msg":"操作失败"}');  
		} 
		$sql = $this->mysql_model->db_upd(USER,$data,'(Username="'.$username.'")');
		if ($sql) {
			$this->cache_model->delsome(USER);
			die('{"status":200,"data":{"userName":"'.$username.'"},"msg":"success"}');  
		} else {
			die('{"status":-1,"msg":"操作失败"}');  
		}
	}
	
	

	
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */