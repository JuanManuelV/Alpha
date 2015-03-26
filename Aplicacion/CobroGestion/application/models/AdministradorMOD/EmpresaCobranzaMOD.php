<?php
    class EmpresaCobranzaMOD extends CI_Model{
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
        
        //INSERT NUEVO CLIENTE
        function NuevoClieteInsert($EmpresaCliente,$OperadorCliente){
             $this->db->insert('EmpresaCliente', $EmpresaCliente);
             $this->db->insert('OperadorCliente', $OperadorCliente);
        }
        
        function DatosEmpresaCobranza(){
            $this->db->select('EmpresaCliente.codigo,EmpresaCliente.txtNomCliente,EmpresaCliente.rutCliente,EmpresaCliente.txtTelCliente,EmpresaCliente.dtFechaCreacion,EstadoCliente.txtNomEstadoCL');
            $this->db->from('EmpresaCliente');
            $this->db->join('EstadoCliente','EmpresaCliente.seqEstadoCliente=EstadoCliente.seqEstadoCliente','inner');
            $this->db->where('seqTipoCliente',2);
            $query = $this->db->get();
                                  
            return $query;
        }
        
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
    }
?>