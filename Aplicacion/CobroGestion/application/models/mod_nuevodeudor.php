
<?php
    class mod_nuevodeudor extends CI_Model{
        
        function TipoPersona(){
            $query = $this->db->get('TipoPersona');
            return $query;
        }
        
        function TipoDocumento(){
            $query = $this->db->get('TipoDocumento');
            return $query;
        }
        
        function TipoRut(){
             $this->db->order_by('TipoRut.codigo','ASC');
            $query = $this->db->get('TipoRut');
            return $query;
        }
        function EmpresaCobranzaSelect(){
            $this->db->where('seqTipoCliente',2);
             //this->db->order_by('txtTipoRut.codigo','ASC');
            $query = $this->db->get('EmpresaCliente');
            return $query;
        }
        
         function RegionesChile(){
            $this->db->order_by('RegionChile.codigo','ASC');
            $query = $this->db->get('RegionChile');
            return $query;
        }
        function BancosChile(){
            //$this->db->order_by('BancoChile.codigo','ASC');
            $query = $this->db->get('BancoChile');
            return $query;
        }
        
        function TipoDocRecauda(){
            $query = $this->db->get('DocumentoPago');
            return $query;
        }
        
        function EstadoDeuda(){
            $this->db->order_by('EstadoDeuda.codigo','ASC');
            $query = $this->db->get('EstadoDeuda');
            //123123
            return $query;
        }
        
        function list_deudores($codigo){
            $this->db->where('Cliente',$codigo);
            $this->db->where('Visible',"t");
            $query = $this->db->get('deudor');
           // $query = $this->db->get('deudor');
            return $query;
        }
        function DeudoresFiltro($codigo){
            $this->db->where('Cliente',$codigo);
            $this->db->where('Visible',"t");
            $query = $this->db->get('deudor');
            return $query;
        }

    function max_rut(){
            $this->db->select_max('codigo');
            $query = $this->db->get('RutsUsuarios');
            if($query->row()->codigo){
              $codigo=$query->row()->codigo+1   ;
            }else{
                $codigo=1;
            }
            return $codigo;
        }

        function MaxInformeComercial(){
            $this->db->select_max('codigo');
            $query = $this->db->get('InformeComercial');
            if($query->row()->codigo){
              $codigo=$query->row()->codigo+1   ;
            }else{
                $codigo=1;
            }
            return $codigo;
        }

        function max_codigo(){
            $this->db->select_max('codigo');
            $query = $this->db->get('deudor');
            if($query->row()->codigo){
              $codigo=$query->row()->codigo+1   ;
            }else{
                $codigo=1;
            }
            return $codigo;
        }
        function InsertInfoComercial($datos) {
            $this->db->insert('InformeComercial', $datos);
        }
        
        
        function ingresa($datos) {
            $this->db->insert('deudor', $datos);
        }

        function carga_deudores($codigo){
            $this->db->where('Cliente',$codigo);
            $this->db->where('Visible',"t");
            $query = $this->db->get('deudor');
            return $query;
        }
        
        function carga_contactos($codigo){
            $this->db->select('Contacto.codigo,Contacto.Nombre,Contacto.Apellidos,Contacto.Direccion,Contacto.Telefono,Contacto.Mail,TipoContacto.NomTipoContacto,deudor.nombres,deudor.apellidos,Contacto.Vigencia');
            $this->db->from('Contacto');
            $this->db->join('TipoContacto','Contacto.Tipo=TipoContacto.codigo','inner');
            $this->db->join('deudor','Contacto.Deudor=deudor.codigo','inner');
            
            $this->db->where('Contacto.Cliente',$codigo);
            $this->db->where('Contacto.Visible',"t");
            $query = $this->db->get();
            return $query;
        }
        
        function editar_accion($datos,$cod,$datoDeuda){
            $this->db->where('Codigo',$cod);
            $this->db->update('Documento',$datos);
            
             $this->db->where('Documento',$cod);
            $this->db->update('Deuda',$datoDeuda);
           // $this->db->where('Documento',$cod);
           // $this->db->update('Deuda',$datoDeuda);
            
        }
         function editar_accion2($datos,$cod){
            $this->db->where('Codigo',$cod);
            $this->db->update('Documento',$datos);
            
             //$this->db->where('Documento',$cod);
            //$this->db->update('Deuda',$datoDeuda);
           // $this->db->where('Documento',$cod);
           // $this->db->update('Deuda',$datoDeuda);
            
        }
        
        function elimina_accion($cod){
            $dato = array(
                'Visible'=>'f',
             );
            $this->db->where('Codigo',$cod);
            $this->db->update('Documento',$dato);
            //$this->db->where('CodDoc',$cod);
//            $this->db->delete('HistorialDoc');
//            
//            $this->db->where('CodDoc',$cod);
//            $this->db->delete('Recauda');
//            
//            $this->db->where('Documento',$cod);
//            $this->db->delete('Deuda');
//            
//             $this->db->where('Codigo',$cod);
//            $this->db->delete('Documento');
            
        }
        
        function VerInfoComer($codigo){
            $this->db->where('CodDoc',$codigo);
             $this->db->limit(1);
            //$this->db->order_by('Documento.ProxGestion','ASC');
            
            $query = $this->db->get('InformeComercial');
            if($query ->num_rows()==1){
                  return $query->row();
              }else{
                  return false;  
              }
            
        }
        
        function SacaInformeComercial($cod){
            $this->db->where('CodDoc',$cod);
            $this->db->delete('InformeComercial');
            
        }
        
        
        function carga_doc($codigo){
            $this->db->where('Documento.Codigo',$codigo);
            //$this->db->where('Abono.SeqDocumento',$codigo);
            
            //$this->db->select_sum('Abono.MontoAbono', 'SumaAbono');
            $this->db->select('accion.NomAccion,deudor.nombres,deudor.apellidos,Documento.Codigo,Documento.Monto,Documento.Tipo,Documento.Observacion,Documento.ProxGestion,Documento.ConPago,Documento.FechaEmision, Documento.Accion,Documento.NumDocumento,Documento.Derivado,EstadoDeuda.txtNomEstadoDeuda,Documento.Remanente');
            
            $this->db->from('Documento');
            $this->db->join('accion','Documento.Accion=accion.codigo','inner');
            $this->db->join('Deuda','Documento.Codigo=Deuda.Documento','inner');
            $this->db->join('deudor','Deuda.Deudor=deudor.codigo','inner');
            $this->db->join('EstadoDeuda','Deuda.Estado=EstadoDeuda.codigo','inner');
            //$this->db->join('Abono','Documento.Codigo=Abono.SeqDocumento','inner');
            
            //  CALCULAR EL TOTAL DE ABONOS DEL DOCUMENT
            //$this->db->join('Abono','Documento.Codigo=Abono.SeqDocumento','inner');
            
            //$this->db->join('InformeComercial','Documento.Codigo=InformeComercial.CodDoc','inner');
            
            
             $this->db->limit(1);
            //$this->db->order_by('Documento.ProxGestion','ASC');
            
            $query = $this->db->get();
            if($query ->num_rows()==1){
                  return $query->row();
              }else{
                  return false;  
              }
        }
        
        function busca_deudor($codigo){
            $this->db->select('deudor.*,TipoRut.txtTipoRut');
            $this->db->from('deudor');
            

            $this->db->join('RutsUsuarios','deudor.Rut=RutsUsuarios.RutAsociado','inner');
            $this->db->join('TipoRut','RutsUsuarios.TipoRut=TipoRut.codigo','inner');
//            
            $this->db->where('deudor.Visible',"t");
            $this->db->where('deudor.codigo',$codigo);
            $query = $this->db->get();
            if($query ->num_rows()==1){
                return $query->row();
            }else{
                return false;  
            }
        }

        function BuscaTipoRut($Rut){
            $this->db->select('RutsUsuarios.RutAsociado,TipoRut.txtTipoRut');
            $this->db->from('RutsUsuarios');
            $this->db->join('TipoRut','RutsUsuarios.txtTipoRut=TipoRut.codigo','inner');
            //$this->db->join('RegionChile','Contacto.Region=RegionChile.codigo','inner');
            $this->db->where('RutsUsuarios.RutAsociado',$Rut);
            $this->db->limit(1);
            $query2 = $this->db->get();
            
            if($query2 ->num_rows()==1){
                return $query2->row();
            }else{
                return false;  
            }
        }

        function busca_contacto($codigo){
            $this->db->select('Contacto.rut,Contacto.Vigencia,Contacto.Cargo,Contacto.Obser,Contacto.codigo,Contacto.Nombre,Contacto.Apellidos,Contacto.Direccion,Contacto.Telefono,Contacto.Mail,Contacto.Deudor,Contacto.Tipo,deudor.nombres,deudor.apellidos,RegionChile.NomRegion,RegionChile.NumRegion,TipoRut.txtTipoRut');
            $this->db->from('Contacto');
            $this->db->join('deudor','Contacto.Deudor=deudor.codigo','inner');
            $this->db->join('RegionChile','Contacto.Region=RegionChile.codigo','inner');
            
            //sacar el tipo de rut
            $this->db->join('RutsUsuarios','Contacto.rut=RutsUsuarios.RutAsociado','inner');
            $this->db->join('TipoRut','RutsUsuarios.TipoRut=TipoRut.codigo','inner');
            
            $this->db->where('Contacto.Visible',"t");
            $this->db->where('Contacto.codigo',$codigo);
            $this->db->limit(1);
            $query = $this->db->get();
            
            if($query ->num_rows()==1){
                return $query->row();
            }else{
                return false;  
            }
        }
        function busca_contactoIMPLEMENTANDO($codigo){
            $this->db->select('cn.contacto,');
            $this->db->from('Contacto cn');
            //$this->db->join('usuarios','Operador.Usuario=usuarios.codigo','inner');
            $this->db->where('codigo',$codigo);
            $this->db->limit(1);
            $query = $this->db->get('Contacto');
            
            if($query ->num_rows()==1){
                return $query->row();
            }else{
                return false;  
            }
        }
        
        function editar_deudor($codigo,$datos_deudor){
            $this->db->where('codigo',$codigo);
            $this->db->update('deudor',$datos_deudor);
            
            //$this->db->insert('Historial',$hist);
        }
        
        function EditarContacto($Codigo,$DatosContacto){
            $this->db->where('codigo',$Codigo);
            $this->db->update('Contacto',$DatosContacto);
            
            //$this->db->insert('Historial',$hist);
        }
        
        function eliminar_deudor($codigo){
             $dato = array(
                'Visible'=>'f',
             );
             $this->db->where('deudor.codigo',$codigo);
            $this->db->update('deudor',$dato);
            //$resul=mysql_query("delete from Contacto where Deudor='".$codigo."'");
            //$this->db->where('Deudor',$codigo);
            //$this->db->update('Contacto.Visible',"f");
            //$this->db->delete('Contacto');
            
            
            //$this->db->where('codigo',$codigo);
            //$this->db->update('deudor.Visible',"f");
            
//            $this->db->insert('Historial',$hist);
        }
        
        
        function TipoAccion(){
            $this->db->where('accion.codigo <>',"4");
            $query = $this->db->get('accion');
            return $query;
        }
        function guarda_doc($doc,$deuda){
            $this->db->insert('Documento', $doc);
            $this->db->insert('Deuda',$deuda);
            
        }
        
        function guarda_docs($encabe,$det,$doc,$deuda){
            $this->db->insert('Deuda',$deuda);
            $this->db->insert('Documento', $doc);
            $this->db->insert('EncabezadoDoc', $encabe);
            $this->db->insert('DetalleDocumento', $det);
        }
        
        function max_doc(){
            $this->db->select_max('Codigo');
            $query = $this->db->get('Documento');
            if($query->row()->codigo){
              $codigo=$query->row()->codigo+1   ;
            }else{
                $codigo=1;
            }
            return $codigo;
        }
        
        function max_dueda(){
            $this->db->select_max('codigo');
            $query = $this->db->get('Deuda');
            if($query->row()->codigo){
              $codigo=$query->row()->codigo+1   ;
            }else{
                $codigo=1;
            }
            return $codigo;
        }

        //BUSCA LAS ACCIONES DE COBRANZA QUE SE DEBEN REALIZAR
        function AccionCobranza($codigo){
            $this->db->select('deudor.nombres,deudor.apellidos,deudor.codigo,accion.NomAccion,Documento.Monto,Documento.Tipo,Documento.ProxGestion, Documento.Accion, Deuda.Deudor,Deuda.Documento,Documento.FechaEmision,Documento.NumDocumento,EstadoDeuda.txtNomEstadoDeuda,accion.NomAccion,TipoDocumento.Nombre,TipoDocumento.codigo,Documento.Derivado,Documento.Remanente,Documento.ConPago');
            $this->db->from('deudor');
            $this->db->join('Deuda','deudor.codigo=Deuda.Deudor','inner');
            $this->db->join('Documento','Deuda.Documento=Documento.Codigo','inner');
            $this->db->join('accion','Documento.Accion=accion.codigo','inner');
            $this->db->join('EstadoDeuda','Deuda.Estado=EstadoDeuda.codigo','inner');
            $this->db->join('TipoDocumento','Documento.Tipo=TipoDocumento.codigo','inner');
            
            
            $this->db->where('Documento.Visible',"t");
            $this->db->where('Deuda.Cliente',$codigo);
           
            //PARA EL FILTRO HOY/ATRASADAS/FUTURAS
            if($this->input->post('TramoAccion') and $this->input->post('TramoAccion')!=-1){
                if($this->input->post('TramoAccion') == 1){
                    $this->db->where('Documento.ProxGestion =',date("d,m,Y"));
                }else{
                   if($this->input->post('TramoAccion') == 2){
                        $this->db->where('Documento.ProxGestion <=',date("d,m,Y"));
                   }else{
                        $this->db->where('Documento.ProxGestion >=',date("d,m,Y"));
                   }
               }
            }   
           
            //para el filtro por deudor
            if($this->input->post('DeudorFiltro') and $this->input->post('DeudorFiltro')!=-1){
                $this->db->where('Deuda.Deudor =',$this->input->post('DeudorFiltro'));
            }
//            
//            //para el filtro por accion a realizar               
            if($this->input->post('AccionFiltro') and $this->input->post('AccionFiltro')!=-1){
                $this->db->where('Documento.Accion =',$this->input->post('AccionFiltro'));
            }
//            
//            //para el filtro por estado de la deuda
            if($this->input->post('EstadoDeuda') and $this->input->post('EstadoDeuda')!=-1){
                $this->db->where('Deuda.Estado =',$this->input->post('EstadoDeuda'));
            }
            //$this->db->order_by('Documento.ProxGestion','ASC');
            
            
            //$this->db->join('OperadorCliente','Deuda.Cliente=OperadorCliente.codigo','inner');
            
            //PARA LAS FECHAS DESDE - HASTA
            if($this->input->post("fecha_hasta") and $this->input->post("fecha_desde")){
                $this->db->where('Documento.ProxGestion >=',$this->input->post("fecha_desde"));
                $this->db->where('Documento.ProxGestion <=',$this->input->post("fecha_hasta"));
            }else{
                $this->db->where('Documento.ProxGestion >',date("01/m/Y"));
                
                
                //echo date("01/m/Y");
            }
//            
            $query = $this->db->get();
            return $query;
//

            
        }
        function TipoAccionGestion(){
            $query=$this->db->get('accion');
            return $query;
        }
        //===============================================
        //                   CONTACTO
        //===============================================
        function TipoContacto(){
            $query=$this->db->get('TipoContacto');
            return $query;
        }
        
        function TramoAcciones(){
            $query=$this->db->get('TramoAccion');
            return $query;
        }
        
        function max_contacto(){
            $this->db->select_max('codigo');
            $query = $this->db->get('Contacto');
            if($query->row()->codigo){
              $codigo=$query->row()->codigo+1   ;
            }else{
                $codigo=1;
            }
            return $codigo;
        }
        
        function agrega_contacto($datos){
            $this->db->insert('Contacto',$datos);
        }
        
        function DescRut($idrut){
            $this->db->select('txtTipoRut');
            $this->db->from('TipoRut');
            $this->db->where('codigo',$idrut);
            $query=$this->db->get();
            
            return $query;   
        }
        
        function agrega_Rut($datos){
            $this->db->insert('RutsUsuarios',$datos);
        }
        
        function elimina_contacto($codigo){
            $dato = array(
                'Visible'=>'f',
             );
            $this->db->where('codigo',$codigo);
            $this->db->update('Contacto',$dato);
        }
        //===============================================
        //                   OPERADOR
        //=============================================== 
        function elimina_operador($codigo){
            $dato = array(
                'Visible'=>'f',
            );
            $this->db->where('codigo',$codigo);
            $this->db->update('OperadorCliente',$dato);
          //  $this->db->where('codigo',$codigo);
//            $this->db->delete('Operador');    
        }
        
        function agrega_operador($datos_op){
            $this->db->insert('OperadorCliente',$datos_op);
            //$this->db->insert('usuarios',$datos_usu);
        }
        
        function ingresa_deudore($datos){
            $this->db->insert('deudor',$datos);
        }
        //--------------------------------
        //      DATOS PARA LOS FILTROS
        //--------------------------------
        //OPERADORES
        function Operadores($codigo){
            $this->db->where('seqCliente',$codigo);
            $this->db->where('seqTipoOperador',3);
            $this->db->where('Visible',"t");
            $query=$this->db->get('OperadorCliente');
            
            return $query;   
        }
        //CONTACTOS
        //TODO: terminar filtro
        function Contactos($codigo){
            $this->db->where('Cliente',$codigo);
            $this->db->where('Contacto.Visible',"t");
            $query=$this->db->get('Contacto');
            
            return $query;  
        }
        
        function busca_operador($codigo){
            $this->db->select('OperadorCliente.*,RegionChile.NomRegion');
            $this->db->from('OperadorCliente');
            $this->db->join('RegionChile','OperadorCliente.seqRegionChile=RegionChile.codigo','inner');

            $this->db->where('OperadorCliente.codigo',$codigo);
            $query = $this->db->get();

            
            if($query ->num_rows()==1){
                return $query->row();
            }else{
                return false;  
            }
        }
        function ModificaOperador($codigo,$datos){
            $this->db->where('codigo',$codigo);
            $this->db->update('OperadorCliente',$datos);
        
        }
        
        function max_operador(){
            $this->db->select_max('codigo');
            $query = $this->db->get('Operador');
            if($query->row()->codigo){
              $codigo=$query->row()->codigo+1   ;
            }else{
                $codigo=1;
            }
            return $codigo;
        }
        
        function max_usuario(){  
            $this->db->select_max('codigo');
            $query = $this->db->get('usuarios');
            
            if($query->row()->codigo){
              return $query->row()->codigo+1;
            }else{
              return 1;
            }
           
        }
        
        function EdicionMasiva($data,$valor){
                $this->db->where('Codigo',$valor);
                $this->db->update('Documento',$data);
                
                //AGREGAR LA PARTE DEL HISTORIAL
        }
        
        
         function Recaudacion($datos){
            $this->db->insert('Recauda',$datos);
         }
         
         function RecaudaSelectMulti($datos){
           $this->db->insert('Recauda',$datos);
         }
         
         function GuardaHistorial($datos){
            $this->db->insert('HistorialDoc',$datos);
         }
         
        function max_histdoc(){
            $this->db->select_max('codigo');
            $query = $this->db->get('HistorialDoc');
            if($query->row()->codigo){
              $codigo=$query->row()->codigo+1   ;
            }else{
                $codigo=1;
            }
            return $codigo;
        }
        
        function CodigoDeuda($CodDoc){
       
            $this->db->select('codigo');
            //$this->db->from('Deuda');
            $this->db->where('Documento',$CodDoc);
            
            $query = $this->db->get('Deuda');
            return $query;
        }
        
        function HistorialDocs($CodDoc){
            $this->db->order_by('HistorialDoc.FechaCambio','DESC');
            $this->db->select('HistorialDoc.FechaCambio,HistorialDoc.AccionPersona,HistorialDoc.Persona,OperadorCliente.txtNomOperador');
            $this->db->from('HistorialDoc');
            $this->db->join('OperadorCliente','HistorialDoc.Persona=OperadorCliente.codigo','inner');
            $this->db->join('Documento','HistorialDoc.CodDoc=Documento.Codigo','inner');

            $this->db->where('HistorialDoc.CodDoc',$CodDoc);            
            $query = $this->db->get();                                  
            return $query->result();
        }
        
        
        function FiltroPanel($codigo){  
            $this->db->where('Cliente',$codigo);
            $query=$this->db->get('Operador');
            
            return $query;
        }
        
        function TipoDoc($codigo){
            $this->db->select('Tipo');
            $this->db->where('Codigo',$codigo);
            
            $CodTipo=$this->db->get('Documento');
            
            return $CodTipo;
        }
        
        
        function FiltroOperadores($CodNombre,$CodUsuario){
            $this->db->select('Operador.codigo,Operador.Nombre,Operador.Apellidos,Operador.Direccion,Operador.Telefono,Operador.Mail,Operador.Usuario');
            $this->db->from('Operador');
            //$this->db->join('usuarios','Operador.Usuario=usuarios.codigo','inner');
            
            if($CodNombre!='-1' and $CodUsuario!='-1'){
                $this->db->where('Operador.codigo',$CodNombre);     
                $this->db->where('Operador.codigo',$CodUsuario);
            }
            if($CodNombre!='-1' and $CodUsuario=='-1'){
                $this->db->where('Operador.codigo',$CodNombre);     
            }
            if($CodNombre=='-1' and $CodUsuario!='-1'){   
                $this->db->where('Operador.codigo',$CodUsuario);
            }
            
            $query = $this->db->get();
                                  
            return $query;
        }
        
        function FiltroDeudores($nombre,$rut,$ciudad){
            $ciudad='-1';
            if($nombre=='-1' and $rut!='-1' and $ciudad=='-1'){
                $this->db->where('codigo',$rut);
            }
            if($nombre=='-1' and $rut=='-1' and $ciudad!='-1'){
                $this->db->where('codigo',$ciudad);
            }
             if($nombre!='-1' and $rut=='-1' and $ciudad=='-1'){
                $this->db->where('codigo',$nombre);
            }
            
            if($nombre!='-1' and $rut!='-1' and $ciudad=='-1'){
                $this->db->where('codigo',$nombre);
                $this->db->where('codigo',$rut);
            }
           
            if($nombre=='-1' and $rut!='-1' and $ciudad!='-1'){
                $this->db->where('codigo',$ciudad);
                $this->db->where('codigo',$rut);
            }
            if($nombre!='-1' and $rut=='-1' and $ciudad!='-1'){
                $this->db->where('codigo',$ciudad);
                $this->db->where('codigo',$nombre);
            }
            if($nombre!='-1' and $rut!='-1' and $ciudad!='-1'){
                $this->db->where('codigo',$ciudad);
                $this->db->where('codigo',$nombre);
                $this->db->where('codigo',$rut);
            }
            
            $query = $this->db->get('deudor');
            return $query;
        }
        
        function FiltroContactos($Nombre,$tipo,$NomDeudorFil){
            $this->db->select('Contacto.codigo,Contacto.Nombre,Contacto.Apellidos,Contacto.Direccion,Contacto.Telefono,Contacto.Mail,TipoContacto.NomTipoContacto,deudor.nombres,deudor.apellidos,deudor.Rut');
            $this->db->from('Contacto');
            $this->db->join('TipoContacto','Contacto.Tipo=TipoContacto.codigo','inner');
            $this->db->join('deudor','Contacto.Deudor=deudor.codigo','inner');
            
            $this->db->where('Contacto.Visible',"t");
            $this->db->where('Contacto.Cliente',$this->session->userdata('codigo'));
            
            if($Nombre!='-1' and $tipo!='-1' and $NomDeudorFil!='-1'){
                $this->db->where('Contacto.codigo',$Nombre);
                $this->db->where('Contacto.Tipo',$tipo);
                $this->db->where('Contacto.Deudor',$NomDeudorFil);
                $query = $this->db->get();
            }else{
                //solo deudor
                 if($Nombre=='-1' and $tipo=='-1' and $NomDeudorFil!='-1'){
                    //$this->db->where('Contacto.Tipo',$tipo);
                    $this->db->where('Contacto.Deudor',$NomDeudorFil);
                    $query = $this->db->get();
                }
                //solo nombre
                 if($Nombre!='-1' and $tipo=='-1' and $NomDeudorFil=='-1'){
                    //$this->db->where('Contacto.Tipo',$tipo);
                    $this->db->where('Contacto.codigo',$Nombre);
                    //$this->db->where('Contacto.Deudor',$NomDeudorFil);
                    $query = $this->db->get();
                }
                //solo tipo
                 if($Nombre=='-1' and $tipo!='-1' and $NomDeudorFil=='-1'){
                    $this->db->where('Contacto.Tipo',$tipo);
                    //$this->db->where('Contacto.Deudor',$NomDeudorFil);
                    $query = $this->db->get();
                }
                //tipo y deudor
                if($Nombre=='-1' and $tipo!='-1' and $NomDeudorFil!='-1'){
                    $this->db->where('Contacto.Tipo',$tipo);
                    $this->db->where('Contacto.Deudor',$NomDeudorFil);
                    $query = $this->db->get();
                }
                //nombre y deudor
                if($Nombre!='-1' and $tipo=='-1' and $NomDeudorFil!='-1'){
                    $this->db->where('Contacto.codigo',$Nombre);
                    $this->db->where('Contacto.Deudor',$NomDeudorFil);
                    $query = $this->db->get();
                }
                //nombre y tipo
                if($Nombre!='-1' and $tipo!='-1' and $NomDeudorFil=='-1'){
                    $this->db->where('Contacto.codigo',$Nombre);
                    $this->db->where('Contacto.Tipo',$tipo);
                    $query = $this->db->get();
                }
            }
           
            return $query;
        }
        
        function Deudores($codigo){
            $this->db->where('Cliente',$codigo);
            $this->db->where('Visible',"t");
            $query=$this->db->get('deudor');
            
            return $query;  
        }
        
        function OperadorEmpCob($codigo){
            $this->db->where('codigo',$codigo);
            //$this->db->where('Visible',"t");
            $query=$this->db->get('OperadorEmpCob');
            //echo $codigo;
            return $query; 
            
        }
        
        
        
        //DERIVA DOCUMENTO
        function MaxDerivacion(){
            $this->db->select_max('codigo');
            $query = $this->db->get('DocumentosDerivados');
            if($query->row()->codigo){
              $codigo=$query->row()->codigo+1   ;
            }else{
                $codigo=1;
            }
            return $codigo;
        }
        
        function DerivaDocumento($datosDerivacion,$codDocumento){
            $dato = array(
                'Derivado'=>'t',
             );

            $this->db->where('Codigo',$codDocumento);
            $this->db->update('Documento',$dato);
                        
            $this->db->insert('DocumentosDerivados',$datosDerivacion);

        }
        
        function AccionesRelacion($codigo){
            $this->db->select('RelEstadoAccion.codAccion,accion.NomAccion');
            $this->db->from('RelEstadoAccion');
            $this->db->join('accion','RelEstadoAccion.codAccion=accion.codigo','inner');

            $this->db->where('RelEstadoAccion.codEstado',$codigo);
            
            $query = $this->db->get();
                                  
            return $query;
        }
        
        
        function EstadosRelacion($codigo){
            $this->db->select('RelAccionEstado.codEstado,EstadoDeuda.txtNomEstadoDeuda');
            $this->db->from('RelAccionEstado');
            $this->db->join('EstadoDeuda','RelAccionEstado.codEstado=EstadoDeuda.codigo','inner');

            $this->db->where('RelAccionEstado.codAccion',$codigo);
            
            $query = $this->db->get();
                                  
            return $query;
        }
        
        function CodigoAccion($nomAccion){
             $this->db->where('NomAccion',$nomAccion);
            $query = $this->db->get('accion');
            return $query;
        }
        
         function MaxOperador(){
            $this->db->select_max('codigo');
            $query = $this->db->get('OperadorCliente');
            if($query->row()->codigo){
              $codigo=$query->row()->codigo+1   ;
            }else{
                $codigo=1;
            }
            return $codigo; 
        }
        
        function AsignarDocOperador($codDoc,$datos){
            $this->db->where('Documento',$codDoc);
            $this->db->update('Deuda',$datos);
            //$query = $this->db->get('accion');
            //return $query;
        }
        
        //INSERT RECAUDACION
        function INSRecaudacion($OBJRecaudacion,$SeqDocumento){
            //ELIMINA ANTIGUOS REGISTRO DE RECAUDACION DE ESE DOCUMENTO
            $this->db->where('SeqDocumento',$SeqDocumento);
            $this->db->delete('Recaudacion');            
            
            //INGRESA EL NUEVO REGISTRO DE RECAUDACION
            $this->db->insert('Recaudacion', $OBJRecaudacion);            
        }
        
        //SELECT CODIGO NUEVA RECAUDACION
        function SELSeqRecaudacion(){
            $this->db->select_max('codigo');
            $query = $this->db->get('Recaudacion');
            if($query->row()->codigo){
              $codigo=$query->row()->codigo+1   ;
            }else{
                $codigo=1;
            }
            return $codigo;
        }
        
        //SELECT DATOS COBRANZA
        function SELRecaudacion($SeqDocumento){
            $this->db->select('Recaudacion.Direccion,Recaudacion.NombreContacto,Recaudacion.Hora,Recaudacion.Dato2,Recaudacion.Dato3,BancoChile.NomBanco,DocumentoPago.DocumentoPago');
            $this->db->from('Recaudacion');
            $this->db->join('BancoChile','Recaudacion.SeqBancoOrigen=BancoChile.codigo','inner');
            $this->db->join('DocumentoPago','Recaudacion.SeqDocumentoPago=DocumentoPago.codigo','inner');

            $this->db->where('Recaudacion.SeqDocumento',$SeqDocumento);
            $query = $this->db->get();
            //$this->db->where('SeqDocumento',$SeqDocumento);
            //$query = $this->db->get('Recaudacion');
            return $query;
        }

        //BUSCA EL ID DEL DEUDOR CON EL NOMBRE
        function SELSeqDeudor($SeqDocumento){
            $this->db->where('Documento',$SeqDocumento);
            $query = $this->db->get('Deuda');
            return $query;
        }
        
        //BUSCA LOS CONTACTOS ASOCIADOS A UN DEUDOR
        function SELDatosContacto($SeqDeudor){
            $this->db->where('Deudor',$SeqDeudor);
            $query = $this->db->get('Contacto');
            return $query->result();
            
        }
        
        //BUSCA EL HISTORIAL DE ABONOS
        function HistorialAbonos($seqDocumento){
            $this->db->where('SeqDocumento',$seqDocumento);
            $query=$this->db->get('Abono');
            
            return $query->result();
        }
        
        //TOTAL ABONOS
        function TotalAbonos($SeqDocumento){

            $this->db->select_sum('MontoAbono', 'SumaAbono');
            $this->db->where('SeqDocumento',$SeqDocumento);
            $query = $this->db->get('Abono');
            return $query->result();

        
        }
        
        function SELMaxIdAbono(){
            
             $this->db->select_max('codigo');
            $query = $this->db->get('Abono');
            if($query->row()->codigo){
              $codigo=$query->row()->codigo+1   ;
            }else{
                $codigo=1;
            }
            return $codigo; 
        }
        
        function INSNuevoAbono($OBJAbono){
            $this->db->insert('Abono',$OBJAbono);
        }
        
        //function UPDContactoNoVigente($seqContacto,$DatosContacto){
//             $this->db->where('codigo',$seqContacto);
//            $this->db->update('Contacto',$DatosContacto);
//            
//        }
        
        //

            

    }
?>