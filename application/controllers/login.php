<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Login extends CI_Controller {

	public function __construct(){
        parent::__construct();
		$this->load->model(array('login_model','data_model'));
    }
    /**
     * showdoc
     * @catalog 开发文档/用户
     * @title 用户登录
     * @description 用户登录的接口
     * @method post
     * @url https://www.2midcm.com/login/index
     * @param username 必选 string 用户名
     * @param userpwd 必选 string 密码
     * @return {"token":1,"data":{"username":"admin","name":"小阳"，"groupid": 2 ,"reg_time": "1436864169","last_login_time": "0"}}
     * @return_param roleid int 用户组id
     * @remark 这里是备注信息
     * @number 1
     */
	public function index(){
	    $data = str_enhtml($this->input->post(NULL,TRUE));
		if (is_array($data)&&count($data)>0) {
		    !token(1) && die('token验证失败'); 
			!isset($data['username']) || strlen($data['username']) < 1 && die('用户名不能为空'); 
			!isset($data['userpwd'])  || strlen($data['userpwd']) < 1  && die('密码不能为空'); 
			$sql = $this->login_model->login($data['username'],$data['userpwd']);
			if ($sql) {
			    if (isset($data['ispwd']) && $data['ispwd'] == 1) {
					$this->input->set_cookie('username',$data['username'],3600000); 
					$this->input->set_cookie('userpwd',$data['userpwd'],3600000); 
				} 
				$this->input->set_cookie('ispwd',$data['ispwd'],3600000);
			    $this->data_model->logs('登陆成功 用户名：'.$data['username']);
			    die('1'); 
			}
			die('账号或密码错误');
		} else {	
			$this->load->view('login',$data);
		}
	}
	
	public function out(){
	    $this->login_model->loginout();
		redirect(site_url('login'));
	}
	
	public function code(){
	    $this->load->library('lib_code');
		$this->lib_code->image();
	}
	 

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */