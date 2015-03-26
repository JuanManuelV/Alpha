<?php
    class ClientesCON extends CI_Controller{
        
        public function __construct(){
            parent::__construct();
            $this->load->helper('form');
            $this->load->model('AdministradorMOD/ClientesMOD');
        }
        
        public function index(){
            
        }
        
        function NuevoCliente(){
            $seqCliente= $this->ClientesMOD->MaxCliente();
            $seqOperador=$this->ClientesMOD->MaxOperador();
            
            $EmpresaCliente=array(
                'codigo'=>$seqCliente, 
                'txtNomCliente'=>$this->input->post("txtNomCliente"), 
                'rutCliente'=>$this->input->post("rutCliente"),
                'seqCiudadCliente'=>$this->input->post("RegionesChile"),
                'txtDirCliente' =>$this->input->post("txtDirCliente"), 
                'txtTelCliente'=>$this->input->post("txtTelCliente"),
                'dtFechaCreacion'=>date("Y-m-d"),
                'seqEstadoCliente' =>1,
                'seqTipoCliente'=>1,
                'Visible'=>"t",
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
            
            $this->ClientesMOD->NuevoClieteInsert($EmpresaCliente,$OperadorCliente);
            redirect("administradorCON/con_administrador");
        }
        
        function CargaCliente($seqCliente){
            $row=$this->ClientesMOD->DatosClienteSelect($seqCliente);
                     
            $data['codigo']=$row->codigo;
            $data['nombre']=$row->txtNomCliente;
            $data['rut']=$row->rutCliente;
            //$data['ciudad']=$row->seqCiudadCliente;
            $data['direccion']=$row->txtDirCliente;
            $data['telefono']=$row->txtTelCliente;
            $data['creacion']=$row->dtFechaCreacion; 
            //$data['estado']=$row->seqEstadoCliente;
            $data['tipo']=$row->seqTipoCliente;

            echo json_encode($data);
        }
        
        function BloqueaCliente($seqCliente){
            $this->ClientesMOD->BloqueaCliente($seqCliente);
            redirect("administradorCON/con_administrador");
        }
        
        function DesbloqueaCliente($seqCliente){
            $this->ClientesMOD->DesbloqueaCliente($seqCliente);
            redirect("administradorCON/con_administrador");
        }
        
        function ModificarCliente(){
            $seqCliente= $this->input->post("CodEmpresaMod");
            $TipoOperador=$this->input->post("TipoOperador");
            
                $datosCliente=array(
                    'codigo'=>$seqCliente, 
                    'txtNomCliente'=>$this->input->post("txtNomClienteMod"), 
                    'rutCliente'=>$this->input->post("rutClienteMod"),
                    'seqCiudadCliente'=>$this->input->post("RegionesChileMod"),
                    'txtDirCliente' =>$this->input->post("txtDirClienteMod"), 
                    'txtTelCliente'=>$this->input->post("txtTelClienteMod"),
                    //'dtFechaCreacion'=>date("Y-m-d"),
                    'seqEstadoCliente' =>1,
                    'seqTipoCliente'=>1,
                );

            $this->ClientesMOD->ModificarCliente($datosCliente,$seqCliente);
            redirect("administradorCON/con_administrador");
            
        }
        
        function ModificaEmpresa(){
            $seqCliente= $this->input->post("CodEmpresaMod");
            $TipoOperador=$this->input->post("TipoOperador");
            
            $datosCliente=array(
                    'codigo'=>$seqCliente, 
                    'txtNomCliente'=>$this->input->post("txtNomEmpresaMod"), 
                    'rutCliente'=>$this->input->post("rutEmpresaMod"),
                    'seqCiudadCliente'=>$this->input->post("RegionesChile"),
                    'txtDirCliente' =>$this->input->post("txtDirEmpresaMod"), 
                    'txtTelCliente'=>$this->input->post("txtTelEmpresaMod"),
                    //'dtFechaCreacion'=>date("Y-m-d"),
                    'seqEstadoCliente' =>1,
                    'seqTipoCliente'=>2,
            );
            $this->ClientesMOD->ModificarCliente($datosCliente,$seqCliente);
            redirect("administradorCON/con_administrador");
        }
    }
?>