<?php
    class EmpresaCobranzaCON extends CI_Controller{
         public function __construct(){
            parent::__construct();
            $this->load->helper('form');
            $this->load->model('AdministradorMOD/EmpresaCobranzaMOD');
        }
        
        public function index(){
            
        }
        function NuevoCliente(){
            $seqCliente= $this->EmpresaCobranzaMOD->MaxCliente();
            $seqOperador=$this->EmpresaCobranzaMOD->MaxOperador();
            
            $EmpresaCliente=array(
                'codigo'=>$seqCliente, 
                'txtNomCliente'=>$this->input->post("txtNomCliente"), 
                'rutCliente'=>$this->input->post("rutCliente"),
                'seqCiudadCliente'=>$this->input->post("RegionesChile"),
                'txtDirCliente' =>$this->input->post("txtDirCliente"), 
                'txtTelCliente'=>$this->input->post("txtTelCliente"),
                'dtFechaCreacion'=>date("Y-m-d"),
                'seqEstadoCliente' =>1,
                'seqTipoCliente'=>2,
            );
            
            $OperadorCliente=array(
                'codigo' => $seqOperador,
                'txtNomOperador'=>null,
                'rutOperador' =>null,
                'seqRegionChile' =>null,
                'txtDirOperador' =>null,
                'txtTelOperador' =>null,
                'seqCliente' =>$seqCliente,
                'seqTipoOperador' =>1,
                'txtUsuOperador' =>$this->input->post("txtUsuCliente"),
                'txtClaveOperador' =>MD5($this->input->post("ClaveCliente")),
            );
            
            $this->EmpresaCobranzaMOD->NuevoClieteInsert($EmpresaCliente,$OperadorCliente);
            redirect("administradorCON/con_administrador");
        }
        function CargaCliente($seqCliente){
            //echo $seqCliente;
            
            $row=$this->EmpresaCobranzaMOD->DatosClienteSelect($seqCliente);
                     
            //$data['nombredata']=$row->codigo;
            $data['nombre']=$row->txtNomCliente;
            $data['rut']=$row->rutCliente;
            //$data['ciudad']=$row->seqCiudadCliente;
            $data['direccion']=$row->txtDirCliente;
            $data['telefono']=$row->txtTelCliente;
            //$data['creacion']=$row->dtFechaCreacion; 
            //$data['estado']=$row->seqEstadoCliente;

            echo json_encode($data);
        }
    }
?>   