<?php
    class ClientesMOD extends CI_Model{
        
        //DATOS PARA MAXIMOS CODIGOS
        function MaxCliente(){
            $this->db->select_max('codigo');
            $query = $this->db->get('EmpresaCliente');
            if($query->row()->codigo){
              $codigo=$query->row()->codigo+1   ;
            }else{
                $codigo=1;
            }
            return $codigo;       
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
        
        //DATOS DE CLIENTES
        function DatosClientes(){
            $this->db->select('EmpresaCliente.codigo,EmpresaCliente.txtNomCliente,EmpresaCliente.rutCliente,EmpresaCliente.txtTelCliente,EmpresaCliente.dtFechaCreacion,EstadoCliente.txtNomEstadoCL');
            $this->db->from('EmpresaCliente');
            $this->db->join('EstadoCliente','EmpresaCliente.seqEstadoCliente=EstadoCliente.seqEstadoCliente','inner');
            $this->db->where('seqTipoCliente',1);
            $query = $this->db->get();
                                  
            return $query;
        }
        
        //DATOS PARA FILTROS
        function DatosCiudad(){
            $this->db->order_by('RegionChile.codigo','ASC');
            $query = $this->db->get('RegionChile');
            return $query;
        }
        
        function TipoOperador(){
            $query = $this->db->get('TipoOperadorCL');
            return $query;
        }
        
        function EstadoCliente(){
            $query = $this->db->get('EstadoCliente');
            return $query;
        }
        
        //INSERT NUEVO CLIENTE
        function NuevoClieteInsert($EmpresaCliente,$OperadorCliente){
             $this->db->insert('EmpresaCliente', $EmpresaCliente);
             $this->db->insert('OperadorCliente', $OperadorCliente);
        }
        
        //SELECT DATOS CLIENTES PARA MODIFICAR
        function DatosClienteSelect($codigo){
            $this->db->where('codigo',$codigo);
            $this->db->limit(1);
            $query = $this->db->get('EmpresaCliente');
            
            if($query ->num_rows()==1){
                return $query->row();
            }else{
                return false;  
            }
        }
        
        //BLOQUEA/DESBLOQUEA A UN CLIENTE
        function BloqueaCliente($seqCliente){
            $datosCliente=array(
                'seqEstadoCliente'=>2,
            );
            
            $this->db->where('codigo',$seqCliente);
            $this->db->update('EmpresaCliente',$datosCliente);
        }
        function DesbloqueaCliente($seqCliente){
                        $datosCliente=array(
                'seqEstadoCliente'=>1,
            );
            
            $this->db->where('codigo',$seqCliente);
            $this->db->update('EmpresaCliente',$datosCliente);
        }
        
        function ModificarCliente($datosCliente,$seqCliente){
            $this->db->where('codigo',$seqCliente);
            $this->db->update('EmpresaCliente',$datosCliente);
            
        }
    }
?>