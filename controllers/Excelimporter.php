<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

ini_set('error_reporting', E_STRICT);

require_once APPPATH . "/third_party/PHPExcel.php";

class Excel extends PHPExcel {

    public function __construct() {

        parent::__construct();
    }

}

/**
 * Excelimporter
 * @access public
 * @Description excel import file in db
 * @date 29/09/2017
 * @author Pramod jaiswal
 */
class Excelimporter extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('Common_model');
        $this->load->language('web', $language);
        $this->load->library('session');
        // Load form helper library
        $this->load->helper('form');
        // Load form validation library
        $this->load->library('form_validation');
        if (empty($this->session->userdata['logged_in']) && ($this->session->userdata['logged_in'] == '')) {
            redirect(base_url() . 'Admin/Index');
        }
    }

    /**
     * index
     * Import data in db one by one 
     */
    public function index() {
        //If form submitted
        $post = $this->input->post();
        // check value of form
        if ($_FILES) {
            if (isset($_FILES['excelname']['name']) AND ! empty($_FILES['excelname']['tmp_name'])) {
                if ($_FILES['excelname']['type'] == 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet') {
                    $xlsName = $_FILES['excelname']['tmp_name'];
                    list($name, $ext) = explode(".", $_FILES['excelname']['name']);
                    $filename = time() . rand() . '_' . $_FILES['excelname']['name'];
                    $path1 = 'public/doc/';
                    $fullPath1 = $path1 . $filename;
                    try {
                        $a = move_uploaded_file($xlsName, $fullPath1);
                    } catch (Exception $e) {
                        echo '<pre>';
                        print_r($e);
                        exit;
                    }
                }
                /* Excel Importer Code Starts here */
                $this->load->library('Excel');
                try {
                    //it will be your file name that you are posting with a form or can pass static name $_FILES["file"]["name"];
                    $objPHPExcel = PHPExcel_IOFactory::load('public/doc/' . $filename);
                } catch (Exception $e) {
                    $this->resp->success = FALSE;
                    $this->resp->msg = 'Error Uploading file';
                    echo json_encode($this->resp);
                    exit;
                }

                $allDataInSheet = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);

                foreach ($allDataInSheet as $import) {


                    if (!empty($import['A']) and ! empty($import['B'])) {

                        //getting Demo Details 
                        $demodetails = $this->Common_model->fetch_data('tm_demo_import', 'demo_id', array('where' => array('demo_name' => trim($import['B']), 'demo_import_id' => trim($import['A']))), true);
                        if ($demodetails) {
                            $demo_id = $demodetails['demo_id'];
                        } else {
                            //Add this Demo data to DB
                            $insertArray = array(
                                'demo_import_id' => trim($import['A']),
                                'demo_name' => trim($import['B']),
                                'created_date' => $this->datetime,
                            );
                            $demo_id = $this->Common_model->insert_single('tm_demo_import', $insertArray);
                        }
                    } elseif (empty($import['A']) or empty($import['B'])) {
                        $this->session->set_flashdata('message', $this->lang->line('error_prefix') . 'Invalid Excel Format! Please Donwload and check sample format.' . $this->lang->line('error_suffix'));
                        redirect(base_url() . 'Admin/excelimporter', 'refresh');
                    }
                }
                $this->session->set_flashdata('message', $this->lang->line('success_prefix') . 'Excel Successfully Import.' . $this->lang->line('success_suffix'));
                redirect(base_url() . 'Admin/excelimporter', 'refresh');
                /* Excel Importer Code Ends here */
            }
        }
        $this->Common_model->load_views('excelimporter/index', $data);
    }

}
