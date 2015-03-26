
<?php
    class DocumentosMOD extends CI_Model{
        function CodigoAccion($nomAccion){
             $this->db->where('NomAccion',$nomAccion);
            $query = $this->db->get('accion');
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
        
        
        function CargaDocumento($codigo){
            $this->db->where('Documento.Codigo',$codigo);
            
            $this->db->select('accion.NomAccion,deudor.nombres,deudor.apellidos,Documento.Codigo,Documento.Monto,Documento.Tipo,Documento.Observacion,Documento.ProxGestion,Documento.ConPago,Documento.FechaEmision, Documento.Accion,Documento.NumDocumento,Documento.Derivado,EstadoDeuda.txtNomEstadoDeuda');
            $this->db->from('Documento');
            $this->db->join('accion','Documento.Accion=accion.codigo','inner');
            $this->db->join('Deuda','Documento.Codigo=Deuda.Documento','inner');
            $this->db->join('deudor','Deuda.Deudor=deudor.codigo','inner');
            $this->db->join('EstadoDeuda','Deuda.Estado=EstadoDeuda.codigo','inner');
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
        
        
        //      PARA EDITAR UN DOCUMENTO
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
        function InsertInfoComercial($datos) {
            $this->db->insert('InformeComercial', $datos);
        }
         function SacaInformeComercial($cod){
            $this->db->where('CodDoc',$cod);
            $this->db->delete('InformeComercial');            
        }
        function editar_accion($datos,$cod,$datos_deuda){
            $this->db->where('Codigo',$cod);
            $this->db->update('Documento',$datos);
            
            
            $this->db->where('Documento',$cod);
            $this->db->update('Deuda',$datos_deuda);
        }
        function editar_accion2($datos,$cod){
            $this->db->where('Codigo',$cod);
            $this->db->update('Documento',$datos);
            
             //$this->db->where('Documento',$cod);
            //$this->db->update('Deuda',$datoDeuda);
           // $this->db->where('Documento',$cod);
           // $this->db->update('Deuda',$datoDeuda);
            
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
        function DevolucionCliente($seqDoc,$seqCliente){
            $datosDoc=array(
                'Derivado'=>"f",
            );
            
            $this->db->where('codDocumento',$seqDoc);
            $this->db->where('codEmpresaCobranza',$seqCliente);
            $this->db->delete('DocumentosDerivados');
            
            $this->db->where('Codigo',$seqDoc);
            $this->db->update('Documento',$datosDoc);
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
        
        function AccionesRelacion($codigo){
            $this->db->select('RelEstadoAccion.codAccion,accion.NomAccion');
            $this->db->from('RelEstadoAccion');
            $this->db->join('accion','RelEstadoAccion.codAccion=accion.codigo','inner');

            $this->db->where('RelEstadoAccion.codEstado',$codigo);
            
            $query = $this->db->get();
                                  
            return $query;
        }
        
                //TOTAL ABONOS
        function TotalAbonosEmp($SeqDocumento){

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
        
        function CodigoDeuda($CodDoc){
       
            $this->db->select('codigo');
            //$this->db->from('Deuda');
            $this->db->where('Documento',$CodDoc);
            
            $query = $this->db->get('Deuda');
            return $query;
        }
        
        
        function INSNuevoAbono($OBJAbono){
            $this->db->insert('Abono',$OBJAbono);
        }
        
                //BUSCA EL HISTORIAL DE ABONOS
        function HistorialAbonos($seqDocumento){
            $this->db->where('SeqDocumento',$seqDocumento);
            $query=$this->db->get('Abono');
            
            return $query->result();
        }
    }
?>