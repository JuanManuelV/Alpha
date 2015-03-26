<?php
    class Login extends CI_Controller{
        
        public function __construct(){
            parent::__construct();
            $this->load->helper('form');
            //$this->load->model('AdministradorMOD/SistemaMOD');
        }
        
        public function index(){
            //ABRE LA VISTA DEL ADMINISTRADOR Y LE PASA LOS DATOS
            $this->load->view('LoginAdmin');
        }
    }
?>       