<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Vendor extends CI_Controller {

    public function __construct(){
        parent::__construct();
		$this->purview_model->checkpurview(63);
		$this->load->model('data_model');
    }
	
	public function index(){
		$this->load->view('vendor/index');
	}


    /**
     * showdoc
     * @catalog 开发文档/设置
     * @title 供应商新增
     * @description 供应商添加修改的接口
     * @method get
     * @url https://www.2midcm.com/vendor/save
     * @param name 可选 string 供应商名称
     * @param number 可选 string 供应商编号
     * @param categoryid 可选 string 供应商类别
     * @param categoryname 必选 string 供应商名称
     * @param linkmans 必选 string 供应商联系方式
     * @return {"status":200,"msg":"success","data":'.json_encode($info).'}
     * @return_param status static 1：'200'新增或修改成功;2："-1"新增或修改失败
     * @remark 这里是备注信息
     * @number 5
     */
    public function save() {
	    $id = intval($this->input->get_post('id',TRUE));
		$act = str_enhtml($this->input->get('act',TRUE));
		$data['type'] = 2; 
		$data['linkmans']    = $this->input->post('linkMans',TRUE);
		$info['amount']      = $data['amount']      = str_enhtml($this->input->post('amount',TRUE));
		$info['beginDate']   = $data['begindate']   = str_enhtml($this->input->post('beginDate',TRUE));
		$info['cCategory']   = $data['categoryid']  = intval($this->input->post('cCategory',TRUE));
		$info['name']        = $data['name']        = str_enhtml($this->input->post('name',TRUE));
		$info['number']      = $data['number']      = str_enhtml($this->input->post('number',TRUE));
		$info['periodMoney'] = $data['periodmoney'] = str_enhtml($this->input->post('periodMoney',TRUE));
		//$info['beginDate']   = $data['beginDate']     = str_enhtml($this->input->post('beginDate',TRUE));
		$info['taxRate']     = $data['taxrate']     = str_enhtml($this->input->post('taxRate',TRUE));
		$info['remark']      = $data['remark']     = str_enhtml($this->input->post('remark',TRUE));
		$data['contact']     = $data['number'].' '.$data['name'].' '.$data['linkmans'];
		$info['links']       = array();
		if (strlen($data['linkmans'])>0) {
			$list = (array)json_decode($data['linkmans']);
			if (count($list)>0) {
				foreach ($list as $arr=>$row) {
					if ($row->linkFirst==1) {
						$info['links'][0]['name']    = $row->linkName;
						$info['links'][0]['mobile']  = $row->linkMobile; 
						$info['links'][0]['phone']   = $row->linkPhone; 
						$info['links'][0]['im']      = $row->linkIm; 
						$info['links'][0]['first']   = $row->linkFirst; 
					}
				} 
			}
		}
		//新增
		if ($act=='add') {
		    $this->purview_model->checkpurview(64);
			strlen($data['name']) < 1 && die('{"status":-1,"msg":"供应商名称不能为空"}'); 
			$this->mysql_model->db_count(CONTACT,'(type=2) and (number="'.$data['number'].'")') > 0 && die('{"status":-1,"msg":"供应商编号重复"}');
			$info['cCategoryName']  = $data['categoryname'] = $this->mysql_model->db_one(CATEGORY,'(id='.$data['categoryid'].')','name');
		    $sql = $this->mysql_model->db_inst(CONTACT,array_filter($data));
			if ($sql) {
			    $info['id'] = $sql;
				$this->cache_model->delsome(CONTACT);
				$this->data_model->logs('新增供应商:'.$data['name']);
				die('{"status":200,"msg":"success","data":'.json_encode($info).'}');
			} else {
			    die('{"status":-1,"msg":"添加失败"}');
			}
		//修改	
		} elseif ($act=='update') {
		    $this->purview_model->checkpurview(65);
			strlen($data['name']) < 1 && die('{"status":-1,"msg":"供应商名称不能为空"}'); 
			$this->mysql_model->db_count(CONTACT,'(type=2) and (id<>'.$id.') and (number="'.$data['number'].'")') > 0 && die('{"status":-1,"msg":"供应商编号重复"}');
			$info['cCategoryName'] = $data['categoryname'] = $this->mysql_model->db_one(CATEGORY,'(id='.$data['categoryid'].')','name');
			$name = $this->mysql_model->db_one(CONTACT,'(id='.$id.')','name');
		    $sql = $this->mysql_model->db_upd(CONTACT,array_filter($data),'(id='.$id.')');
			if ($sql) {
			    //更新购货表供应商信息
				$v['contactname'] = $info['number'].' '.$info['name'];
			    $this->mysql_model->db_upd(INVPU,$v,'(contactid='.$id.')');  
				$this->mysql_model->db_upd(INVPU_INFO,$v,'(contactid='.$id.')');  
			    $this->cache_model->delsome(INVPU);
				$this->cache_model->delsome(CONTACT);
				$this->cache_model->delsome(INVPU_INFO);
				$this->data_model->logs('修改供应商:'.$name.' 修改为 '.$data['name']);
				$info['id'] = $id;
				die('{"status":200,"msg":"success","data":'.json_encode($info).'}');
			} else {
				die('{"status":-1,"msg":"修改失败"}');
			}
		}
		die('{"status":-1,"msg":"操作失败"}');
	}

    /**
     * showdoc
     * @catalog 开发文档/设置
     * @title 供应商导出
     * @description 供应商导出的接口
     * @method get
     * @url https://www.2midcm.com/vendor/export
     * @param id 必选 int 供应商ID
     * @return "{"status":200,"msg":"success","id":"1"}
     * @return_param status int 1：'200'导出成功;2："-1"导出失败
     * @remark 这里是备注信息
     * @number 5
     */
	public function export() {
	    $this->purview_model->checkpurview(67);
	    sys_xls('供应商.xls');
		$skey   = str_enhtml($this->input->get('skey',TRUE));
		$where  = ' and type=2';
		if ($skey) {
			$where .= ' and contact like "%'.$skey.'%"';
		}
		$this->data_model->logs('导出供应商');
		$data['list'] = $this->cache_model->load_data(CONTACT,'(status=1) '.$where.' order by id desc');   
		$this->load->view('vendor/export',$data);
	}


    /**
     * showdoc
     * @catalog 开发文档/设置
     * @title 供应商删除
     * @description 供应商删除的接口
     * @method get
     * @url https://www.2midcm.com/vendor/del
     * @param id 必选 int 供应商ID
     * @return "{"status":200,"msg":"success","id":"1"}
     * @return_param status int 1：'200'删除成功;2："-1"删除失败
     * @remark 这里是备注信息
     * @number 5
     */
	public function del() {
	    $this->purview_model->checkpurview(66);
	    $id = str_enhtml($this->input->post('id',TRUE));
		if (strlen($id) > 0) {
		    $this->mysql_model->db_count(INVPU,'(contactid in('.$id.'))')>0 && die('{"status":-1,"msg":"其中有供应商发生业务不可删除"}'); 
		    $sql = $this->mysql_model->db_del(CONTACT,'(id in('.$id.'))');   
		    if ($sql) {
			    $this->cache_model->delsome(CONTACT);
				$this->data_model->logs('删除供应商:ID='.$id);
				die('{"status":200,"msg":"success","data":{"msg":"","id":['.$id.']}}');
			} else {
			    die('{"status":-1,"msg":"删除失败"}');
			}
		}
	}

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */