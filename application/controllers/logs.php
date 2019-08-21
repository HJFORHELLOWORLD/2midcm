<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Logs extends CI_Controller {

    public function __construct(){
        parent::__construct();
		$this->purview_model->checkpurview(83);
    }
	
	public function index(){
		$this->load->view('logs/index');
	}

    /**
     * showdoc
     * @catalog 开发文档/设置
     * @title 操作日志
     * @description 日志导出的接口
     * @method get
     * @url https://www.2midcm.com/logs/export
     * @param id 必选 int 购货单ID
     * @return {sys_xls('日志明细.xls');
    $user   = str_enhtml($this->input->get('user',TRUE));
    $where = '';
    if ($user) {
    $where .= ' and username="'.$user.'"';
    }
    $data['list'] = $this->cache_model->load_data(LOG,'(1=1) '.$where.' order by id desc');
    $this->load->view('logs/export',$data);}
     * @return_param status int 1：'200'导出成功;2："-1"导出失败
     * @remark 这里是备注信息
     * @number 1
     */
	public function export() {
	    sys_xls('日志明细.xls');
/*		$user   = str_enhtml($this->input->get('user',TRUE));
		$where = '';
		if ($user) {
			$where .= ' and username="'.$user.'"';
		}*/

        $stt  = str_enhtml($this->input->get('fromDate',TRUE));
        $ett  = str_enhtml($this->input->get('toDate',TRUE));
        $page = max(intval($this->input->get_post('page',TRUE)),1);
        $rows = max(intval($this->input->get_post('rows',TRUE)),100);
        $where = '';

        if ($stt) {
            $where .= ' and Log_Date>="'.$stt.'"';
        }
        if ($ett) {
            $where .= ' and Log_Date<="'.$ett.' 23:59:59"';
        }

        $data['list'] = $this->data_model->logList($where, ' order by Log_Date desc');

		$data['list'] = $this->cache_model->load_data(LOG,'(1=1) '.$where.' order by id desc');
		$this->load->view('logs/export',$data);
	}	
}

