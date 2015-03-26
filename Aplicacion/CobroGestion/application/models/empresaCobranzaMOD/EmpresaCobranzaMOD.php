
<?php
    class EmpresaCobranzaMOD extends CI_Model{
     
         function DocumentosDerivados($Usuario){
            //if($this->session->userdata('TipoOperador')!=3){
               //$this->db->select('deudor.nombres,deudor.apellidos,deudor.codigo,accion.NomAccion,Documento.Monto,Documento.Tipo,Documento.ProxGestion, Documento.Accion, Deuda.Deudor,Deuda.Documento,Documento.FechaEmision,Documento.NumDocumento,EstadoDeuda.txtNomEstadoDeuda,accion.NomAccion,TipoDocumento.Nombre,TipoDocumento.codigo');
                $this->db->select('deudor.nombres,deudor.apellidos,deudor.codigo,accion.NomAccion,Documento.Monto,Documento.Tipo,Documento.ProxGestion, Documento.Accion, Deuda.Deudor,Deuda.Documento,Documento.FechaEmision,Documento.NumDocumento,EstadoDeuda.txtNomEstadoDeuda,accion.NomAccion,TipoDocumento.Nombre,TipoDocumento.codigo,Documento.Derivado,Documento.Remanente,Documento.ConPago');
                
                $this->db->from('deudor');
                $this->db->join('Deuda','deudor.codigo=Deuda.Deudor','inner');
                $this->db->join('Documento','Deuda.Documento=Documento.Codigo','inner');
                $this->db->join('accion','Documento.Accion=accion.codigo','inner');
                $this->db->join('EstadoDeuda','Deuda.Estado=EstadoDeuda.codigo','inner');
                $this->db->join('TipoDocumento','Documento.Tipo=TipoDocumento.codigo','inner');
                $this->db->join('DocumentosDerivados','Documento.Codigo=DocumentosDerivados.codDocumento','inner');
                
                
                //$this->db->where('Documento.Visible',"t");
                $this->db->where('DocumentosDerivados.codEmpresaCobranza',$Usuario);

            //$this->db->order_by('Documento.ProxGestion','ASC');
            
            $query = $this->db->get();
            return $query;
         }
         function EstadoDeuda(){
                $query = $this->db->get('EstadoDeuda');
                
                return $query;
         }
         function TipoAccion(){
            $this->db->where('accion.codigo <>',"4");
            $query = $this->db->get('accion');
            return $query;
        }
        function TipoRut(){
             $this->db->order_by('TipoRut.codigo','ASC');
            $query = $this->db->get('TipoRut');
            return $query;
        }
        
        function RegionesChile(){
            $this->db->order_by('RegionChile.codigo','ASC');
            $query = $this->db->get('RegionChile');
            return $query;
        }
        
        //cambiar a otro archivo php
         function agrega_Rut($datos){
            $this->db->insert('RutsUsuarios',$datos);
        }
                function agrega_operador($datos_op){
            $this->db->insert('OperadorCliente',$datos_op);
            //$this->db->insert('usuarios',$datos_usu);
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
        
        function Operadores($codigo){
            $this->db->where('seqCliente',$codigo);
            $this->db->where('seqTipoOperador',3);
            $this->db->where('Visible',"t");
            $query=$this->db->get('OperadorCliente');
            
            return $query;   
        }
        function AsignarDocOperador($codDoc,$datos){
            $this->db->where('codDocumento',$codDoc);
            $this->db->update('DocumentosDerivados',$datos);
        }
        
        function elimina_operador($codigo){
            $dato = array(
                'Visible'=>'f',
            );
            $this->db->where('codigo',$codigo);
            $this->db->update('OperadorCliente',$dato);
        }        
        
        //################################
        //      devolver al administrador
        //################################
        function seqAdministrador($seqCliente){
            $this->db->select('codigo')->from('OperadorCliente')->where('seqTipoOperador',1)->where('seqCliente',$seqCliente);
            
            $query = $this->db->get();
            return $query->result(); 
        }
        
        function DerivaDocAdministrador($seqCliente,$codOperador,$seqDocumento,$datoAdmin){
            $this->db->where('codDocumento',$seqDocumento);
            $this->db->where('codEmpresaCobranza',$codOperador);
            $this->db->update('DocumentosDerivados',$datoAdmin);            
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
        
    }
?>