<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

//报表模块
class Report extends CI_Controller {

    public function __construct(){
        parent::__construct();
		$this->load->model('data_model');
    }
	
	//------------------------------------采购报表-------------------------------------------------------------
	//商品采购明细表
	public function invpu_detail() {
	    $this->purview_model->checkpurview(22);
	    $data['stt']= str_enhtml($this->input->get_post('beginDate',TRUE));
		$data['ett']= str_enhtml($this->input->get_post('endDate',TRUE));
		$contactno  = $this->input->get_post('customerNo',TRUE);
		$goodsno    = $this->input->get_post('goodsNo',TRUE);
		$where = '';
		if ($data['stt']) {
			$where .= ' and a.billdate>="'.$data['stt'].'"';
		}
		if ($data['ett']) {
			$where .= ' and a.billdate<="'.$data['ett'].'"';
		}
		if ($contactno) {
			$where .= ' and a.contactname like "%'.$contactno.'%"';
		}
		if ($goodsno) {
			$where .= ' and a.goodsno in('.str_quote($goodsno).')';
		}
	    $data['list'] = $this->data_model->invpu_detail($where,'order by a.id desc');
		$this->load->view('report/invpu_detail',$data);	
	}
	
	//商品采购明细表导出
	public function invpu_detail_xls() {
	    $this->purview_model->checkpurview(23);
	    sys_xls('invpu_detail.xls');
	    $data['stt']= str_enhtml($this->input->get_post('beginDate',TRUE));
		$data['ett']= str_enhtml($this->input->get_post('endDate',TRUE));
		$contactno  = $this->input->get_post('customerNo',TRUE);
		$goodsno    = $this->input->get_post('goodsNo',TRUE);
		$where = '';
		if ($data['stt']) {
			$where .= ' and a.billdate>="'.$data['stt'].'"';
		}
		if ($data['ett']) {
			$where .= ' and a.billdate<="'.$data['ett'].'"';
		}
		if ($contactno) {
			$where .= ' and a.contactname like "%'.$contactno.'%"';
		}
		if ($goodsno) {
			$where .= ' and a.goodsno in('.str_quote($goodsno).')';
		}
		$this->data_model->logs('导出商品采购明细');
	    $data['list'] = $this->data_model->invpu_detail($where,'order by a.id desc');
		$this->load->view('report/invpu_detail_xls',$data);	
	}
	
	//采购汇总表（按商品）
	public function invpu_summary() {
	    $this->purview_model->checkpurview(25);
	    $data['stt']= str_enhtml($this->input->get_post('beginDate',TRUE));
		$data['ett']= str_enhtml($this->input->get_post('endDate',TRUE));
		$contactno  = $this->input->get_post('customerNo',TRUE);
		$goodsno    = $this->input->get_post('goodsNo',TRUE);
		$where = '';
		if ($data['stt']) {
			$where .= ' and a.billdate>="'.$data['stt'].'"';
		}
		if ($data['ett']) {
			$where .= ' and a.billdate<="'.$data['ett'].'"';
		}
		if ($contactno) {
			$where .= ' and a.contactname like "%'.$contactno.'%"';
		}
		if ($goodsno) {
			$where .= ' and a.goodsno in('.str_quote($goodsno).')';
		}
	    $data['list'] = $this->data_model->invpu_summary($where,'group by a.goodsid');
		$this->load->view('report/invpu_summary',$data);	
	}
	
	//采购汇总表（按商品）导出
	public function invpu_summary_xls() {
	    $this->purview_model->checkpurview(26);
	    sys_xls('invpu_summary.xls');
	    $data['stt'] = str_enhtml($this->input->get_post('beginDate',TRUE));
		$data['ett'] = str_enhtml($this->input->get_post('endDate',TRUE));
		$contactno  = $this->input->get_post('customerNo',TRUE);
		$goodsno    = $this->input->get_post('goodsNo',TRUE);
		$where       = '';
		if ($data['stt']) {
			$where .= ' and a.billdate>="'.$data['stt'].'"';
		}
		if ($data['ett']) {
			$where .= ' and a.billdate<="'.$data['ett'].'"';
		}
		if ($contactno) {
			$where .= ' and a.contactname like "%'.$contactno.'%"';
		}
		if ($goodsno) {
			$where .= ' and a.goodsno in('.str_quote($goodsno).')';
		}
		$this->data_model->logs('导出采购汇总表(按商品)');
	    $data['list'] = $this->data_model->invpu_summary($where,'group by a.goodsid');
		$this->load->view('report/invpu_summary_xls',$data);	
	}
	
	//采购汇总表（按供应商）
	public function invpu_supply() {
	    $this->purview_model->checkpurview(28);
	    $data['stt'] = str_enhtml($this->input->get_post('beginDate',TRUE));
		$data['ett'] = str_enhtml($this->input->get_post('endDate',TRUE));
		$contactno  = $this->input->get_post('customerNo',TRUE);
		$goodsno    = $this->input->get_post('goodsNo',TRUE);
		$where       = '';
		if ($data['stt']) {
			$where .= ' and a.billdate>="'.$data['stt'].'"';
		}
		if ($data['ett']) {
			$where .= ' and a.billdate<="'.$data['ett'].'"';
		}
		if ($contactno) {
			$where .= ' and a.contactname like "%'.$contactno.'%"';
		}
		if ($goodsno) {
			$where .= ' and a.goodsno in('.str_quote($goodsno).')';
		}
	    
	    $data['list'] = $this->data_model->invpu_supply($where,'order by a.invpuid desc');
		$this->load->view('report/invpu_supply',$data);	
	}
	
	//采购汇总表（按供应商）导出
	public function invpu_supply_xls() {
	    $this->purview_model->checkpurview(29);
	    sys_xls('invpu_supply.xls');
	    $data['stt'] = str_enhtml($this->input->get_post('beginDate',TRUE));
		$data['ett'] = str_enhtml($this->input->get_post('endDate',TRUE));
		$contactno  = $this->input->get_post('customerNo',TRUE);
		$goodsno    = $this->input->get_post('goodsNo',TRUE);
		$where = '';
		if ($data['stt']) {
			$where .= ' and a.billdate>="'.$data['stt'].'"';
		}
		if ($data['ett']) {
			$where .= ' and a.billdate<="'.$data['ett'].'"';
		}
		if ($contactno) {
			$where .= ' and a.contactname like "%'.$contactno.'%"';
		}
		if ($goodsno) {
			$where .= ' and a.goodsno in('.str_quote($goodsno).')';
		}
	    $this->data_model->logs('导出采购汇总表(按供应商)');
	    $data['list'] = $this->data_model->invpu_supply($where,'order by a.invpuid desc');
		$this->load->view('report/invpu_supply_xls',$data);	
	}
	
	//------------------------------------销售报表-------------------------------------------------------------
	//销售明细
	public function sales_detail() {
	    $this->purview_model->checkpurview(31);
	    $data['stt'] = str_enhtml($this->input->get_post('beginDate',TRUE));
		$data['ett'] = str_enhtml($this->input->get_post('endDate',TRUE));
		$contactno  = $this->input->get_post('customerNo',TRUE);
		$goodsno    = $this->input->get_post('goodsNo',TRUE);
		$where       = '';
		if ($data['stt']) {
			$where .= ' and a.billdate>="'.$data['stt'].'"';
		}
		if ($data['ett']) {
			$where .= ' and a.billdate<="'.$data['ett'].'"';
		}
		if ($contactno) {
			$where .= ' and a.contactname like "%'.$contactno.'%"';
		}
		if ($goodsno) {
			$where .= ' and a.goodsno in('.str_quote($goodsno).')';
		}
	    $data['list'] = $this->data_model->invsa_list($where,'order by id desc');
		$this->load->view('report/sales_detail',$data);	
	}
	
	////销售明细报表导出
	public function sales_detail_xls() {
	    $this->purview_model->checkpurview(32);
	    sys_xls('sales_detail.xls');
	    $data['stt'] = str_enhtml($this->input->get_post('beginDate',TRUE));
		$data['ett'] = str_enhtml($this->input->get_post('endDate',TRUE));
		$contactno  = $this->input->get_post('customerNo',TRUE);
		$goodsno    = $this->input->get_post('goodsNo',TRUE);
		$where       = '';
		if ($data['stt']) {
			$where .= ' and a.billdate>="'.$data['stt'].'"';
		}
		if ($data['ett']) {
			$where .= ' and a.billdate<="'.$data['ett'].'"';
		}
		if ($contactno) {
			$where .= ' and a.contactname like "%'.$contactno.'%"';
		}
		if ($goodsno) {
			$where .= ' and a.goodsno in('.str_quote($goodsno).')';
		}
		$this->data_model->logs('导出商品销售明细');
	    $data['list'] = $this->data_model->invsa_list($where,'order by id desc');
		$this->load->view('report/sales_detail_xls',$data);	
	}
	
	
	//销售汇总表（按商品）
	public function sales_summary() {
	    $this->purview_model->checkpurview(34);
	    $data['stt'] = str_enhtml($this->input->get_post('beginDate',TRUE));
		$data['ett'] = str_enhtml($this->input->get_post('endDate',TRUE));
		$contactno  = $this->input->get_post('customerNo',TRUE);
		$goodsno    = $this->input->get_post('goodsNo',TRUE);
		$where = '';
		if ($data['stt']) {
			$where .= ' and a.billdate>="'.$data['stt'].'"';
		}
		if ($data['ett']) {
			$where .= ' and a.billdate<="'.$data['ett'].'"';
		}
		if ($contactno) {
			$where .= ' and a.contactname like "%'.$contactno.'%"';
		}
		if ($goodsno) {
			$where .= ' and a.goodsno in('.str_quote($goodsno).')';
		}
	    $data['list'] = $this->data_model->invsa_summary($where,'group by a.goodsid');
		$this->load->view('report/sales_summary',$data);	
	}
	
	//销售汇总表导出（按商品）
	public function sales_summary_xls() {
	    $this->purview_model->checkpurview(35);
	    sys_xls('sales_summary.xls');
	    $data['stt'] = str_enhtml($this->input->get_post('beginDate',TRUE));
		$data['ett'] = str_enhtml($this->input->get_post('endDate',TRUE));
		$contactno  = $this->input->get_post('customerNo',TRUE);
		$goodsno    = $this->input->get_post('goodsNo',TRUE);
		$where       = '';
		if ($data['stt']) {
			$where .= ' and a.billdate>="'.$data['stt'].'"';
		}
		if ($data['ett']) {
			$where .= ' and a.billdate<="'.$data['ett'].'"';
		}
		if ($contactno) {
			$where .= ' and a.contactname like "%'.$contactno.'%"';
		}
		if ($goodsno) {
			$where .= ' and a.goodsno in('.str_quote($goodsno).')';
		}
		$this->data_model->logs('导出销售汇总表(按商品)');
	    $data['list'] = $this->data_model->invsa_summary($where,'group by a.goodsid');
		$this->load->view('report/sales_summary_xls',$data);	
	}
	
	//销售汇总表（按客户）
	public function sales_customer() {
	    $this->purview_model->checkpurview(37);
	    $data['stt'] = str_enhtml($this->input->get_post('beginDate',TRUE));
		$data['ett'] = str_enhtml($this->input->get_post('endDate',TRUE));
		$contactno  = $this->input->get_post('customerNo',TRUE);
		$goodsno    = $this->input->get_post('goodsNo',TRUE);
		$where = '';
		if ($data['stt']) {
			$where .= ' and a.billdate>="'.$data['stt'].'"';
		}
		if ($data['ett']) {
			$where .= ' and a.billdate<="'.$data['ett'].'"';
		}
		if ($contactno) {
			$where .= ' and a.contactname like "%'.$contactno.'%"';
		}
		if ($goodsno) {
			$where .= ' and a.goodsno in('.str_quote($goodsno).')';
		}
	    $data['list'] = $this->data_model->invsa_customer($where,'order by a.invsaid desc');
		$this->load->view('report/sales_customer',$data);	
	}
	
	//销售汇总表导出（按客户）
	public function sales_customer_xls() {
	    $this->purview_model->checkpurview(38);
	    sys_xls('sales_customer.xls');
	    $data['stt'] = str_enhtml($this->input->get_post('beginDate',TRUE));
		$data['ett'] = str_enhtml($this->input->get_post('endDate',TRUE));
		$contactno  = $this->input->get_post('customerNo',TRUE);
		$goodsno    = $this->input->get_post('goodsNo',TRUE);
		$where = '';
		if ($data['stt']) {
			$where .= ' and a.billdate>="'.$data['stt'].'"';
		}
		if ($data['ett']) {
			$where .= ' and a.billdate<="'.$data['ett'].'"';
		}
		if ($contactno) {
			$where .= ' and a.contactname like "%'.$contactno.'%"';
		}
		if ($goodsno) {
			$where .= ' and a.goodsno in('.str_quote($goodsno).')';
		}
		$this->data_model->logs('导出销售汇总表(按客户)');
	    $data['list'] = $this->data_model->invsa_customer($where,'order by a.invsaid desc');
		$this->load->view('report/sales_customer_xls',$data);	
	}
	
	//------------------------------------仓库报表-------------------------------------------------------------
	//商品库存余额表
	public function goods_balance() {
	    $this->purview_model->checkpurview(40);
		$categoryid  = intval($this->input->get_post('categoryId',TRUE));
		$goodsno     = $this->input->get_post('goodsNo',TRUE);
		$data['stt'] = str_enhtml($this->input->get_post('beginDate',TRUE));
		$data['ett'] = str_enhtml($this->input->get_post('endDate',TRUE));
		$where = '';
		$order = 'order by a.id desc';
		if ($categoryid > 0) {
		    $cid = $this->cache_model->load_data(CATEGORY,'(1=1) and find_in_set('.$categoryid.',path)','id'); 
			if (count($cid)>0) {
			    $cid = join(',',$cid);
			    $where .= ' and a.categoryid in('.$cid.')';
			} 
		}
		if ($goodsno)  $where .= ' and a.number in('.str_quote($goodsno).')';      
		$data['list'] = $this->data_model->inventory($where,$order); 
		$this->load->view('report/goods_balance',$data);	
	}
	
	
	//导出商品库存余额表
	public function goods_balance_xls() {
	    $this->purview_model->checkpurview(41);
	    sys_xls('goods_balance.xls');
	    $categoryid  = intval($this->input->get_post('categoryId',TRUE));
		$goodsno     = $this->input->get_post('goodsNo',TRUE);
		$data['stt'] = str_enhtml($this->input->get_post('beginDate',TRUE));
		$data['ett'] = str_enhtml($this->input->get_post('endDate',TRUE));
		$where = '';
		$order = 'order by a.id desc';
		if ($categoryid > 0) {
		    $cid = $this->cache_model->load_data(CATEGORY,'(1=1) and find_in_set('.$categoryid.',path)','id'); 
			if (count($cid)>0) {
			    $cid = join(',',$cid);
			    $where .= ' and a.categoryid in('.$cid.')';
			} 
		}
		if ($goodsno)  $where .= ' and a.number in("'.str_quote($goodsno).'")';      
		$this->data_model->logs('导出商品库存余额表');    
		$data['list'] = $this->data_model->inventory($where,$order);  
		$this->load->view('report/goods_balance_xls',$data);	
	}
	
    //商品收发明细表
	public function goods_detail() {
	    $this->purview_model->checkpurview(43);
	    $data['stt'] = str_enhtml($this->input->get('beginDate',TRUE));
		$data['ett'] = str_enhtml($this->input->get('endDate',TRUE));
		$contactno   = str_enhtml($this->input->get('customerNo',TRUE));
		$goodsno     = str_enhtml($this->input->get('goodsNo',TRUE));
		$where = '';
		if ($data['stt']) {
			$where .= ' and a.billdate>="'.$data['stt'].'"';
		}
		if ($data['ett']) {
			$where .= ' and a.billdate<="'.$data['ett'].'"';
		}
		if ($contactno) {
			$where .= ' and a.contactname like "%'.$contactno.'%"';
		}
		if ($goodsno) {
			$where .= ' and a.goodsno in('.str_quote($goodsno).')';
		}
	    $data['list'] = $this->data_model->invsa_customer($where,'order by a.invsaid desc');
		$this->load->view('report/goods_detail',$data);	
	}
	
	
	//商品收发汇总表
	public function goods_summary() {
	    $this->purview_model->checkpurview(46);
	    $data['stt'] = str_enhtml($this->input->get_post('beginDate',TRUE));
		$data['ett'] = str_enhtml($this->input->get_post('endDate',TRUE));
		$goodsno     = $this->input->get_post('goodsNo',TRUE);
		$where1 = '';
		$where2 = '';
		if ($data['stt']) {
			$where1 .= ' and billdate>="'.$data['stt'].'"';
		}
		if ($data['ett']) {
			$where1 .= ' and billdate<="'.$data['ett'].'"';
		}
		if ($goodsno) {
			$where2 .= ' and a.number in('.str_quote($goodsno).')';
		}
	    $data['list'] = $this->data_model->goods_summary($where1,$where2,'group by a.id');
		$this->load->view('report/goods_summary',$data);	
	}
	
	
	//商品收发汇总表导出
	public function goods_summary_xls() {
	    $this->purview_model->checkpurview(46);
		sys_xls('goods_summary.xls');
	    $data['stt'] = str_enhtml($this->input->get_post('beginDate',TRUE));
		$data['ett'] = str_enhtml($this->input->get_post('endDate',TRUE));
		$goodsno     = $this->input->get_post('goodsNo',TRUE);
		$where1 = '';
		$where2 = '';
		if ($data['stt']) {
			$where1 .= ' and billdate>="'.$data['stt'].'"';
		}
		if ($data['ett']) {
			$where1 .= ' and billdate<="'.$data['ett'].'"';
		}
		if ($goodsno) {
			$where2 .= ' and a.number in('.str_quote($goodsno).')';
		}
	    $data['list'] = $this->data_model->goods_summary($where1,$where2,'group by a.id');
		//print_r($this->db->last_query());
		$this->load->view('report/goods_summary_xls',$data);	
	}
	

    //往来单位欠款表
	public function arrears() {
	    $this->purview_model->checkpurview(49);
	    $data['stt'] = str_enhtml($this->input->get_post('beginDate',TRUE));
		$data['ett'] = str_enhtml($this->input->get_post('endDate',TRUE));
		$where = '';
		if ($data['stt']) {
			$where .= ' and billdate>="'.$data['stt'].'"';
		}
		if ($data['ett']) {
			$where .= ' and billdate<="'.$data['ett'].'"';
		}
		$data['list1'] = $this->data_model->customer_arrears($where,' order by a.id');
		$data['list2'] = $this->data_model->vendor_arrears($where,' order by a.id');
		$this->load->view('report/arrears',$data);	
	}
	
	//往来单位欠款表导出
	public function arrears_xls() {
	    $this->purview_model->checkpurview(50);
	    sys_xls('arrears.xls');
	    $data['stt'] = str_enhtml($this->input->get_post('beginDate',TRUE));
		$data['ett'] = str_enhtml($this->input->get_post('endDate',TRUE));
		$where = '';
		if ($data['stt']) {
			$where .= ' and billdate>="'.$data['stt'].'"';
		}
		if ($data['ett']) {
			$where .= ' and billdate<="'.$data['ett'].'"';
		}
		$data['list1'] = $this->data_model->customer_arrears($where,' order by a.id');
		$data['list2'] = $this->data_model->vendor_arrears($where,' order by a.id');
		$this->load->view('report/arrears_xls',$data);	
	}
	
	
	
	//应付账款明细表
	public function balance_supply() {
	    $this->purview_model->checkpurview(52);
	    $data['stt'] = str_enhtml($this->input->get_post('beginDate',TRUE));
		$data['ett'] = str_enhtml($this->input->get_post('endDate',TRUE));
		$accountno   = str_enhtml($this->input->get_post('accountNo',TRUE));
		$where1 = '';
		$where2 = '';
		if ($accountno) {
			$where1 .= ' and number in('.str_quote($accountno).')';
		}
		if ($data['stt']) {
			$where2 .= ' and billdate>="'.$data['stt'].'"';
		}
		if ($data['ett']) {
			$where2 .= ' and billdate<="'.$data['ett'].'"';
		}
	
		$data['list1'] = $this->cache_model->load_data(CONTACT,'(status=1) and type=2 '.$where1.' order by id');
		$data['list2'] = $this->cache_model->load_data(INVPU,'(1=1) '.$where2.' order by id');
		$this->load->view('report/balance_supply',$data);	
	}
	
	//应付账款明细表
	public function balance_supply_xls() {
	    $this->purview_model->checkpurview(53);
	    sys_xls('balance_supply.xls');
	    $data['stt'] = str_enhtml($this->input->get_post('beginDate',TRUE));
		$data['ett'] = str_enhtml($this->input->get_post('endDate',TRUE));
		$accountno   = str_enhtml($this->input->get_post('accountNo',TRUE));
		$where1 = '';
		$where2 = '';
		if ($accountno) {
			$where1 .= ' and number in("'.str_quote($accountno).'")';
		}
		if ($data['stt']) {
			$where2 .= ' and billdate>="'.$data['stt'].'"';
		}
		if ($data['ett']) {
			$where2 .= ' and billdate<="'.$data['ett'].'"';
		}
	
		$data['list1'] = $this->cache_model->load_data(CONTACT,'(status=1) and type=2 '.$where1.' order by id');
		$data['list2'] = $this->cache_model->load_data(INVPU,'(1=1) '.$where2.' order by id');
		$this->load->view('report/balance_supply_xls',$data);	
	}
	
	//应收账款明细表
	public function balance_detail() {
	    $this->purview_model->checkpurview(55);
	    $data['stt'] = str_enhtml($this->input->get_post('beginDate',TRUE));
		$data['ett'] = str_enhtml($this->input->get_post('endDate',TRUE));
		$accountno   = str_enhtml($this->input->get_post('accountNo',TRUE));
		$where1 = '';
		$where2 = '';
		if ($accountno) {
			$where1 .= ' and number in('.str_quote($accountno).')';
		}
		if ($data['stt']) {
			$where2 .= ' and billdate>="'.$data['stt'].'"';
		}
		if ($data['ett']) {
			$where2 .= ' and billdate<="'.$data['ett'].'"';
		}
		$data['list1'] = $this->cache_model->load_data(CONTACT,'(status=1) and type=1 '.$where1.' order by id');
		$data['list2'] = $this->cache_model->load_data(INVSA,'(1=1) '.$where2.' order by id');
		$this->load->view('report/balance_detail',$data);	
	}

    //应收账款明细表
	public function balance_detail_xls() {
	    $this->purview_model->checkpurview(56);
		sys_xls('balance_detail.xls');
	    $data['stt'] = str_enhtml($this->input->get_post('beginDate',TRUE));
		$data['ett'] = str_enhtml($this->input->get_post('endDate',TRUE));
		$accountno   = str_enhtml($this->input->get_post('accountNo',TRUE));
		$where1 = '';
		$where2 = '';
		if ($accountno) {
			$where1 .= ' and number in('.str_quote($accountno).')';
		}
		if ($data['stt']) {
			$where2 .= ' and billdate>="'.$data['stt'].'"';
		}
		if ($data['ett']) {
			$where2 .= ' and billdate<="'.$data['ett'].'"';
		}
		$data['list1'] = $this->cache_model->load_data(CONTACT,'(status=1) and type=1 '.$where1.' order by id');
		$data['list2'] = $this->cache_model->load_data(INVSA,'(1=1) '.$where2.' order by id');
		$this->load->view('report/balance_detail_xls',$data);	
	}

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */