 <?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Mysql_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function db_sql($sql, $type = '1') {
        $query = $this->db->query($sql);
        if ($type == 1)
            return $query->row_array();
        if ($type == 2)
            return $query->result_array();
        if ($type == 3)
            return $query->num_rows();
        return array();
    }

    public function db_select($table, $where = '', $field = '*') {
        if ($where) {
            $this->db->where($where);
        }
        $this->db->select($field);
        $query = $this->db->get($table);
        $result = $query->result_array();
        if ($field != '*') {
            $data = array();
            foreach ($result as $arr => $row) {
                $data[] = @$row[$field];
            }
            return $data;
        } else {if($table == BOM_STOCK){}
            return $result;
        }
    }

    public function db_one($table, $where = '', $field = '*') {
        if (!$where)
            return false;
        if (isset($where)) {
            $this->db->where($where);
        }
        $this->db->select($field);
        $query = $this->db->get($table);
        $result = $query->row_array();
        if ($field != '*') {
            return @$result[$field];
        } else {
            return $result;
        }
    }

    public function db_sum($table, $where = '', $field = array('id', 'hits')) {
        if (!is_array($field))
            return false;
        foreach ($field as $arr) {
            $this->db->select_sum($arr);
        }
        if ($where) {
            $this->db->where($where);
        }
        $query = $this->db->get($table);
        return $query->row_array();
    }

    public function db_count($table, $where = '') {
        if ($where) {
            $this->db->where($where);
        }
        return $this->db->count_all_results($table);
    }

    public function db_inst($table, $data) {
        if (!$data)
            return false;
        if (isset($data[0]) && is_array($data[0])) {
            if ($this->db->insert_batch($table, $data)) {
                return $this->db->insert_id();
            }
        } else {
            if ($this->db->insert($table, $data)) {
                return $this->db->insert_id();
            }
        }
        return false;
    }

    public function db_upd($table, $data, $where = '') {
        if (!$data)
            return false;
        if (isset($data[0]) && is_array($data[0])) {
            $this->db->update_batch($table, $data, $where);
            if ($this->db->affected_rows()) {
                return true;
            }
        } else {
            if (is_array($data)) {
                if ($where) {
                    $this->db->where($where);
                }
                return $this->db->update($table, $data);
            } else {
                if ($where) {
                    $where = ' WHERE ' . $where;
                }
                $sql = 'UPDATE ' . $table . ' SET ' . $data . $where;
                return $this->db->query($sql);
            }
        }
        return true;
    }

    public function db_del($table, $where = '') {
        if ($where) {
            $this->db->where($where);
        }
        $this->db->delete($table);
        if ($this->db->affected_rows()) {
            return true;
        } else {
            return false;
        }
    }


}

?>