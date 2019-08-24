<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Cache_model extends CI_Model{

	public function __construct(){
  		parent::__construct();
		$this->path = $this->config->item('cache_path');
		$this->load->driver('cache', array('adapter' => 'file'));
	}
	
	public function load_sql($table,$sql,$type=1) {//var_dump($sql);exit;
	    $name = $table.'/'.md5($sql.$type);
		dir_add($this->path.$table);
		$data = $this->cache->get($name);
		if (!$data) {
			$data = $this->mysql_model->db_sql($sql,$type);;
			$this->cache->save($name,$data,3600000);
		}
		return $data;
	}
	
	public function load_one($table,$where,$filed='*') {
	    $name = $table.'/'.md5('one'.$table.$where.$filed);
		dir_add($this->path.$table);
		$data = $this->cache->get($name);
		if (!$data) {
			$data = $this->mysql_model->db_one($table,$where,$filed);
			$this->cache->save($name,$data,3600000);
		}
		return $data;
	}

	public function load_data($table,$where,$filed='*'){
	    $name = $table.'/'.md5($table.$where.$filed);
		dir_add($this->path.$table);
		$data = $this->cache->get($name);
		if (!$data) {
			$data = $this->mysql_model->db_select($table,$where,$filed);
			$this->cache->save($name,$data,3600000);
		}
		return $data;
	}
	
	public function load_sum($table,$where,$filed=array('id','hits')) {
	    $name = $table.'/'.md5('sum'.$table.$where.$filed);
		dir_add($this->path.$table);
		$data = $this->cache->get($name);
		if (!$data) {
			$data = $this->mysql_model->db_sum($table,$where,$filed);
			$this->cache->save($name,$data,3600000);
		}
		return $data;
	}
	
	public function load_total($table,$where='') {
	    $name = $table.'/'.md5('total'.$table.$where);
		dir_add($this->path.$table);
		$data = $this->cache->get($name);
		if (!$data) {
			$data = $this->mysql_model->db_count($table,$where);
			$this->cache->save($name,$data,3600000);
		}
		return $data;
	}
	
	
	public function load_category($table,$where) {
		$name = $table.'/'.md5('load_category'.$table.$where);
		dir_add($this->path.$table);
		$data = $this->cache->get($name);
		if (!$data) {
			$data = $info = $this->load_one($table,$where);
			if (is_array($data)&&count($data)>0) {
				if ($data['pid']==0) {
					$data['top'] = $info;
				} else {
					$path = explode(',',$data['path']);
					$data['top'] = $this->load_one($table,'(id='.$path[0].')');
				}
				$cid = $this->load_data($table,'(mode='.$data['mode'].') and find_in_set('.$data['id'].',path)','id');
				if (is_array($cid)&&count($cid)>0) {
					$data['cid'] = join(',',$cid);
				} else {
					$data['cid'] = $data['id'];
				}
				$this->cache->save($name,$data,3600000);
			}
		}
		return $data;
	}
	
	public function load_location($category,$code='>') {
	    $name = CATEGORY.'/'.$category['id'].md5(CATEGORY.$category['id'].$code);
		dir_add($this->path.CATEGORY);
		$data = $this->cache->get($name);
		if (!$data) {
			$list = $this->load_data(CATEGORY,'(id in('.$category['path'].'))');
			$data = '�����ڵ�λ�ã� <a href="'.base_url().'">��ҳ</a>';
			foreach ($list as $arr=>$row) {
				$data .= $code.'<a href="'.web_url($row['dir']).'">'.$row['title'].'</a>';
			}
			$this->cache->save($name,$data,3600000);
		}
		return $data;
	}
	
	public function info() {
		return $this->cache->cache_info();
	}
	
	public function delete($key) {
		return $this->cache->delete($key);
	}
	
	public function clean() {
		return $this->cache->clean();
	}
	
	public function delsome($key) {
	    if (is_dir($this->path.$key)) {
		    delete_files($this->path.$key);
		} else {
		    $data = $this->info();
			foreach ($data as $arr=>$row) {
				if ($key == substr($arr,0,strlen($key))) {
					$this->delete($arr);	
				}
			} 	
		}
	}

}