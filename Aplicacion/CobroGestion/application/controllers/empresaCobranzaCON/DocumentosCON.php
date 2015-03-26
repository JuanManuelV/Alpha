<?php 
     class DocumentosCON extends CI_Controller{
        
        public function __construct(){
            parent::__construct();
            $this->load->helper('form');
            $this->load->helper('array');
            $this->load->library('form_validation');
            $this->load->model('empresaCobranzaMOD/DocumentosMOD');
            
        }
        function index(){
            
        }
        
        function CargaDocumento($seqDocumento){
            
            
            $row=$this->DocumentosMOD->CargaDocumento($seqDocumento);

            $data['datos_historial']=$this->DocumentosMOD->HistorialDocs($seqDocumento);
            
            $data['Historial_abonos']=$this->DocumentosMOD->HistorialAbonos($seqDocumento);
            
            $SumaAbonos=$this->DocumentosMOD->TotalAbonosEmp($seqDocumento);
            
            //echo      $SumaAbonos;
            $data['TotalAbonos']=$SumaAbonos;
            
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
            $data['estadoActual']=$row->txtNomEstadoDeuda;
            $data['derivado']=$row->Derivado;
            
            if($this->DocumentosMOD->VerInfoComer($seqDocumento)){
                $data['infocomer']="Existe";
            }else{
                $data['infocomer']="NoExiste";
                
            }
            //echo $seqDocumento;
            
            //$data['codigo']=$seqDocumento;
            echo json_encode($data);
        }
        
        function EditaDocumento(){
            $cod=$this->input->post("CodigoDocumento");
           $fecha_cambio=date("Y-m-d");

           $comentarioHistorial="Modifica documento - ".$this->input->post("observacion_doc");
           
            //   VE SI EXISTE UN NUEVO ABONO Y ESCRIBE EN LA BASE DE DATOS
           if($this->input->post('NuevoAbonoOcultoEMP')!=""){
            
                $MaxIdAbono=$this->DocumentosMOD->SELMaxIdAbono();
                $OBJAbono=array(
                    'codigo'=>$MaxIdAbono,
                    'MontoAbono'=>$this->input->post('NuevoAbonoOcultoEMP'),
                      'FechaAbono'=>$fecha_cambio,
                      'Notas'=>"Falta definicion - CG",
                      'Usuario'=>$this->session->userdata('codigo'),
                      'FechaRegistro'=>$fecha_cambio,
                      'SeqDocumento'=>$cod,
                );                
                $this->DocumentosMOD->INSNuevoAbono($OBJAbono);
           }
           
           
           //ve si se selecciono una nueva accion
           if($this->input->post("TipoAccionGestiona")=="-1"){
                $resul=$this->DocumentosMOD->CodigoAccion($this->input->post("accion_doc"));
                foreach ($resul->result() as $row){                     
                    $codAccion= $row->codigo;                            
                }
            
                $DOCaccion=$codAccion;
           }else{
                $DOCaccion=$this->input->post("TipoAccionGestiona");
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
                      'codigo'=>$this->DocumentosMOD->MaxInformeComercial(),
                      'CodDoc'=>$cod,
                    );
                    $this->DocumentosMOD->InsertInfoComercial($datos_InfComer);
              }
              if($this->input->post("PublicarInfoComer")==false and $this->input->post("EstadoPublicaInfoComer")==false and $this->input->post("FlagInformeComercial")!="VACIO" ){
                
                    $comentarioHistorial="Modifica documento - Despublicado de informes comerciales - ".$this->input->post("observacion_doc");
                    $this->DocumentosMOD->SacaInformeComercial($cod);
              }
              
              $datos_doc=array(
                  'Observacion'=>$this->input->post("observacion_doc"),
                  'ProxGestion'=>$prox_gestion,
                  'ConPago'=>$this->input->post('ConPago_doc'),
                  'FechaEmision'=>$fecha_cambio,
                  'Accion'=>$DOCaccion,
              );              
              
             //SI LA ACCION ES RECAUDAR, RESCATA LOS DATOS
            if($this->input->post("TipoAccionGestiona")==3){
                  $cod_deuda=$this->DocumentosMOD->CodigoDeuda($cod);
                  $cod_recauda=$this->DocumentosMOD->SELSeqRecaudacion();
                  
                  $datos_recauda=array(
                      'codigo'=>$cod_recauda,
                      'CodDoc'=>$cod,
                      'Lugar'=>$this->input->post("LugRecaudaMod"),
                      'Contacto'=>$this->input->post("ConRecaudaMod"),
                      'Hora'=>$this->input->post("HrRecaudaMod"),
                      'TipoDocPago'=>$this->input->post("FormaPagoMod")
                  );
                  
                  //$this->mod_nuevodeudor->Recaudacion($datos_recauda);
              }
              
              //si cambia el estado
              if($this->input->post("EstadoDeuda")!=-1){
                $datos_deuda=array(
                      'Estado'=>$this->input->post("EstadoDeuda"),
                  );
                  $this->DocumentosMOD->editar_accion($datos_doc,$cod,$datos_deuda);
                  //echo $this->input->post("EstadoDeuda");
              }else{
                $this->DocumentosMOD->editar_accion2($datos_doc,$cod);
              }
              
              $datos_hist=array(
                  'codigo'=>$this->DocumentosMOD->max_histdoc(),
                  'CodDoc'=>$cod,
                  'FechaCambio'=>$fecha_cambio,                  
                  'AccionPersona'=>$comentarioHistorial,
                  'Persona'=>$this->session->userdata('codigo')
              ); 
             // echo $this->input->post("EstadoDeuda");
              
              //echo $this->input->post("EstadoDeuda");
              //echo $fecha_cambio;
              
              //echo $this->input->post("EstadoDeuda");
              //echo $comentarioHistorial;
             //$this->DocumentosMOD->editar_accion($datos_doc,$cod,$datos_deuda);
             $this->DocumentosMOD->GuardaHistorial($datos_hist);
          redirect('empresaCobranzaCON/EmpresaCobranzaCON');
          
          
          
          
          //  $cod=$this->input->post("CodigoDocumento");
//           $fecha_cambio=date("Y-m-d");
//              
//              //VE SI EL DOCUMENTO ES DERIVADO A UNA EMPRESA DE COBRANZA
//              if($this->input->post("OpeEmpCob")!='-1'){
//                  //$comentarioHistorial="DOCUMENTO DERIVADO A CobroGestion Cod->".$this->input->post("EmpresaCobranza");
//                  $comentarioHistorial="DERIVA DOCUMENTO A CobroGestion.";
//              }
//              
//              //VE SI EXISTE UNA NUEVA FECHA O CONSERVA LA ANTERIOR
//              if($this->input->post("FechaNueva")==""){
//                    $prox_gestion=$this->input->post("pgest_doc");
//              }else{
//                    list($dia,$mes,$año) = explode("/", $this->input->post('FechaNueva'));            
//                    $fecha=$año.'-'.$dia.'-'.$mes;       
//                    $prox_gestion=$fecha;
//              }
//              
//              //INFORMES COMERCIALES               
//              if($this->input->post("PublicarInfoComer")==true){
//                    $comentarioHistorial="PUBLICADO EN INFORMES COMERCIALES";
//                    $datos_InfComer=array(
//                      'codigo'=>$this->DocumentosMOD->MaxInformeComercial(),
//                      'CodDoc'=>$cod,
//                    );
//                    $this->DocumentosMOD->InsertInfoComercial($datos_InfComer);
//              }
//              if($this->input->post("PublicarInfoComer")==false and $this->input->post("EstadoPublicaInfoComer")==false){
//                
//                    $comentarioHistorial="DESPUBLICADO DE INFORMES COMERCIALES";
//                    $this->DocumentosMOD->SacaInformeComercial($cod);
//              }
//              
//              $datos_doc=array(
//                  'Observacion'=>$this->input->post("observacion_doc"),
//                  'ProxGestion'=>$prox_gestion,
//                  'ConPago'=>$this->input->post('ConPago_doc'),
//                  'FechaEmision'=>$fecha_cambio,
//                  'Accion'=>$this->input->post("TipoAccionGestiona"),
//              );
//              
//             //SI LA ACCION ES RECAUDAR, RESCATA LOS DATOS
//          //  if($this->input->post("TipoAccionGestiona")==3){
////                  $cod_deuda=$this->mod_nuevodeudor->CodigoDeuda($cod);
////                  $cod_recauda=$this->mod_nuevodeudor->max_recauda();
////                  
////                  $datos_recauda=array(
////                      'codigo'=>$cod_recauda,
////                      'CodDoc'=>$cod,
////                      'Lugar'=>$this->input->post("LugRecaudaMod"),
////                      'Contacto'=>$this->input->post("ConRecaudaMod"),
////                      'Hora'=>$this->input->post("HrRecaudaMod"),
////                      'TipoDocPago'=>$this->input->post("FormaPagoMod")
////                  );
////                  
////                  //$this->mod_nuevodeudor->Recaudacion($datos_recauda);
////              }
////              
//              $datos_hist=array(
//                  'codigo'=>$this->DocumentosMOD->max_histdoc(),
//                  'CodDoc'=>$cod,
//                  'FechaCambio'=>$fecha_cambio,                  
//                  'AccionPersona'=>$comentarioHistorial,
//                  'Persona'=>$this->session->userdata('codigo')
//              ); 
//              
//              //echo $fecha_cambio;
//              
//              //echo $comentarioHistorial;
//             $this->DocumentosMOD->editar_accion($datos_doc,$cod);
//             $this->DocumentosMOD->GuardaHistorial($datos_hist);
             
        }
        
        function DevuelveCliente(){
            $seqDocumento=$this->input->post("seqDocumento");
            $seqCliente=$this->session->userdata('codigo');
            
            $this->DocumentosMOD->DevolucionCliente($seqDocumento,$seqCliente);
            //echo "documento->".$seqDocumento."  cliente--->".$seqCliente;
            redirect('empresaCobranzaCON/EmpresaCobranzaCON');
        }
        
        function elimina_operador($codigo){
            $this->DocumentosMOD->elimina_operador($codigo);
            redirect("empresaCobranzaCON/EmpresaCobranzaCON");
        }
        
        function Prueba(){
            $codEstado=$this->input->post('Codigo');
            //TOMA LOS TIPOS DE CONTACTOS Y EL CODIGO PARA LOS COMBOBOX           
            $resul=$this->DocumentosMOD->AccionesRelacion($codEstado);

            if($resul){
                foreach($resul->result() as $row ){
                    $NuevasAcciones[$row->codAccion]=$row->NomAccion;       
                }
            }
            $data['NuevasAcciones']=$NuevasAcciones;
            
            echo json_encode($data);
        }
        
               
    }
?>