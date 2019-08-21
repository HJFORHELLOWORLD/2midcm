<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Api extends CI_Controller {

    public function __construct(){
        parent::__construct();
		$this->load->model('data_model');
    }
	
	
	//购货打印
	public function invpu_print(){
		$id  = intval($this->input->get_post('id',TRUE));
		$data = $this->cache_model->load_one(INVPU,'(id='.$id.')');  
		if (count($data)>0) {
		    $linkmans = $this->cache_model->load_one(CONTACT,'(id='.$data['contactid'].')','linkmans');  //获取客户信息
			$data['links'] = array('name'=>'','phone'=>'','address'=>'');
			if (strlen($linkmans)>0) {
				$list = (array)json_decode($linkmans);
				foreach ($list as $arr=>$row) {
					if ($row->linkFirst==1) {
						$data['links']['name']     = $row->linkName;
						$data['links']['mobile']   = $row->linkMobile; 
						$data['links']['phone']    = $row->linkPhone; 
						$data['links']['im']       = $row->linkIm; 
						$data['links']['first']    = $row->linkFirst; 
						//$data['links']['address']  = $row->linkAddress; 
					}
				} 
		    }
			$data['list'] = $this->data_model->invpu_info(' and (a.invpuid='.$id.')');  
			$this->load->view('invpu/print',$data);
		}	 
	}
	
	//销货打印
	public function invsa_print(){
		$id  = intval($this->input->get_post('id',TRUE));
		$data = $this->cache_model->load_one(INVSA,'(id='.$id.')');  
		if (count($data)>0) {
		    $linkmans = $this->cache_model->load_one(CONTACT,'(id='.$data['contactid'].')','linkmans');  //获取客户信息
			$data['links'] = array('name'=>'','phone'=>'','address'=>'');
			if (strlen($linkmans)>0) {
				$list = (array)json_decode($linkmans);
				foreach ($list as $arr=>$row) {
					if ($row->linkFirst==1) {
						$data['links']['name']     = $row->linkName;
						$data['links']['mobile']   = $row->linkMobile; 
						$data['links']['phone']    = $row->linkPhone; 
						$data['links']['im']       = $row->linkIm; 
						$data['links']['first']    = $row->linkFirst; 
						$data['links']['address']  = $row->linkAddress; 
					}
				} 
		    }
			$data['list'] = $this->data_model->invsa_info(' and (a.invsaid='.$id.')');  
			$this->load->view('invsa/print',$data);
		}	  
	}
	
	//其他打印
	public function invoi_print(){
		$id  = intval($this->input->get_post('id',TRUE));
		$data = $this->cache_model->load_one(INVOI,'(id='.$id.')');  
		if (count($data)>0) {
		    $linkmans = $this->cache_model->load_one(CONTACT,'(id='.$data['contactid'].')','linkmans');  //获取客户信息
			$data['links'] = array('name'=>'','phone'=>'','address'=>'');
			if (strlen($linkmans)>0) {
				$list = (array)json_decode($linkmans);
				foreach ($list as $arr=>$row) {
					if ($row->linkFirst==1) {
						$data['links']['name']     = $row->linkName;
						$data['links']['mobile']   = $row->linkMobile; 
						$data['links']['phone']    = $row->linkPhone; 
						$data['links']['im']       = $row->linkIm; 
						$data['links']['first']    = $row->linkFirst; 
						$data['links']['address']  = $data['billtype']==1?'':$row->linkAddress; 
					}
				} 
		    }
			$data['list'] = $this->data_model->invoi_info(' and (a.invoiid='.$id.')');  
			if ($data['billtype']==1) {
				$this->load->view('invoi/inprint',$data);
			} else {
				$this->load->view('invoi/outprint',$data);
			}
		}	  
	}

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */