<?php
    class con_administrador extends CI_Controller{
        
        public function __construct(){
            parent::__construct();
             $this->load->helper('form');
            $this->load->model('mod_admin');
            $this->load->model('AdministradorMOD/ClientesMOD');
            $this->load->model('AdministradorMOD/EmpresaCobranzaMOD');
            $this->load->model('AdministradorMOD/SistemaMOD');
        }
        
        //VAMOS A CARGAR TODOS LOS DATOS NECESARIOS, CUANDO SE CARGA LA INTERFAZ DEL ADMINISTRADOR
        public function index(){
            //REGIONES DE CHILE
            $resul=$this->ClientesMOD->DatosCiudad();
            $RegionesChile = array(
                '-1'=>'- Seleccione una regi&oacute;n -',
            );
            if($resul){
               foreach($resul->result() as $row ){
                    $RegionesChile[$row->codigo]=$row->NomRegion;       
               }
            }
            $data['RegionesChile']=$RegionesChile;
            
            $data['DatosClientes']=$this->ClientesMOD->DatosClientes();
            
            $data['DatosEmpresaCobranza']=$this->EmpresaCobranzaMOD->DatosEmpresaCobranza();
            
          //  $data['DatosAbonos']=$this->mod_admin->SELDatosAbono();
            $data['OBJDocumento']=$this->mod_admin->SELDocumentos();
            //$data['datos_clientes']=$this->mod_admin->lista_clientes();
            
            //$data['Historial']=$this->mod_admin->Historial();            
            
            //CARGO DEL TIPO DE CLIENTES
            $resul=$this->ClientesMOD->TipoOperador();
            $TipoOperador = array(
                '-1'=>'- Seleccione un tipo de cliente -',
            );
            if($resul){
               foreach($resul->result() as $row ){
                    $TipoOperador[$row->codigo]=$row->txtNomTipoOP;       
               }
            }
            $data['TipoOperador']=$TipoOperador;
            
            //CARGO DEL ESTADOS DE CLIENTES
            $resul=$this->ClientesMOD->EstadoCliente();
            $EstadoCliente = array(
                '-1'=>'- Seleccione un tipo de cliente -',
            );
            if($resul){
               foreach($resul->result() as $row ){
                    $EstadoCliente[$row->seqEstadoCliente]=$row->txtNomEstadoCL;       
               }
            }
            $data['EstadoCliente']=$EstadoCliente;
            
            //############################################################
            //      DATOS PARA CONFIGURAR ESTADOS-ACCIONES
            //############################################################
            $resul=$this->SistemaMOD->EstadosDocumento();
            $EstadoDeuda = array(
                '-1'=>'- Seleccione un estado -',
            );
            if($resul){
               foreach($resul->result() as $row ){
                    $EstadoDeuda[$row->codigo]=$row->txtNomEstadoDeuda;       
               }
            }
            $data['EstadoDeuda']=$EstadoDeuda;
            
            //############################################################
            //      DATOS PARA CONFIGURAR ACCIONES-ESTADOS
            //############################################################
            $resul=$this->SistemaMOD->AccionesDocumento();
            $AccionesDeuda = array(
                '-1'=>'- Seleccione una accion -',
            );
            if($resul){
               foreach($resul->result() as $row ){
                    $AccionesDeuda[$row->codigo]=$row->NomAccion;       
               }
            }
            $data['AccionesDeuda']=$AccionesDeuda;
            
            
            //ABRE LA VISTA DEL ADMINISTRADOR Y LE PASA LOS DATOS
            $this->load->view('view_admin',$data);
            
        }
        
        public function agregar_cliente(){
            $tipo_sesion="normal";
            $cod_cliente=$this->mod_admin->max_cliente();
           
           $datos_cliente =array(
                'codigo'=>$cod_cliente,
                'Rut'=>$this->input->post('rut'),
                'Nombres'=>$this->input->post("nombre"),
                'ApePat'=>$this->input->post("apat"),
                'ApeMat'=>$this->input->post("amat"),
                'Direccion'=>$this->input->post("dir"),
                'Telefono'=>$this->input->post("telefono"),
                'Mail'=>$this->input->post("mail"),
                'Persona'=>$this->input->post("IdTipoPersona"),
            );   
            
            $datos_usuario=array(
                'codigo'=>$this->mod_admin->max_usuario(),
                'nombre'=>$this->input->post("nomusu"),
                'clave'=>MD5($this->input->post("pass")),
                'tipo'=>$tipo_sesion,
                'Cliente'=>$cod_cliente,
            );        

            $this->mod_admin->AgregaCliente($datos_cliente);
            $this->mod_admin->AgregaUsuario($datos_usuario);
            redirect("con_administrador");
        }
        
        function carga_cliente($codigo){
            $row=$this->mod_admin->busca_cliente($codigo);
                     
            $data['cod']=$row->codigo;
            $data['rut']=$row->Rut;
            $data['nom']=$row->Nombres;
            $data['apat']=$row->ApePat;
            $data['amat']=$row->ApeMat;
            $data['dire']=$row->Direccion;
            $data['telef']=$row->Telefono;
            $data['mail']=$row->Mail;
            $data['tperso']=$row->Persona;

            echo json_encode($data);

        }
        
        function modifica_cliente(){
           $cod_cliente=$this->input->post('cod');
            
             $datos_cliente =array(
                'Rut'=>$this->input->post('rut'),
                'Nombres'=>$this->input->post("nombre"),
                'ApePat'=>$this->input->post("apat"),
                'ApeMat'=>$this->input->post("amat"),
                'Direccion'=>$this->input->post("dir"),
                'Telefono'=>$this->input->post("telefono"),
                'Mail'=>$this->input->post("mail"),
                'Persona'=>$this->input->post("TipoPersona"),
            );           
            
            $this->mod_admin->editar_cliente($cod_cliente,$datos_cliente);
            redirect("con_administrador");
        }
        
        function elimina_cliente($codigo){
            $this->mod_admin->elimina_cliente($codigo);
            redirect("con_administrador");
        }
        
        function agregar_tipo_cliente(){
            $codigo=$this->mod_admin->max_tipo_cliente();
            $datos_tipo_cliente =array(
                'codigo'=>$codigo,
                'Nombre'=>$this->input->post('tipo'),
                'Descripcion'=>$this->input->post("desc"),
            ); 
            
            $this->mod_admin->agrega_tipo_cliente($datos_tipo_cliente);
            redirect("con_administrador");
        }
        
        function agregar_etapa_cobranza(){
            $codigo=$this->mod_admin->max_etapa_cobranza();
            $datos_cob =array(
                'codigo'=>$codigo,
                'Nombre'=>$this->input->post('tipo'),
                'Descripcion'=>$this->input->post("desc"),
            ); 
            
            $this->mod_admin->agrega_etapa_cobranza($datos_cob);
            redirect("con_administrador");
        }
        
        function EliminarAbono($seqDocumento){
            
            $DatosDoc =array(
                'Remanente'=>"0",                
            ); 
            
            $this->mod_admin->DELAbonos($seqDocumento);
            $this->mod_admin->UPDDocumento($seqDocumento,$DatosDoc);
            
  
            redirect("administradorCON/con_administrador");
            

        }
    }
?>