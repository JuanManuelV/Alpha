<?php
    class mod_login extends CI_Model{
        public function login($nombre,$clave){
            $this->db->where('Usuario',$nombre);
            $this->db->where('Clave',MD5($clave));
            $this->db->limit(1);
            
            $query=$this->db->get('Operador');
            
            if($query ->num_rows()==1){
                return $query->row();
            }else{
                return false;  
            }
            
        }
        
        public function EmpresasCliente(){
            $this->db->where('seqTipoCliente',1);
            $query = $this->db->get('EmpresaCliente');
            return $query;
        }
        public function EmpresasCobranza(){
            $this->db->where('seqTipoCliente',2);
            $query = $this->db->get('EmpresaCliente');
            return $query;
        }
        
        public function ValidarLogin($nombre,$clave,$Empresa){           
            $this->db->select('OperadorCliente.codigo,OperadorCliente.txtUsuOperador,OperadorCliente.seqCliente,OperadorCliente.txtNomOperador,OperadorCliente.seqTipoOperador,EmpresaCliente.seqEstadoCliente,EmpresaCliente.seqTipoCliente');
            $this->db->from('OperadorCliente');
            $this->db->join('EmpresaCliente','OperadorCliente.seqCliente=EmpresaCliente.codigo','inner');
            
            $this->db->where('OperadorCliente.seqCliente',$Empresa);
            //$this->db->where('OperadorCliente.seqCliente',$Empresa);
            $this->db->where('OperadorCliente.txtUsuOperador',$nombre);
            $this->db->where('OperadorCliente.txtClaveOperador',MD5($clave));
            $this->db->limit(1);
            
            $query=$this->db->get();
            
            if($query ->num_rows()==1){
                return $query->row();
            }else{
                return false;  
            }
            
        }
    }
?>