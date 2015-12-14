<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Model_generator extends CI_Controller
{
	public function __construct() {
		parent::__construct();
        $this->load->helper('url');
	}
	
    public function index()
    {
		$db_name = $this->db->database;
		$result = $this->db->query('SELECT TABLE_NAME AS TABLES FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA="'.$db_name.'";');
		$result = $result->result_array();
		$data['result'] = $result;
        $this->load->view('v_model_generator', $data);
    }

    public function create_model()
    {
        $table_name = $this->input->post('table_name');
        if ($this->db->table_exists($table_name)) {
            $result = $this->db->query('DESC ' . $table_name);
            $result = $result->result_array();
            $primary_key = "";
            foreach ($result as $row) {
                if($row['Key'] == "PRI"){
                    $primary_key = $row['Field'];
                }
            }
			if($primary_key == ""){
				$primary_key = $result[0]['Field'];
			}
            $stuff = '<?php

		/*
		* Created By: Waqas Shahid
		* Developer Email: waqas-shahid@live.com
		* Created On: ' . date("D - F j, Y") . '
		*/
		
		/*
		* Some useful return methods:
		* return $query->row_array();
		* return $query->result_array();
		* echo $last_query = $this->db->last_query();
		*/
		
		class ' . ucwords($table_name) . '_model extends CI_Model {
		';

            foreach ($result as $row) {
                $stuff .= 'var $' . $row['Field'] . '; ';
            }

            $stuff .= '
		
		static $table = "' . $table_name . '";
		
		function __construct() {
        parent::__construct();
		}
		
		function insert(';
            $numItems = count($result);
            $i = 0;

            foreach ($result as $row) {
                if($row['Key'] == "PRI" && $row['Extra'] == "auto_increment"){
                    if (++$i === $numItems) {

                    } else {

                    }
                } else {
                    $stuff .= '$' . $row['Field'];
                    if (++$i === $numItems) {
                        $stuff .= ' '; //Last Index
                    } else {
                        $stuff .= ', ';
                    }
                }
            }

            $stuff .= ') {
		';
            $i = 0;
            foreach ($result as $row) {
                if($row['Key'] == "PRI" && $row['Extra'] == "auto_increment"){

                } else{
                $stuff .= '$this->' . $row['Field'] . '  = $' . $row['Field'] . ';
			';
                }
            }
            $stuff .= '
		$this->db->insert(self::$table, $this);
		return $this->db->insert_id();
		}
		
		function update(';
            foreach ($result as $row) {
                $stuff .= '$' . $row['Field'];
                if (++$i === $numItems) {
                    $stuff .= ' '; //Last Index
                } else {
                    $stuff .= ', ';
                }
            }
            $i = 0;
            $stuff .= ') {
		$data = array(
		';

            foreach ($result as $row) {
                if($row['Key'] == "PRI"){
                    if (++$i === $numItems) {

                    } else {

                    }
                } else {
                    $stuff .= "'" . $row['Field'] . "' => $" . $row['Field'];
                    if (++$i === $numItems) {
                        $stuff .= "
                    "; //Last Index
                    } else {
                        $stuff .= ",
                    ";
                    }
                }
            }

            $stuff .= ');
		
		$this->db->where("' . $primary_key . '", $' . $primary_key . ');
		return $this->db->update(self::$table, $data);
		}
		
		function delete($' . $primary_key . ')
		{
	    $this->db->where("' . $primary_key . '", $' . $primary_key . ');
		return $this->db->delete(self::$table);
		}

		function get_by_id($' . $primary_key . ') {
        $query = $this->db->get_where(self::$table, array("' . $primary_key . '" => $' . $primary_key . '));
		return $query->row_array();
		}

		function get_all(){
		return $this->db->get(self::$table)->result_array();
		}

		/*
		function dummy_function(){
			$result = $this->db->query("SELECT * FROM ".self::$table.";");
			return $result->result_array();
		}
		*/';

            $stuff .= '
		}
		?>';
            $path_to_model = realpath(dirname(__FILE__) . '/../models');
            $content = fopen($path_to_model . "/" . $table_name . "_model.php", "w");

            fputs($content, $stuff);

            fclose($content);
			redirect(base_url('model_generator/?success=1&table='.$table_name));
        } else {
			redirect(base_url('model_generator/?error=1'));
        }
    }
	
	public function create_all_models()
    {
		$db_name = $this->db->database;
		$result = $this->db->query('SELECT TABLE_NAME AS TABLES FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA="'.$db_name.'";');
		$result = $result->result_array();
		foreach($result as $row){
			$table_name = $row['TABLES'];
			if ($this->db->table_exists($table_name)) {
				$result = $this->db->query('DESC ' . $table_name);
				$result = $result->result_array();
				$primary_key = "";
				foreach ($result as $row) {
					if($row['Key'] == "PRI"){
						$primary_key = $row['Field'];
					}
				}
				if($primary_key == ""){
					$primary_key = $result[0]['Field'];
				}
				$stuff = '<?php

			/*
			* Created By: Waqas Shahid
			* Developer Email: waqas-shahid@live.com
			* Created On: ' . date("D - F j, Y") . '
			*/
			
			/*
			* Some useful return methods:
			* return $query->row_array();
			* return $query->result_array();
			* echo $last_query = $this->db->last_query();
			*/
			
			class ' . ucwords($table_name) . '_model extends CI_Model {
			';

				foreach ($result as $row) {
					$stuff .= 'var $' . $row['Field'] . '; ';
				}

				$stuff .= '
			
			static $table = "' . $table_name . '";
			
			function __construct() {
			parent::__construct();
			}
			
			function insert(';
				$numItems = count($result);
				$i = 0;

				foreach ($result as $row) {
					if($row['Key'] == "PRI" && $row['Extra'] == "auto_increment"){
						if (++$i === $numItems) {

						} else {

						}
					} else {
						$stuff .= '$' . $row['Field'];
						if (++$i === $numItems) {
							$stuff .= ' '; //Last Index
						} else {
							$stuff .= ', ';
						}
					}
				}

				$stuff .= ') {
			';
				$i = 0;
				foreach ($result as $row) {
					if($row['Key'] == "PRI" && $row['Extra'] == "auto_increment"){

					} else{
					$stuff .= '$this->' . $row['Field'] . '  = $' . $row['Field'] . ';
				';
					}
				}
				$stuff .= '
			$this->db->insert(self::$table, $this);
			return $this->db->insert_id();
			}
			
			function update(';
				foreach ($result as $row) {
					$stuff .= '$' . $row['Field'];
					if (++$i === $numItems) {
						$stuff .= ' '; //Last Index
					} else {
						$stuff .= ', ';
					}
				}
				$i = 0;
				$stuff .= ') {
			$data = array(
			';

				foreach ($result as $row) {
					if($row['Key'] == "PRI"){
						if (++$i === $numItems) {

						} else {

						}
					} else {
						$stuff .= "'" . $row['Field'] . "' => $" . $row['Field'];
						if (++$i === $numItems) {
							$stuff .= "
						"; //Last Index
						} else {
							$stuff .= ",
						";
						}
					}
				}

				$stuff .= ');
			
			$this->db->where("' . $primary_key . '", $' . $primary_key . ');
			return $this->db->update(self::$table, $data);
			}
			
			function delete($' . $primary_key . ')
			{
			$this->db->where("' . $primary_key . '", $' . $primary_key . ');
			return $this->db->delete(self::$table);
			}

			function get_by_id($' . $primary_key . ') {
			$query = $this->db->get_where(self::$table, array("' . $primary_key . '" => $' . $primary_key . '));
			return $query->row_array();
			}

			function get_all(){
			return $this->db->get(self::$table)->result_array();
			}

			/*
			function dummy_function(){
				$result = $this->db->query("SELECT * FROM ".self::$table.";");
				return $result->result_array();
			}
			*/';

				$stuff .= '
			}
			?>';
				$path_to_model = realpath(dirname(__FILE__) . '/../models');
				$content = fopen($path_to_model . "/" . $table_name . "_model.php", "w");

				fputs($content, $stuff);

				fclose($content);
				//redirect(base_url('model_generator/?success=1&table='.$table_name));
			} else {
				
			}
		}
		redirect(base_url('model_generator/?success_all=1'));
	}

}

/* End of file model_generator.php */
/* Location: ./application/controllers/model_generator.php */