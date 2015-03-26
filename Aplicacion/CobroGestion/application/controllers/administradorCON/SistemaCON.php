<?php
    class SistemaCON extends CI_Controller{
        
        public function __construct(){
            parent::__construct();
            $this->load->helper('form');
            $this->load->model('AdministradorMOD/SistemaMOD');
        }
        
        public function index(){
            
        }
        
        function ConfigEstadosAcciones(){
            $acciones=$this->input->post('AccionesSeleccionadas');
            $array_acciones = explode(",", $acciones);
            $Estado=$this->input->post('seqEstado');
            
            $this->SistemaMOD->EliminaRelaciones($Estado);
            
            
            foreach ($array_acciones as $valor){
                $Relacion =array(
                    'codigo'=>$this->SistemaMOD->MaxRelEstadoAccion(),
                    'codEstado'=>$Estado,
                    'codAccion'=>$valor
                ); 
                $this->SistemaMOD->AgregaRelacionEstadoAccion($Relacion);
            }
            
            redirect("administradorCON/con_administrador");
            //echo "Estado->".$this->input->post('seqEstado');
        }
        
        function ConfigAccionesEstados(){
            $estados=$this->input->post('EstadosSeleccionados');
            $array_estados = explode(",", $estados);
            $accion=$this->input->post('seqAccion');
            
            
            $this->SistemaMOD->EliminaRelacionesAccionEstado($accion);            
            
            foreach ($array_estados as $valor){
                $Relacion =array(
                    'codigo'=>$this->SistemaMOD->MaxRelAccionEstado(),                    
                    'codAccion'=>$accion,
                    'codEstado'=>$valor
                ); 
                $this->SistemaMOD->AgregaRelacionAccionEstado($Relacion);
            }
            
            redirect("administradorCON/con_administrador");
            //echo "Estado->".$this->input->post('seqEstado');
        }
        
        function BuscaEstadosCheck(){           
           
            $resulado=$this->SistemaMOD->BuscaEstadosCheck($this->input->post('Codigo'));

            if($resulado != null){
                foreach($resulado->result() as $row ){
                    $RelAcciones[$row->codigo]=$row->codAccion;  
                }
                 $data['RelAcciones']=$RelAcciones;
            } else {
                $data['RelAcciones'] = null;
            }
            
            echo json_encode($data);
        }
        
        function BuscaAccionesCheck(){           
           
            $resulado=$this->SistemaMOD->BuscaAccionesCheck($this->input->post('Codigo'));

            if($resulado != null){
                foreach($resulado->result() as $row ){
                    $RelEstados[$row->codigo]=$row->codEstado;  
                }
                 $data['RelEstados']=$RelEstados;
            } else {
                $data['RelEstados'] = null;
            }
            
            echo json_encode($data);
        }
    }
?>