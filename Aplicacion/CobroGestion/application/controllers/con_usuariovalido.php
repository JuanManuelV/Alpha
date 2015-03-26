
<?php
    class con_usuariovalido extends CI_Controller{
        
        function __construct(){
            parent::__construct();
            $prefs = array (
                'start_day' => 'sunday',
                'month_type' => 'long',
                'day_type' => 'short',
                'show_next_prev' => true
                
            );
            $this->load->library('calendar', $prefs);
            //$this->lang->load('text', 'spanish');
            //$this->lang->load('spanish', 'spanish');
            $this->load->helper('date');
            
             $this->load->model('mod_nuevodeudor');
             //$this->load->controller('con_usuariovalido');
             $this->load->model('mod_operadores');
             $this->load->library('session');
             $CodigoLinea;
        }
        
        function index(){
            //si el usuario es operador o cliente
            $data['tipoopera']= $this->session->userdata('tipoOperador');

           // if($this->input->post("fecha_desde")!="" and $this->input->post("fecha_hasta")!=""){
//                $data['FechaDesdeFiltro']=$this->input->post("fecha_desde");
//                $data['FechaHastaFiltro']=$this->input->post("fecha_hasta");
//            }
            
            
            if ($this->uri->segment(3)){
                $data["tab"]=$this->uri->segment(3);
            }else{
                $data["tab"]="";
            }
            
            $NomDeudorFilCont=$this->input->post("NomDeudorFilContacto");
            $FilDeudorNom=$this->input->post("NomDeudorFil");
            $FilDeudorRut=$this->input->post("RutDeudorFil");
            $FilDeudorCiudad=$this->input->post("CiudadDeudorFil");
            $FilOperadorNom=$this->input->post("NombreOperador");
            $FilOperadorUsu=$this->input->post("UsuarioOperador");
            $FilContactoNombre=$this->input->post("NomContacto");
            $FilContactoTipo=$this->input->post("TipoContFiltro");
            //$FilEstadoDoc=
            
            
            
            $cod_usuario=$this->session->userdata('codigo'); //codigo del usuario que usa la aplicacion

            if($FilOperadorNom=='' and $FilOperadorUsu==''){
                $data['Operadores']=$this->mod_nuevodeudor->Operadores($cod_usuario);
            }else{
                if($FilOperadorNom=='-1' and $FilOperadorUsu=='-1'){
                    $data['Operadores']=$this->mod_nuevodeudor->Operadores($cod_usuario);
                    $data["tab"]="PanelOperador";
                }else{
                    $data['Operadores']=$this->mod_nuevodeudor->FiltroOperadores($FilOperadorNom,$FilOperadorUsu);
                    $data["tab"]="PanelOperador";
                }
            }

        
            //TOMA LOS DATOS DE LAS ACCIONES DE COBRANZA            
            $data['accion_cobranza']=$this->mod_nuevodeudor->AccionCobranza($cod_usuario);
            
            //TOMA LOS DATOS DE LOS DEUDORES
            if($FilDeudorNom=='' and $FilDeudorRut=='' and $FilDeudorCiudad==''){
                $data['datos_deudores']=$this->mod_nuevodeudor->carga_deudores($cod_usuario);
            }else{
                if($FilDeudorNom=='-1' and $FilDeudorRut=='-1' and $FilDeudorCiudad=='-1'){
                    $data['datos_deudores']=$this->mod_nuevodeudor->carga_deudores($cod_usuario);
                    $data['tab']="PanelDeudores";
                }else{
                    $data['datos_deudores']=$this->mod_nuevodeudor->FiltroDeudores($FilDeudorNom,$FilDeudorRut,$FilDeudorCiudad);
                    $data['tab']="PanelDeudores";
                }
            }
            
            
            //TOMA LOS DATOS DE LOS contactos
            if($FilContactoNombre=='' and $FilContactoTipo=='' and $NomDeudorFilCont==''){
                $data['datos_contacto']=$this->mod_nuevodeudor->carga_contactos($cod_usuario);
            }else{
                if($FilContactoNombre=='-1' and $FilContactoTipo=='-1' and $NomDeudorFilCont=='-1'){
                $data['datos_contacto']=$this->mod_nuevodeudor->carga_contactos($cod_usuario);
                $data["tab"]="PanelContactos";
                }else{
                    $data['datos_contacto']=$this->mod_nuevodeudor->FiltroContactos($FilContactoNombre,$FilContactoTipo,$NomDeudorFilCont);
                    $data["tab"]="PanelContactos";
                }
            }
            
            
            //toma los datos de los documentos
            $data['datos_doc']=$this->mod_nuevodeudor->carga_doc($cod_usuario);
            
            //toma los historiales de documentos
            //$data['datos_historial']=$this->mod_nuevodeudor->HistorialDocs($cod_usuario);
            
            
            $resul=$this->mod_nuevodeudor->TipoDocRecauda();
            $TipoDocRecauda=array();
            if($resul){
               foreach($resul->result() as $row ){
                    $TipoDocRecauda[$row->codigo]=$row->DocumentoPago;       
               }
            }
            $data['TipoDocRecauda']=$TipoDocRecauda;
            
            
            
            //tramo acciones
            $resul=$this->mod_nuevodeudor->TramoAcciones();
             $TramoAccion = array(
                '-1'=>'TODOS',
             );
            if($resul){
               foreach($resul->result() as $row ){
                    $TramoAccion[$row->codigo]=$row->Nombre;       
               }
            }
            $data['TramoAccion']=$TramoAccion;
            
            //TOMA LOS TIPOS DE ACCIONES Y EL CODIGO PARA LOS COMBOBOX
            $resul=$this->mod_nuevodeudor->EstadoDeuda();
             $EstadoDeuda = array(
                '-1'=>'TODOS',
             );
            if($resul){
               foreach($resul->result() as $row ){
                    $EstadoDeuda[$row->codigo]=$row->txtNomEstadoDeuda;       
               }
            }
            $data['EstadoDeuda']=$EstadoDeuda;
            
            //TOMA LOS TIPOS DE CONTACTOS Y EL CODIGO PARA LOS COMBOBOX
            $resul=$this->mod_nuevodeudor->TipoContacto();
             $TipoContacto = array();
            if($resul){
               foreach($resul->result() as $row ){
                    $TipoContacto[$row->codigo]=$row->NomTipoContacto;       
               }
            }
            $data['TipoContacto']=$TipoContacto;
            
            //TOMA LOS TIPOS DE ACCIONES Y EL CODIGO PARA LOS COMBOBOX
            $resul=$this->mod_nuevodeudor->TipoAccion();
             $TipoAccion = array(
                //'-1'=>'- Seleccione acción -',
             );
            if($resul){
               foreach($resul->result() as $row ){
                    $TipoAccion[$row->codigo]=$row->NomAccion;       
               }
            }
            $data['TipoAccion']=$TipoAccion;
            
            //TOMA LOS TIPOS DE PERSONA Y EL CODIGO PARA LOS COMBOBOX
            $resul=$this->mod_nuevodeudor->TipoPersona();
             $TipoPersona = array();
            if($resul){
               foreach($resul->result() as $row ){
                    $TipoPersona[$row->codigo]=$row->Nombre;       
               }
            }
            $data['TipoPersona']=$TipoPersona;
            
            //TOMA LOS TIPOS DE DOCUMENTOS Y EL CODIGO PARA LOS COCMBOBOX
            $resul=$this->mod_nuevodeudor->TipoDocumento();
            $TipoDocumento=array(
                 //'-1'=>'- Seleccione tipo de documento -',
            );
            if($resul){
                foreach($resul->result() as $row){
                    $TipoDocumento[$row->codigo]=$row->Nombre;
                }
            }
             $data['TipoDocumento']=$TipoDocumento;
             
            //TOMA LOS TIPOS DE PERSONA Y EL CODIGO PARA LOS COMBOBOX
            $resul=$this->mod_nuevodeudor->list_deudores($cod_usuario);
            
             $deudores = array();
            
            if($resul){
               foreach($resul->result() as $row ){
                    $deudores[$row->codigo]=$row->nombres." ".$row->apellidos;       
               }
            }
            $data['deudores']=$deudores;
            
            //TOMA LOS TIPOS DE PERSONA Y EL CODIGO PARA LOS COMBOBOX
            $resul=$this->mod_nuevodeudor->DeudoresFiltro($cod_usuario);
             $DeudorFiltro = array(
                '-1'=>'TODOS',
                );
            if($resul){
               foreach($resul->result() as $row ){
                    $DeudorFiltro[$row->codigo]=$row->nombres." ".$row->apellidos;       
               }
            }
            $data['DeudorFiltro']=$DeudorFiltro;
            
            //TOMA LOS TIPOS DE ACCIONES Y EL CODIGO PARA LOS COMBOBOX
            $resul=$this->mod_nuevodeudor->TipoAccionGestion();
             $AccionFiltro = array(
             '-1'=>'TODOS',
             );
            if($resul){
               foreach($resul->result() as $row ){
                    $AccionFiltro[$row->codigo]=$row->NomAccion;       
               }
            }
            $data['AccionFiltro']=$AccionFiltro;    
            
            //=======================================
            //      CARGA DATOS PARA EL FILTRO 
            //=======================================
            //OPERADORES
            $resul=$this->mod_nuevodeudor->Operadores($cod_usuario,"FILTRO");
            $NombreOperador = array(
                '-1'=>'TODOS',
             );
            $UsuarioOperador=array(
                '-1'=>'TODOS',
            );
            if($resul){
               foreach($resul->result() as $row ){
                    //$DatosOperadores[$row->codigo]=$row->Nombre." ".$row->Apellidos;       
                    $NombreOperador[$row->codigo]=$row->txtNomOperador; 
                    $UsuarioOperador[$row->codigo]=$row->txtUsuOperador; 
               }               
            }
            $data['NombreOperador']=$NombreOperador; 
            $data['UsuarioOperador']=$UsuarioOperador;
            
            //CONTACTOS
            $resul=$this->mod_nuevodeudor->Contactos($cod_usuario);
            $NomDeudorFil=array(
                '-1'=>'TODOS',
            );
            $NomContacto=array(
                '-1'=>'TODOS',
            );
            if($resul){
               foreach($resul->result() as $row ){
                    //$DatosOperadores[$row->codigo]=$row->Nombre." ".$row->Apellidos;       
                    $NomContacto[$row->codigo]=$row->Nombre." ".$row->Apellidos." - ".$row->rut; 
               }               
            }            
            $data['NomContacto']=$NomContacto; 
            
            //TIPOCONTACTOS
            $resul=$this->mod_nuevodeudor->TipoContacto();
            $TipoContFiltro=array(
                '-1'=>'TODOS',
            );
            if($resul){
               foreach($resul->result() as $row ){
                    //$DatosOperadores[$row->codigo]=$row->Nombre." ".$row->Apellidos;       
                    $TipoContFiltro[$row->codigo]=$row->NomTipoContacto; 
               }               
            }            
            $data['TipoContFiltro']=$TipoContFiltro; 
            
            //DEUDORES
            $resul=$this->mod_nuevodeudor->Deudores($cod_usuario);
            $CiudadDeudorFil=array(
              '-1'=>'TODOS',  
            );
            $NomDeudorFil=array(
                '-1'=>'TODOS',
            );
            $NomDeudorFilContacto=array(
                '-1'=>'TODOS',
            );
            $RutDeudorFil=array(
                '-1'=>'TODOS',
            );
            if($resul){
               foreach($resul->result() as $row ){
                    $NomDeudorFil[$row->codigo]=$row->nombres." ".$row->apellidos;      
                    $NomDeudorFilContacto[$row->codigo]=$row->nombres." ".$row->apellidos." - ".$row->Rut;   
                    $RutDeudorFil[$row->codigo]=$row->Rut; 
                    $CiudadDeudorFil[$row->codigo]=$row->Region;
               }               
            }            
            $data['NomDeudorFilContacto']=$NomDeudorFilContacto;
            $data['NomDeudorFil']=$NomDeudorFil; 
            $data['RutDeudorFil']=$RutDeudorFil; 
            $data['CiudadDeudorFil']=$CiudadDeudorFil;
            
            //REGIONES
            $resul=$this->mod_nuevodeudor->RegionesChile();
             $RegChile = array(
             //'-1'=>'TODOS',
             );
            if($resul){
               foreach($resul->result() as $row ){
                    $RegChile[$row->codigo]=$row->NumRegion." - ".$row->NomRegion;       
               }
            }
            $data['RegChile']=$RegChile;    
            
            //BANCOS
            $resul=$this->mod_nuevodeudor->BancosChile();
             $BancosChile = array(
             //'-1'=>'TODOS',
             );
            if($resul){
               foreach($resul->result() as $row ){
                    $BancosChile[$row->codigo]=$row->NomBanco;       
               }
            }
            $data['BancosChile']=$BancosChile;  
            
            //TIPOS DE RUT
            
            $resul=$this->mod_nuevodeudor->TipoRut();
             $TipoRut = array(
             //'-1'=>'TODOS',
             );
            if($resul){
               foreach($resul->result() as $row ){
                    $TipoRut[$row->codigo]=$row->txtTipoRut;       
               }
            }
            $data['TipoRut']=$TipoRut;  
            
            //EMPRESAS DE COBRANZA
            $resul=$this->mod_nuevodeudor->EmpresaCobranzaSelect();
             $EmpresaCobranza = array(
                '-1'=>'- Seleccione una empresa -',
             );
            if($resul){
               foreach($resul->result() as $row ){
                    $EmpresaCobranza[$row->codigo]=$row->txtNomCliente;       
               }
            }
            $data['EmpresaCobranza']=$EmpresaCobranza; 
            
             $OpeEmpCob = array(
                '-1'=>'- Seleccione una empresa -',
             );
            $data['OpeEmpCob']=$OpeEmpCob; 
            //CARGA LA VISTA Y LE PASA LOS DATOS
             $this->load->view('view_usuario', $data);
            
        }
        
        //===============================================
        //                   DEUDORES
        //=============================================== 
        function agrega_deudor(){
            $datos_deudor =array(
                'codigo'=>$this->mod_nuevodeudor->max_codigo(),
                'nombres'=>$this->input->post("NomDeudor"),
                'apellidos'=>$this->input->post("ApeDeudor"),
                'direccion'=>$this->input->post("dir"),
                //'telefono'=>$this->input->post("telefono"),
                //'mail'=>$this->input->post("mail"),
                'Region'=>$this->input->post("RegChile"),
                'comuna'=>$this->input->post("comuna"),
                'tipopersona'=>$this->input->post('TPersona'),
                'Cliente'=>$this->session->userdata('codigo'),
                'Rut'=>$this->input->post("RutDeudor"),
                'Visible'=>"t",
            );         
            
            $datos_rut=array(
                'codigo'=>$this->mod_nuevodeudor->max_rut(),
                'RutAsociado'=>$this->input->post("RutDeudorMod"),
                'TipoRut'=>$this->input->post("TipoRut"),
            );
             
            $this->mod_nuevodeudor->ingresa($datos_deudor);
            $this->mod_nuevodeudor->agrega_Rut($datos_rut);
            $this->index();
            redirect("con_usuariovalido/index/PanelDeudores");
        }
        
        function carga_deudor($codigo){
            $row=$this->mod_nuevodeudor->busca_deudor($codigo);

            $data['cod']=$row->codigo;
            $data['NomDeudor']=$row->nombres;
            $data['ApeDeudor']=$row->apellidos;
            $data['RutDeudor']=$row->Rut;
            $data['dire']=$row->direccion;
            //$data['telef']=$row->telefono;
            //$data['mail']=$row->mail;
            $data['ciudad']=$row->Region;
            $data['com']=$row->comuna;
            if($row->tipopersona==1)
                $data['Tipo']="Natural";
            else
                $data['Tipo']="Juridica";
            $data['cliente']=$row->Cliente;
            $data['Tiporut']=$row->txtTipoRut;
            //$data['nombre']="leo";
            echo json_encode($data);
        }
        
        function modificar_deudor(){
            $cod_deudor=$this->input->post('CodDeudorMod');
            
            $datos_deudor =array(
                'nombres'=>$this->input->post("NomDeudorMod"),
                'apellidos'=>$this->input->post("ApeDeudorMod"),
                'Rut'=>$this->input->post("RutDeudorMod"),
                'direccion'=>$this->input->post("DirDeudorMod"),
                //'telefono'=>$this->input->post("TelDeudorMod"),
                //'mail'=>$this->input->post("MailDeudorMod"),
                'Region'=>$this->input->post("RegChile"),
                'comuna'=>$this->input->post("ComunaDeudorMod"),
            );
            
          //  $datos_rut=array(
//                'codigo'=>$this->mod_nuevodeudor->max_rut(),
//                'RutAsociado'=>$this->input->post("RutDeudorMod"),
//                'TipoRut'=>$this->input->post("TipoRut"),
//            );
            
            $this->mod_nuevodeudor->editar_deudor($cod_deudor,$datos_deudor);
            //$this->mod_nuevodeudor->agrega_Rut($datos_rut);
            redirect("con_usuariovalido/index/PanelDeudores");
        }
        
        function elimina_deudor($codigo){
            $this->mod_nuevodeudor->eliminar_deudor($codigo);
            redirect("con_usuariovalido/index/PanelDeudores");
        }
        
        
        //===============================================
        //                   CONTACTOS
        //=============================================== 
        function agregar_contacto(){
            
            $datos_contacto=array(
                'codigo'=>$this->mod_nuevodeudor->max_contacto(),
                'Deudor'=>$this->input->post("deudores"),
                'Cliente'=>$this->session->userdata('codigo'),
                'Nombre'=>$this->input->post("NomContacto"),
                'Direccion'=>$this->input->post("DirContacto"),
                'Telefono'=>$this->input->post("TelContacto"),
                'Mail'=>$this->input->post("MailContacto"),
                'Obser'=>$this->input->post("ObsContacto"),
                'Tipo'=>$this->input->post("TipoContacto"),
                'Cargo'=>$this->input->post("CargoContacto"),
                'Apellidos'=>$this->input->post("ApeContacto"),
                'rut'=>$this->input->post("RutContacto"),
                'Region'=>$this->input->post("RegChile"),
                'Visible'=>"t",
                'Vigencia'=>"Vigente",
            );
            
            //$datos_rut=array(
//                'codigo'=>$this->mod_nuevodeudor->max_rut(),
//                'RutAsociado'=>$this->input->post("RutContacto"),
//                'TipoRut'=>$this->input->post("TipoRut"),
//            );
            
            $this->mod_nuevodeudor->agrega_contacto($datos_contacto);
            //$this->mod_nuevodeudor->agrega_Rut($datos_rut);
            redirect("con_usuariovalido/index/PanelContactos");
        }
        
       function carga_contacto($codigo){
            $row=$this->mod_nuevodeudor->busca_contacto($codigo);
            $data['cod']=$row->codigo;
            $data['deudor']=$row->nombres." ".$row->apellidos;
            if($row->Tipo==1)
                $data['tipo']="Regular";
            else 
                $data['tipo']="Cobranza";
            $data['nom']=$row->Nombre;
            $data['dire']=$row->Direccion;
            $data['telef']=$row->Telefono;
            $data['mail']=$row->Mail;
            $data['obs']=$row->Obser;
            $data['cargo']=$row->Cargo;
            $data['apellido']=$row->Apellidos;
            $data['rut']=$row->rut;
            $data['reg']=$row->NumRegion." - ".$row->NomRegion;
            $data['Tiporut']=$row->txtTipoRut;
            $data['Vigencia']=$row->Vigencia;
            
            echo json_encode($data);
       }
       
        function ModificaContacto(){
            $CodContacto=$this->input->post('codContacto');
            
            $DatosContacto =array(
                'Nombre'=>$this->input->post("nomContacto"),
                'Apellidos'=>$this->input->post("apellContacto"),
                'rut'=>$this->input->post("rutContacto"),
                'Cargo'=>$this->input->post("CargoContactoMod"),               
                'Mail'=>$this->input->post("mailContacto"),
                'Telefono'=>$this->input->post("telContacto"),
                'Direccion'=>$this->input->post("dirContacto"),
                'Obser'=>$this->input->post("obserContacto"),
                'Vigencia'=>$this->input->post("flagVigencia"),
            );       
            
           // if($this->input->post("chkContactoVigente")==true){
//                echo "vigente";
//            }
//            if($this->input->post('chkContactoNoVigente')==true){
//                echo "no vigente";
//            }
            
            //echo $this->input->post("flagVigencia");
            
//            if($this->input->post("chkContactoNoVigente")==true){
//                echo "no vigente";
//            }
            
            $this->mod_nuevodeudor->EditarContacto($CodContacto,$DatosContacto);
            redirect("con_usuariovalido/index/PanelContactos");
        }
        
        function elimina_contacto($codigo){
            $this->mod_nuevodeudor->elimina_contacto($codigo);
            redirect("con_usuariovalido/index/PanelContactos");
        }
        
        //===============================================
        //                   DOCUMENTOS
        //=============================================== 
        function agrega_doc(){
           
            $dia=date("Y-m-d");//PODEMOS DESCOMPONER LA FECHA CON DATE("D") PARA EL DIA Y DE LA MISMA FORMA MES Y AÑO
            
            $codigo=$this->session->userdata('codigo');  
            $cod_doc=$this->mod_nuevodeudor->max_doc();
            $cod_deuda=$this->mod_nuevodeudor->max_dueda();
            
            //cambia fecha vencimiento
            //$fecha = "04/30/1973";
            list($diavence, $mes, $año) =  explode("/", $this->input->post("FechaVence"));
            
            //echo $this->input->post("FechaVence");
//            echo "--dia"+$diavence;
//            echo "mes"+$mes;
//            echo "año"+$año;
//            
//            echo "ingreso->".$año."-".$mes."-".$diavence;
            
            $datos_doc=array(
                'Codigo'=>$cod_doc,
                'Monto'=>$this->input->post("Precio"),
                'Tipo'=>$this->input->post("TipoDocumento"),
                'NumDocumento'=>$this->input->post("NumDocumento"),
                'Observacion'=>$this->input->post("observacion"),
                'Accion'=>$this->input->post("TipoAccion"),
                'ProxGestion'=>$this->input->post("ProxGes1"),
                'ConPago'=>$año."-".$mes."-".$diavence,
                'FechaEmision'=>$dia, 
                'Visible'=>"t",       
                'Derivado'=>"f", 
                'Remanente'=>"0",      
            );  
            
           $datos_hist=array(
                'codigo'=>$this->mod_nuevodeudor->max_histdoc(),
                'CodDoc'=>$cod_doc,
                'FechaCambio'=>$dia,                
                'AccionPersona'=>"Agrega documento / ".$this->input->post("observacion"),
                'Persona'=>$codigo
           ); 
            
            if($this->input->post("NomDeudorHidden")==""){
                $datos_deuda=array(
                    'codigo'=>$cod_deuda,
                    'Documento'=>$cod_doc,
                    'Deudor'=>$this->input->post("deudores"),
                    'Cliente'=>$codigo,
                    'Estado'=>0,
                );
            }else{
                $datos_deuda=array(
                    'codigo'=>$cod_deuda,
                    'Documento'=>$cod_doc,
                    'Deudor'=>$this->mod_nuevodeudor->max_codigo(),
                    'Cliente'=>$codigo,
                    'Estado'=>0,
                );  
            }
            
            if ($this->input->post("NomDeudorHidden")!=""){
                $datos_NuevoDeudor=array(
                    'codigo'=>$this->mod_nuevodeudor->max_codigo(),
                    'nombres'=>$this->input->post("NomDeudorHidden"),
                    'apellidos'=>$this->input->post("ApellDuedorHidden"),
                    'direccion'=>$this->input->post("DirDeudorHidden"),
                    //'telefono'=>$this->input->post("TelDeudorHidden"),
                    //'mail'=>$this->input->post("MailDeudorHidden"),
                    'Region'=>$this->input->post("CiuDeudorHidden"),
                    'comuna'=>$this->input->post("ComDeudorHidden"),
                    'tipopersona'=>$this->input->post("TDeudorHidden"),
                    'Cliente'=>$this->session->userdata('codigo'),
                    'Rut'=>$this->input->post("IdTribDeudorHidden"),
                    'Visible'=>"t"
                );
                
                $datos_rut=array(
                    'codigo'=>$this->mod_nuevodeudor->max_rut(),
                    'RutAsociado'=>$this->input->post("IdTribDeudorHidden"),
                    'TipoRut'=>$this->input->post("TipoTribDeudorHidden"),
                );
           
                $this->mod_nuevodeudor->agrega_Rut($datos_rut);
                $this->mod_nuevodeudor->ingresa($datos_NuevoDeudor);
            }
            
            $this->mod_nuevodeudor->guarda_doc($datos_doc,$datos_deuda);
            
            if($this->input->post("TipoAccion")==3){
                $cod_recauda=$this->mod_nuevodeudor->SELSeqRecaudacion();
                
                $datos_recauda=array(
                    'codigo'=>$cod_recauda,
                    'CodDoc'=>$cod_doc,
                    'Lugar'=>$this->input->post("LugRecauda"),
                    'Contacto'=>$this->input->post("ConRecauda"),
                    'Hora'=>$this->input->post("HrRecauda"),
                    'TipoDocPago'=>$this->input->post("FormaPago"),
                );
                $this->mod_nuevodeudor->Recaudacion($datos_recauda);
            }
            $this->mod_nuevodeudor->GuardaHistorial($datos_hist);
            redirect("con_usuariovalido/index");
        }
        
        function edita_accion($codigo){
            //echo '->'.$codigo;
            $row=$this->mod_nuevodeudor->carga_doc($codigo);
            
            //toma los historiales de documentos
            $data['datos_historial']=$this->mod_nuevodeudor->HistorialDocs($codigo);
            
            //RESCATA LOS ABONOS DEL DOCUMENTO
            //$MontoAbono=$this->mod_nuevodeudor->MontoAbonoDOC($co);
            
            $data['Historial_abonos']=$this->mod_nuevodeudor->HistorialAbonos($codigo);
            
            $SumaAbonos=$this->mod_nuevodeudor->TotalAbonos($codigo);
            
            //echo $SumaAbonos;
            //echo $SumaAbonos[0];
            //echo $SumaAbonos[0]->SumaAbono;
            
            $data['TotalAbonos']=$SumaAbonos;
            
                        
            //$data['TotalAbonos']=$row2->a;
            //$data['TotalAbonos']=$this->mod_nuevodeudor->TotalAbonos($codigo);
            
            //$data['TotalAbonos']=$row->TotalAbono;
               
                    
            $data['coddoc']=$row->Codigo;
            $data['cod']=$row->NumDocumento;        
            $data['accion']=$row->NomAccion;
            $data['deudor']=$row->nombres." ".$row->apellidos;
            if($row->Tipo==0){
                                $data['tipodoc']="Facturas";
                            }else{
                                if($row->Tipo==1){
                                    $data['tipodoc']="Cheques";
                                }else{
                                    if($row->Tipo==2){
                                        $data['tipodoc']="Letras";
                                    }else{
                                        $data['tipodoc']="NC";
                                    }
                                }
                            }
            
            $data['conpago']=$row->ConPago;
            list($año,$mes,$dia) = explode("-",$row->ProxGestion);            
            $fecha=$dia.'/'.$mes.'/'.$año;
            $data['pgestion']=$fecha;
            $data['precio']=$row->Monto;
            $data['obs']=$row->Observacion;
            $data['nomDeuda']=$row->txtNomEstadoDeuda;
            
            $data['derivado']=$row->Derivado;
            
            if($this->mod_nuevodeudor->VerInfoComer($codigo)){
                $data['infocomer']="Existe";
            }else{
                $data['infocomer']="NoExiste";
                
            }
            //echo $row->CodDoc;
            echo json_encode($data);
        }
        
        function modifica_doc(){
           $cod=$this->input->post("CodigoDocumento");
           $fecha_cambio=date("Y-m-d");
           
           $comentarioHistorial="Modifica documento - ".$this->input->post("observacion_doc");
           
           //ve si se selecciono una nueva accion
           if($this->input->post("TipoAccionGestiona")=="-1"){
                $resul=$this->mod_nuevodeudor->CodigoAccion($this->input->post("accion_doc"));
                foreach ($resul->result() as $row){                     
                    $codAccion= $row->codigo;                            
                }
            
                $DOCaccion=$codAccion;
           }else{
                $DOCaccion=$this->input->post("TipoAccionGestiona");
           }
           
           //   VE SI EXISTE UN NUEVO ABONO Y ESCRIBE EN LA BASE DE DATOS
           if($this->input->post('NuevoAbonoOculto')!=""){
            
                $MaxIdAbono=$this->mod_nuevodeudor->SELMaxIdAbono();
                $OBJAbono=array(
                    'codigo'=>$MaxIdAbono,
                    'MontoAbono'=>$this->input->post('NuevoAbonoOculto'),
                      'FechaAbono'=>$fecha_cambio,
                      'Notas'=>"Falta definicion - CG",
                      'Usuario'=>$this->session->userdata('codigo'),
                      'FechaRegistro'=>$fecha_cambio,
                      'SeqDocumento'=>$cod,
                );                
                $this->mod_nuevodeudor->INSNuevoAbono($OBJAbono);
           }
              
              ////VE SI EL DOCUMENTO ES DERIVADO A UNA EMPRESA DE COBRANZA
//              if($this->input->post("OpeEmpCob")!='-1'){
//                  //$comentarioHistorial="DOCUMENTO DERIVADO A CobroGestion Cod->".$this->input->post("EmpresaCobranza");
//                  $comentarioHistorial="DERIVA DOCUMENTO A CobroGestion.";
//              }
//              
              //VE SI EXISTE UNA NUEVA FECHA O CONSERVA LA ANTERIOR
              if($this->input->post("FechaNueva")==""){
                    $prox_gestion=$this->input->post("pgest_doc");
              }else{
                    list($dia,$mes,$año) = explode("/", $this->input->post('FechaNueva'));            
                    $fecha=$año.'-'.$dia.'-'.$mes;       
                    $prox_gestion=$fecha;
              }
              
              //INFORMES COMERCIALES               
              if($this->input->post("PublicarInfoComer")==true){
                    $comentarioHistorial="Modifica documento - Publicado en informes comerciales - ".$this->input->post("observacion_doc");
                    $datos_InfComer=array(
                      'codigo'=>$this->mod_nuevodeudor->MaxInformeComercial(),
                      'CodDoc'=>$cod,
                    );
                    $this->mod_nuevodeudor->InsertInfoComercial($datos_InfComer);
              }
              if($this->input->post("PublicarInfoComer")==false and $this->input->post("EstadoPublicaInfoComer")==false and $this->input->post("FlagInformeComercial")!="VACIO"){
                
                    $comentarioHistorial="Modifica documento - Despublicado de informes comerciales - ".$this->input->post("observacion_doc");
                    $this->mod_nuevodeudor->SacaInformeComercial($cod);
              }
              
              $limpiaRem=str_replace("$ ", "",$this->input->post("MontoRemanente"));
              
              $datos_doc=array(
                  'Observacion'=>$this->input->post("observacion_doc"),
                  'ProxGestion'=>$prox_gestion,
                  'ConPago'=>$this->input->post('ConPago_doc'),
                  'FechaEmision'=>$fecha_cambio,
                  'Accion'=>$DOCaccion,
                  'Remanente'=>str_replace(".", "",$limpiaRem),
              );
              
             // echo $this->input->post("MontoRemanente");
//              echo $limpiaRem;
//              echo str_replace($limpiaRem, ".", "");
//              
             //SI LA ACCION ES RECAUDAR, RESCATA LOS DATOS
             if($this->input->post("TipoAccionGestiona")==3){
                  $cod_deuda=$this->mod_nuevodeudor->CodigoDeuda($cod);
                  $SeqRecaudacion=$this->mod_nuevodeudor->SELSeqRecaudacion();
                  
                  $OBJRecaudacion=array(
                      'codigo'=>$SeqRecaudacion,
                      'SeqDocumento'=>$cod,
                      'Direccion'=>$this->input->post("LugRecaudaMod"),
                      'NombreContacto'=>$this->input->post("ConRecaudaMod"),
                      'Hora'=>$this->input->post("HrRecaudaMod"),
                      'SeqDocumentoPago'=>$this->input->post("FormaPagoMod"),
                      'SeqBancoOrigen'=>$this->input->post("num1Mod"),
                      'Dato2'=>$this->input->post("num2Mod"),
                      'Dato3'=>$this->input->post("num3Mod")
                  );                                
                  
                  
                  $this->mod_nuevodeudor->INSRecaudacion($OBJRecaudacion,$cod);
              }
              
              //si cambia el estado
              if($this->input->post("EstadoDeuda")!=0){
                $datos_deuda=array(
                      'Estado'=>$this->input->post("EstadoDeuda"),
                      
                  );
                  $this->mod_nuevodeudor->editar_accion($datos_doc,$cod,$datos_deuda);
              }else{
                $this->mod_nuevodeudor->editar_accion2($datos_doc,$cod);
              }
              
              $datos_hist=array(
                  'codigo'=>$this->mod_nuevodeudor->max_histdoc(),
                  'CodDoc'=>$cod,
                  'FechaCambio'=>$fecha_cambio,                  
                  'AccionPersona'=>$comentarioHistorial,
                  'Persona'=>$this->session->userdata('codigo')
              ); 
              
              //echo $fecha_cambio;
              
              //echo $this->input->post("EstadoDeuda");
              //echo $comentarioHistorial;
             
             $this->mod_nuevodeudor->GuardaHistorial($datos_hist);
             
             redirect("con_usuariovalido/index");
        }
        
        function elimina_doc($codigo){
            $this->mod_nuevodeudor->elimina_accion($codigo);
            redirect("con_usuariovalido/index");
        }
        
        //===============================================
        //                   OPERADOR
        //===============================================       
        function agregar_operador(){
            $cod_usuario=$this->mod_nuevodeudor->max_usuario();

            $datos_operador=array(
                'codigo' => $this->mod_nuevodeudor->MaxOperador(),
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
                    'codigo'=>$this->mod_nuevodeudor->max_rut(),
                    'RutAsociado'=>$this->input->post("rutOperador"),
                    'TipoRut'=>$this->input->post("TipoRut"),
            );
            
            $this->mod_nuevodeudor->agrega_Rut($datos_rut);
            $this->mod_nuevodeudor->agrega_operador($datos_operador);
            redirect("con_usuariovalido/index/PanelOperador");
            
        }
        
        function carga_operador($codigo){
            //echo $codigo;
            $row=$this->mod_nuevodeudor->busca_operador($codigo);

            $data['OperaCod']=$row->codigo;
            $data['NomOpe']=$row->txtNomOperador;
            $data['rutOperador']=$row->rutOperador;
            $data['OperaRegion']=$row->NomRegion;
            $data['OperaDir']=$row->txtDirOperador;
            $data['OperaTel']=$row->txtTelOperador;
            //$data['']=$row->seqCliente;
            //$data['']=$row->seqTipoOperador;
            $data['OperaUsu']=$row->txtUsuOperador;
            $data['OperaClave']=$row->txtClaveOperador; 

            echo json_encode($data);
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
            
            $this->mod_nuevodeudor->ModificaOperador($cod_operador,$datos_operador);
            redirect("con_usuariovalido/index/PanelOperador");
        }
        
        function elimina_operador($codigo){
            $this->mod_nuevodeudor->elimina_operador($codigo);
            redirect("con_usuariovalido/index/PanelOperador");
        }

        
        function filtro_accion(){
            $hasta=$this->input->post("fecha_hasta");
            list($dia,$mes,$año) = explode("/", $hasta);
            $data['accion_cobranza']=$this->mod_nuevodeudor->fecha_hasta($dia,$mes,$año);
            
            $this->load->view('usuario',$data);
        }
         
        
         
        function EditaDocs(){
            $cod_historial=$this->mod_nuevodeudor->max_histdoc();
            $cod_recauda=$this->mod_nuevodeudor->SELSeqRecaudacion();
             
            $acciones=$this->input->post('modal_acciones');
            $array_acciones = explode(",", $acciones);
           
            $datos_doc =array(
                'Accion'=>$this->input->post("TipoAccion"),
                'Observacion'=>$this->input->post("comen"),
                'ProxGestion'=>$this->input->post("ProxGestionMulti"),
            );    
            
            foreach ($array_acciones as $valor){
                if($this->input->post("TipoAccion")==3){
                    //$cod_deuda=$this->mod_nuevodeudor->CodigoDeuda($valor);
                    $datos_recauda=array(
                          'codigo'=>$cod_recauda,
                          'CodDoc'=>$valor,
                          'Lugar'=>$this->input->post("LugRecaudaMulti"),
                          'Contacto'=>$this->input->post("ConRecaudaMulti"),
                          'Hora'=>$this->input->post("HrRecaudaMulti"),
                          'TipoDocPago'=>$this->input->post("FormaPagoMulti"),
                      );
                      echo $this->input->post("HrRecaudaMulti");
                      echo $this->input->post("num1Multi");
                      $this->mod_nuevodeudor->RecaudaSelectMulti($datos_recauda);
                      $cod_recauda+=1;
                } 
                
                
                if($this->input->post("EstadoPublicaInfoComerMod")==true){
                    $datos_InfComer=array(
                          'codigo'=>$this->mod_nuevodeudor->MaxInformeComercial(),
                          'CodDoc'=>$valor,
                    );
                    $this->mod_nuevodeudor->InsertInfoComercial($datos_InfComer);
                }
                
                //TODO: rescatar los datos que faltan para el update del historial
               // $TipoDoc=$this->mod_nuevodeudor->TipoDoc($valor);
//                echo "---->".$TipoDoc;
//                
                //foreach($resul->result() as $row ){
//                    $datos_hist=array(
//                      'codigo'=>$cod_historial,
//                      'CodDoc'=>$valor,
//                      'Tipo'=>$resul->Nombre,
//                      'Observacion'=>$this->input->post("comen"),                  
//                      'ProxGestion'=>$this->input->post("ProxGestionMulti"),
//                      'ConPago'=>$this->input->post("ConPago_doc"),
//                      'FechaCambio'=>date("d/m/Y"),
//                      'Accion'=>$this->input->post("TipoAccion"),
//                      'Monto'=>$this->input->post("Precio_doc"),
//                      'AccionPersona'=>"GESTIONA DOCUMENTO",
//                      'Persona'=>$this->session->userdata('codigo'),
//                ); 
//                }  
                //$tipo=$this->mod_nuevodeudor->TipoDoc($valor);
                //echo "DOcumento".$valor."---------------TipoDoc".$datos_hist['Tipo'];
                                 
                $this->mod_nuevodeudor->EdicionMasiva($datos_doc,$valor);
            } 
            
            //echo $this->input->post('TipoAccionMulti');
//            echo $this->input->post('TipoAccion');
            redirect('con_usuariovalido');
            }

        function FiltroOperador(){
            
            $CodNombre=$this->input->post("NombreOperador");
            $CodUsuario=$this->input->post("UsuarioOperador");
                        
            $resul=$this->mod_nuevodeudor->FiltroOperadores($CodNombre,$CodUsuario);
            
            foreach ($resul->result() as $row){                     
                            echo $row->Nombre." ".$row->Apellidos;
                            echo $row->nombre;
                            echo $row->Direccion;
                            echo $row->Telefono;
                            echo $row->Mail;
                            
            }
            //$num_result=$resul->num_rows();
            //echo $num_result;
            $data['Operadores']=$resul;
//            
////            for ($i=0; $i<$num_results; $i++) {
////                $row = $result->fetchRow(MDB2_FETCH_ASSOC);
////                echo '<img src="'.$row['Image'].'>';
////                echo "<br/>" . "Price: " . stripslashes($row['Price']);
            //$this->load->view('index.php/con_usuariovalido/index/PanelOperador',$data);
            redirect("con_usuariovalido/index/PanelOperador");
            //$this->view_all($data);
        }
        
        function DerivarDocumento(){
            $CodNuevaDerivacion=$this->mod_nuevodeudor->MaxDerivacion();
            if($this->input->post("DerivarCodigoDoc")){
                $codDocumento=$this->input->post("DerivarCodigoDoc");
                
                $DatosDerivacion=array(
                    'codigo'=>$CodNuevaDerivacion,
                    'codDocumento'=>$codDocumento,
                    'codEmpresaCobranza'=>$this->input->post("EmpresaCobranza"),
                    //'codOperadorCobranza'=>$this->input->post("OpeEmpCob"),
                );
                
                $this->mod_nuevodeudor->DerivaDocumento($DatosDerivacion,$codDocumento);
                
                 //registra en el historial el cambio a documentos derivados
                 $datos_hist=array(
                      'codigo'=>$this->mod_nuevodeudor->max_histdoc(),
                      'CodDoc'=>$codDocumento,
                      'FechaCambio'=>date("Y-m-d"),                  
                      'AccionPersona'=>"Documento derivado a empresa de cobranza",
                      'Persona'=>$this->session->userdata('codigo')
                  ); 
                  $this->mod_nuevodeudor->GuardaHistorial($datos_hist);
            }
            if($this->input->post("DocDerivaMasivo")){
                $acciones=$this->input->post('DocDerivaMasivo');
                $array_acciones = explode(",", $acciones);
                   
                foreach($array_acciones as $valor)    {     
                    $DatosDerivacion=array(
                        'codigo'=>$CodNuevaDerivacion,
                        'codDocumento'=>$valor,
                        'codEmpresaCobranza'=>$this->input->post("EmpresaCobranza"),
                        //'codOperadorCobranza'=>$this->input->post("OpeEmpCob"),
                    );
                    
                    $this->mod_nuevodeudor->DerivaDocumento($DatosDerivacion,$valor);
                    
                     //registra en el historial el cambio a documentos derivados
                     $datos_hist=array(
                          'codigo'=>$this->mod_nuevodeudor->max_histdoc(),
                          'CodDoc'=>$valor,
                          'FechaCambio'=>date("Y-m-d"),                  
                          'AccionPersona'=>"Documento derivado a empresa de cobranza",
                          'Persona'=>$this->session->userdata('codigo')
                      ); 
                      $this->mod_nuevodeudor->GuardaHistorial($datos_hist);
                }
            }
            
            redirect("con_usuariovalido/index");
        }
            
        function InformeComercialMasivo(){
            
            $acciones=$this->input->post('DocInformesComerciales');
            $array_acciones = explode(",", $acciones);
            
            $fecha_cambio=date("Y-m-d");
           
            foreach($array_acciones as $valor){
               //los publica en informes comerciales 
               $datos_InfComer=array(
                          'codigo'=>$this->mod_nuevodeudor->MaxInformeComercial(),
                          'CodDoc'=>$valor,
               );
               $this->mod_nuevodeudor->InsertInfoComercial($datos_InfComer);
               //registra en el historial el cambio a informes comerciales
               $datos_hist=array(
                  'codigo'=>$this->mod_nuevodeudor->max_histdoc(),
                  'CodDoc'=>$valor,
                  'FechaCambio'=>$fecha_cambio,                  
                  'AccionPersona'=>"Publicado en informes comerciales",
                  'Persona'=>$this->session->userdata('codigo')
              ); 
              $this->mod_nuevodeudor->GuardaHistorial($datos_hist);
            }
            redirect("con_usuariovalido/index");
        }
        
            
        function Prueba(){
            //echo $_POST['elegido'];
            $codEstado=$this->input->post('Codigo');
            //TOMA LOS TIPOS DE CONTACTOS Y EL CODIGO PARA LOS COMBOBOX
           
            $resul=$this->mod_nuevodeudor->AccionesRelacion($codEstado);
            
            $NuevasAcciones = array(
               // '-1'=>' - Seleccione una acción - ',
            );
                if($resul){
                   foreach($resul->result() as $row ){
                        $NuevasAcciones[$row->codAccion]=$row->NomAccion;       
                   }
               }
            $data['NuevasAcciones']=$NuevasAcciones;
            
            
            //$data['nombre']="Estado->".$codEstado;
            echo json_encode($data);
            //echo $dato;
            //echo $codigo;
        }
        
        function CargaEstados(){
            $resul=$this->mod_nuevodeudor->CodigoAccion($this->input->post('Codigo'));
            
            foreach ($resul->result() as $row){                     
                $codAccion= $row->codigo;                            
            }
//            
//            //echo $codAccion;
//            
            $resulado=$this->mod_nuevodeudor->EstadosRelacion($codAccion);
            
            $NuevosEstados = array(
                '0'=>' - Seleccione un estado - ',
            );
                if($resulado){
                   foreach($resulado->result() as $row ){
                        $NuevosEstados[$row->codEstado]=$row->txtNomEstadoDeuda;  
                   }
               }
            $data['NuevosEstados']=$NuevosEstados;



            //$data['nombre']=$codAccion;
            echo json_encode($data);
            
        }
        
        function AsignarOperador(){
            $seqDocumento=$this->input->post("CodDocumento");
            //$row=$this->mod_nuevodeudor->AsignarOperador($codigo);
            $datoDeuda=array(
                'Cliente'=>$this->input->post("NombreOperador"),
            );
            $this->mod_nuevodeudor->AsignarDocOperador($seqDocumento,$datoDeuda);
            redirect("con_usuariovalido/index");
        }
        
        function GetDatosRecaudacion(){
            //TRAE LOS DATOS DE RECAUDACION SEGUN ID DE DOCUMENTO
            $resulado=$this->mod_nuevodeudor->SELRecaudacion($this->input->post('Codigo'));
            
            foreach($resulado->result() as $row ){
                //$data['codigo']=$row->codigo;
                //$data['SeqDocumento']=$row->SeqDocumento;
                $data['Direccion']=$row->Direccion;
                $data['NombreContacto']=$row->NombreContacto;
                $data['Hora']=$row->Hora;
                $data['SeqDocumentoPago']=$row->DocumentoPago;
                $data['SeqBancoOrigen']=$row->NomBanco;
                $data['Dato2']=$row->Dato2;
                $data['Dato3']=$row->Dato3;
            }

            echo json_encode($data);
        }
        
        //TRAE LOS CONTACTOS ASOCIADOS A UN DEUDOR
        function GetDatosContactos(){
            //BUSCA EL ID DEL DEUDOR DEL DOCUMENTO
            $resul=$this->mod_nuevodeudor->SELSeqDeudor($this->input->post("Codigo"));
            
            foreach ($resul->result() as $row){                     
                $SeqDeudor= $row->Deudor;                            
            }
            
            //TOMA LOS DATOS DE LOS CONTACTOS
            $data['DatosContacto']=$this->mod_nuevodeudor->SELDatosContacto($SeqDeudor);
            
            
            $data['prueba']=$SeqDeudor;
            echo json_encode($data);
        }
        
        //
        //function UPDVigenciaOperador($seqContacto){
//            $DatosContacto=array(
//                'Vigencia'=>"No Vigente",
//            );
//            
//            $this->mod_nuevodeudor->UPDContactoNoVigente($seqContacto,$DatosContacto);
//            redirect("con_usuariovalido/index/PanelContactos");
//            
//        } 
        
    }
?>