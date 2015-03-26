<?php 
     class EmpresaCobranzaCON extends CI_Controller{
        
        public function __construct(){
            parent::__construct();
            $this->load->helper('form');
            $this->load->helper('array');
            $this->load->library('form_validation');
            $this->load->library('session');
            $this->load->model('empresaCobranzaMOD/EmpresaCobranzaMOD');
            
        }
        function index(){
            
            
            $codUsuario=$this->session->userdata('codigo');
            $data['tipoopera']= $this->session->userdata('tipoOperador');
            //      BUSCA LOS DOCUMENTOS QUE HAN SIDO DERIVADOS A LA EMPRESA
            $data['DocumentosDerivados']=$this->EmpresaCobranzaMOD->DocumentosDerivados($codUsuario);
            $data['Operadores']=$this->EmpresaCobranzaMOD->Operadores($codUsuario);
             //TOMA LOS TIPOS DE ACCIONES Y EL CODIGO PARA LOS COMBOBOX
            $resul=$this->EmpresaCobranzaMOD->EstadoDeuda();
             $EstadoDeuda = array(
                '-1'=>'TODOS',
             );
            if($resul){
               foreach($resul->result() as $row ){
                    $EstadoDeuda[$row->codigo]=$row->txtNomEstadoDeuda;       
               }
            }
            $data['EstadoDeuda']=$EstadoDeuda;
            
             //TOMA LOS TIPOS DE ACCIONES Y EL CODIGO PARA LOS COMBOBOX
            $resul=$this->EmpresaCobranzaMOD->TipoAccion();
             $TipoAccion = array();
            if($resul){
               foreach($resul->result() as $row ){
                    $TipoAccion[$row->codigo]=$row->NomAccion;       
               }
            }
            $data['TipoAccion']=$TipoAccion;
            
            //TIPOS DE RUT
            
            $resul=$this->EmpresaCobranzaMOD->TipoRut();
             $TipoRut = array(
             //'-1'=>'TODOS',
             );
            if($resul){
               foreach($resul->result() as $row ){
                    $TipoRut[$row->codigo]=$row->txtTipoRut;       
               }
            }
            $data['TipoRut']=$TipoRut; 
            
                //REGIONES
            $resul=$this->EmpresaCobranzaMOD->RegionesChile();
             $RegChile = array(
             //'-1'=>'TODOS',
             );
            if($resul){
               foreach($resul->result() as $row ){
                    $RegChile[$row->codigo]=$row->NumRegion." - ".$row->NomRegion;       
               }
            }
            $data['RegChile']=$RegChile;     
            
             //OPERADORES
            $resul=$this->EmpresaCobranzaMOD->Operadores($codUsuario);
            $NombreOperador = array(
                '-1'=>'- Seleccione un operador -',
             );
            if($resul){
               foreach($resul->result() as $row ){       
                    $NombreOperador[$row->codigo]=$row->txtNomOperador; 
               }               
            }
            $data['NombreOperador']=$NombreOperador; 
            
            
            $this->load->view("EmpresaCobranzaVIEW",$data);
        }
        
        //cambiar funciones oa otro archivo php
        function agregar_operador(){
            //$cod_usuario=$this->EmpresaCobranzaMOD->max_usuario();

            $datos_operador=array(
                'codigo' => $this->EmpresaCobranzaMOD->MaxOperador(),
                'txtNomOperador'=>$this->input->post("NomOperador")." ".$this->input->post("ApellOperador"),
                'rutOperador' =>$this->input->post("rutOperador"),
                'seqRegionChile' =>$this->input->post("RegChile"),
                'txtDirOperador' =>$this->input->post("DirOperador"),
                'txtTelOperador' =>$this->input->post("TelefOperador"),
                'seqCliente' =>$this->session->userdata('codigo'),
                'seqTipoOperador' =>3,
                'txtUsuOperador' =>$this->input->post('UsuarioOperador'),
                'txtClaveOperador' =>md5($this->input->post('ClaveOperador')),
                'Visible'=>"t",
            );
            $datos_rut=array(
                    'codigo'=>$this->EmpresaCobranzaMOD->max_rut(),
                    'RutAsociado'=>$this->input->post("rutOperador"),
                    'TipoRut'=>$this->input->post("TipoRut"),
            );
            
            $this->EmpresaCobranzaMOD->agrega_Rut($datos_rut);
            $this->EmpresaCobranzaMOD->agrega_operador($datos_operador);
            redirect("empresaCObranzaCON/EmpresaCobranzaCON");
            
        }
        function carga_operador($codigo){
            //echo $codigo;
            $row=$this->EmpresaCobranzaMOD->busca_operador($codigo);

            $data['OperaCod']=$row->codigo;
            $data['NomOpe']=$row->txtNomOperador;
            $data['rutOperador']=$row->rutOperador;
            //$data['OperaRegion']=$row->NomRegion;
            $data['OperaDir']=$row->txtDirOperador;
            $data['OperaTel']=$row->txtTelOperador;
            //$data['']=$row->seqCliente;
            //$data['']=$row->seqTipoOperador;
            $data['OperaUsu']=$row->txtUsuOperador;
            $data['OperaClave']=$row->txtClaveOperador; 

            echo json_encode($data);
        }
        
        function AsignarOperador(){
            $seqDocumento=$this->input->post("CodDocumento");
            //$row=$this->mod_nuevodeudor->AsignarOperador($codigo);
            //$datoDeuda=array(
//                'Cliente'=>$this->input->post("NombreOperador"),
//            );
            $datoRelacion=array(
                'codEmpresaCobranza'=>$this->input->post("NombreOperador"),
            );
            //echo $seqDocumento;
            //echo "------>".$this->input->post("NombreOperador");
            $this->EmpresaCobranzaMOD->AsignarDocOperador($seqDocumento,$datoRelacion);
            redirect("empresaCobranzaCON/EmpresaCobranzaCON");
        }
        
        function elimina_operador($codigo){
            $this->EmpresaCobranzaMOD->elimina_operador($codigo);
            redirect("empresaCobranzaCON/EmpresaCobranzaCON");
        }
        
        function DerivarAdministrador(){
            $codOperador=$this->session->userdata('codigo');
            $seqCliente=$this->session->userdata('seqCliente');
            $seqDocumento=$this->input->post("SeqDocumento");
            $row=$this->EmpresaCobranzaMOD->seqAdministrador($seqCliente);
            
            foreach ($row as $valor){
                $seqAdministrador= $valor->codigo;           
            }
            
            $datoAdmin=array(
                'codEmpresaCobranza'=>$seqAdministrador,
            );
            
            $this->EmpresaCobranzaMOD->DerivaDocAdministrador($seqCliente,$codOperador,$seqDocumento,$datoAdmin);
            redirect("empresaCobranzaCON/EmpresaCobranzaCON");
            //echo "cliente->".$seqCliente;
//            echo "documento->".$seqDocumento;
//            echo "documento->".$seqDocumento;
        }
        
        function modifica_operador(){
            $cod_operador=$this->input->post('CodOperadorMod');
            $datos_operador=array(
                'codigo' => $cod_operador,
                'txtNomOperador'=>$this->input->post("NomOperadorMod"),
                'rutOperador' =>$this->input->post("rutOperadorMod"),
                'seqRegionChile' =>$this->input->post("RegChile"),
                'txtDirOperador' =>$this->input->post("DirOperadorMod"),
                'txtTelOperador' =>$this->input->post("TelefOperadorMod"),
                'seqCliente' =>$this->session->userdata('codigo'),
                //'seqTipoOperador' =>3,
                'txtUsuOperador' =>$this->input->post('UsuarioOperadorMod'),
                'txtClaveOperador' =>md5($this->input->post('ClaveOperadorMod')),
            );
             
             //echo $cod_operador;       
            
            $this->EmpresaCobranzaMOD->ModificaOperador($cod_operador,$datos_operador);
            redirect("empresaCobranzaCON/EmpresaCobranzaCON");
        }
    }
?>