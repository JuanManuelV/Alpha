<?php
    class con_login extends CI_Controller{
        
        public function __construct(){
            parent::__construct();
            $this->load->helper('form');
            $this->load->helper('array');
            $this->load->library('form_validation');
            $this->load->model('mod_login');
            
        }
        
        public function index($error_login=false){
            
            $EmpresaCliente = array(
                '-1'=>'- Seleccione su empresa -',
            );
                    
            $data['EmpresaCliente']=$EmpresaCliente;    
            
            if ($error_login){
                $data['mensaje']="Error en el Login";
                $this->load->view('view_login',$data);
            }else{
                $this->load->view("view_login",$data);
            }
        } 
        
        public function process_login(){
            
            $this->form_validation->set_rules('nombre', 'nombre', 'trim|required|xss_clean');
            $this->form_validation->set_rules('clave', 'clave', 'trim|required|xss_clean|callback_check_database');
            
            if($this->form_validation->run() == FALSE){
	            $this->load->view('view_login');
	        }else{
	           $result = $this->mod_login->login( $this->input->post("nombre"), $this->input->post("clave"));
    	       if($result){
                    $sess_array = array(
                        'codigo'=>$result->codigo,
                        'nombre' => $result->Usuario,
                        'tipo'   => $result->Tipo,
                        'login' => true
                    );
                                
                    $this->session->set_userdata($sess_array);
                           
                           if($result->Tipo == '1'){
                                redirect("administradorCON/con_administrador");
                           }
                           if($result->Tipo == '2'){
                                redirect("empresaCobranzaCON/EmpresaCobranzaCON");
                           }                   
    	       }else{
                   redirect("con_login/index/true");
    	       }
            }
        }
        
        public function LogearUsuario(){
            
            $this->form_validation->set_rules('txtNomOperador', 'txtNomOperador', 'trim|required|xss_clean');
            $this->form_validation->set_rules('txtClaveOperador', 'txtClaveOperador', 'trim|required|xss_clean|callback_check_database');
            
            if($this->form_validation->run() == FALSE){
	            $this->load->view('view_login');
	        }else{
	           $result = $this->mod_login->ValidarLogin( $this->input->post("txtNomOperador"), $this->input->post("txtClaveOperador"),$this->input->post("EmpresaCliente"));
    	       if($result){
                    $sess_array = array(
                        'codigo'=>$result->codigo,
                        'nombre' => $result->txtNomOperador,
                        'tipoOperador'=> $result->seqTipoOperador,
                        'TipoCliente'=>$result->seqTipoCliente,
                        'EstadoCliente'=>$result->seqEstadoCliente,
                        'seqCliente'=>$result->seqCliente,
                        'login' => true
                    );
                                
                    $this->session->set_userdata($sess_array);
                    
                   // if($result->txtUsuOperador=="cobrogestion"){
//                        redirect("administradorCON/con_administrador");
//                    }
                    
                    if($result->seqEstadoCliente=='1'){
                        if($result->seqTipoCliente=='1'){
                            redirect("con_usuariovalido");
                        }
                        if($result->seqTipoCliente=='2'){
                            redirect("empresaCobranzaCON/EmpresaCobranzaCON");
                        } 
                       // if($result->seqTipoCliente=='0'){
//                            redirect("administradorCON/con_administrador");
//                        }           
                       
        	       }else{
                       redirect("con_login/index/true");
        	       }
                }else{
                    redirect("con_login/index/true");
                            
                }
            }   
        }
        
          public function LogearAdmin(){
            
            //$this->form_validation->set_rules('txtNomOperador', 'txtNomOperador', 'trim|required|xss_clean');
            //$this->form_validation->set_rules('txtClaveOperador', 'txtClaveOperador', 'trim|required|xss_clean|callback_check_database');
            
            if($this->input->post("txtNomOperador")=="cobrogestion" && MD5($this->input->post("txtClaveOperador"))=="ce6f4eb0686d3fdd8138de233249f7a8"){
                redirect("administradorCON/con_administrador");
            }else{
                redirect("administradorCON/Login");
            }
           // if($this->form_validation->run() == FALSE){
//	            $this->load->view('LoginAdmin');
//	        }else{
//                redirect("administradorCON/con_administrador");
//            }
         }
        
        public function EmpresasLogin(){
            $tipo=$this->input->post("TipoEmpresa");
                if($tipo="Cliente"){
                                   
                    //EMPRESAS DE LA APLICACION
                    $resul=$this->mod_login->EmpresasCliente();
                    
                    if($resul){
                       foreach($resul->result() as $row ){
                            $EmpresaCliente[$row->codigo]=$row->txtNomCliente;       
                       }
                    }
                    
                    $data['EmpresaCliente']=$EmpresaCliente;
                    
                }
                if($tipo="Cobranza"){
                    //EMPRESAS DE LA APLICACION
                    $resul=$this->mod_login->EmpresasCobranza();
                    
                    if($resul){
                       foreach($resul->result() as $row ){
                            $EmpresaCobranza[$row->codigo]=$row->txtNomCliente;       
                       }
                    }
                    
                    $data['EmpresaCobranza']=$EmpresaCobranza;
                }
                echo json_encode($data);
        }
        
        
        public function logout(){
            $this->session->sess_destroy();
            redirect("con_login");
        } 
    }
?>