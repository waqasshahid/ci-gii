<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Controller_generator extends CI_Controller
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
        $this->load->view('v_controller_generator', $data);
    }

    public function create_controller()
    {
        $controller_name = $this->input->post('ctrl_name');
        $table_name = $this->input->post('table_name');
        $model_name = $this->input->post('model_name');
        if($model_name == ""){
            $model_name = $table_name."_model";
        }

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
            $stuff = "<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

		/*
		* Created By: Waqas Shahid
		* Developer Email: waqas-shahid@live.com
		* Created On: " . date('D - F j, Y') . "
		*/

		class " . ucwords($controller_name) . " extends CI_Controller {
		";

            $stuff .= 'public function __construct() {
        parent::__construct();
        $this->load->model("'.$model_name.'");
		}

		public function index(){
		$this->load->view("/welcome_message.php");
		}

		/*
		public function add() {
		    ';
            $numItems = count($result);
            $i = 0;
            foreach ($result as $row) {
                if($row['Key'] == "PRI" && $row['Extra'] == "auto_increment"){

                } else{
                $stuff .= '$' . $row['Field'] . ' = $this->input->post("' . $row['Field'] . '");
			';
                }
            }
            $i = 0;
            $stuff .= '
		    $query_result = $this->'.$model_name.'->insert(';
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
            $stuff .=');
            if($query_result)
		    {
			    redirect(base_url("'.$controller_name.'/?success=1"));
		    }
		    redirect(base_url("'.$controller_name.'/?error=1"));
		}
		
		public function update() {
            ';

            foreach ($result as $row) {
            $stuff .= '$' . $row['Field'] . ' = $this->input->post("' . $row['Field'] . '");
			';
            }

            $stuff .= '
		    $query_result = $this->'.$model_name.'->update(';
            $i = 0;
            foreach ($result as $row) {
                $stuff .= '$' . $row['Field'];
                if (++$i === $numItems) {
                    $stuff .= ' '; //Last Index
                } else {
                    $stuff .= ', ';
                }
            }
            $stuff .=');
            if($query_result)
		    {
			    redirect(base_url("'.$controller_name.'/?success=1"));
		    }
		    redirect(base_url("'.$controller_name.'/?error=1"));
		}
		
		public function delete($' . $primary_key . ')
		{
            $query_result = $this->'.$model_name.'->delete($' . $primary_key . ');
            if($query_result)
            {
                redirect(base_url("'.$controller_name.'"));
            }
            redirect(base_url("'.$controller_name.'"));
		}

		public function get_by_id($' . $primary_key . ') {
            $result = $this->'.$model_name.'->get_by_id($' . $primary_key . ');
		    $data["result"]= $result;
            $this->load->view("/welcome_message.php",$data);
		}

		public function get_all(){
		    $result = $this->'.$model_name.'->get_all();
		    $data["result"]= $result;
            $this->load->view("/welcome_message.php",$data);
		}
		*/';

            $stuff .= '
		}
		?>';
            $path_to_model = realpath(dirname(__FILE__) . '/../controllers');
            $content = fopen($path_to_model . "/" . $controller_name . ".php", "w");

            fputs($content, $stuff);

            fclose($content);
			redirect(base_url('controller_generator/?success=1&controller='.$controller_name));
        } else {
			redirect(base_url('controller_generator/?error=1'));
        }
    }

}

/* End of file model_generator.php */
/* Location: ./application/controllers/model_generator.php */