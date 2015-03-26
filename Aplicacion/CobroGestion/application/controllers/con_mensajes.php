<?php
    class con_mensajes extends CI_Controller{
        
        function __construct(){
            parent::__construct();
            $this->load->model('mod_mensajes');
            $this->load->library('session');
        }
        public function index(){
            
            $cod_usuario=$this->session->userdata('codigo');
            //$msj_sinleer=
            //echo '->'.$this->mod_mensajes->msj_nuevo($cod_usuario);;
            if ($this->mod_mensajes->msj_nuevo($cod_usuario)==""){
                echo "Nuevos Mensajes.";
            }
            //
            //TOMA LOS DATOS DE LOS mensajes
            $data['Mensajes']=$this->mod_mensajes->msj_recibido($cod_usuario);
            //echo '->'.$data['Mensaje'];
            //TOMA LOS TIPOS DE ACCIONES Y EL CODIGO PARA LOS COMBOBOX
            

            $resul=$this->mod_mensajes->Usuarios($cod_usuario);
            $Operadores = array();
            if($resul){
               foreach($resul->result() as $row ){
                    $Operadores[$row->Codigo]=$row->Nombre;       
               }
            }
            $data['Operadores']=$Operadores;
            $this->load->view('view_mensajes',$data); 
            //'echo '->'.$this->session->userdata('codigo');
        }
        
        function enviar(){
            $estado="NOLEIDO";
            $datos_mensaje=array(
                'Codigo'=>$this->mod_mensajes->max_msj(),
                'Emisor'=>$this->session->userdata('codigo'),
                'Receptor'=>$this->input->post('Operadores'),
                'FechaEnvio'=>date("Y/m/d H:i:s"),
                'Mensaje'=>$this->input->post('msj'),
                'Estado'=>$estado,
                'Asunto'=>$this->input->post('Asunto'),
                'Conversacion'=>$this->mod_mensajes->max_conv(),
            );
            
            $this->mod_mensajes->agrega_mensaje($datos_mensaje);
            redirect('con_mensajes');
        }
        
        function mostrar_msj($cod_msj){
            $this->mod_mensajes->modifica_estado($cod_msj);
            $row=$this->mod_mensajes->busca_msj($cod_msj);

            $data['cod']=$row->Codigo;
            $data['emisor']=$row->Emisor;
            $data['fecha']=$row->FechaEnvio;
            $data['msj']=$row->Mensaje;
            $data['asunto']=$row->Asunto;
            $data['conv']=$row->Conversacion;
            
            echo json_encode($data);
        }
        
        function responder(){
            $estado="NOLEIDO";
            $cod_msj=$this->input->post('cod_msj');
            $datos_mensaje=array(
                'Codigo'=>$this->mod_mensajes->max_msj(),
                'Emisor'=>$this->session->userdata('codigo'),
                'Receptor'=>$this->input->post('emisor_msj'),
                'FechaEnvio'=>date("Y/m/d H:i:s"),
                'Mensaje'=>$this->input->post('respuesta_msj'),
                'Estado'=>$estado,
                'Asunto'=>$this->input->post('asunto_msj'),
                'Conversacion'=>$this->input->post('conv_msj'),
            );
            $this->mod_mensajes->agrega_mensaje($datos_mensaje);
            redirect('con_mensajes');
            
        }
        function elimina_msj($conv){
            $this->mod_mensajes->borra_conv($conv);
            redirect('con_mensajes');
        }
    }
?>