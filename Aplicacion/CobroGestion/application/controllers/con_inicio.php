<?php
    class con_inicio extends CI_Controller {
        public function index($error_login=false)
        {
           
            
            
             if($this->session->userdata("login")){
                $this->load->view('view_inicio');
              }else{
                redirect('con_login', 'refresh');                
             }            
        }
        public function logeo(){
            $this->load->view('view_usuario',$nombre);
        }
    }
    
?>