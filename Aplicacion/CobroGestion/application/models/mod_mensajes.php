<?php
    class mod_mensajes extends CI_Model{
    
    function Usuarios($codigo){
        if($codigo==0){
            $query = $this->db->get('Operador');
        }else{
            $this->db->where('Cliente',$codigo);
            $query = $this->db->get('Operador');
        }
        return $query;
    }
    
    function max_msj(){
        $this->db->select_max('Codigo');
        $query = $this->db->get('Mensajes');
        if($query->row()->codigo){
            $codigo=$query->row()->codigo+1   ;
        }else{
            $codigo=1;
        }
        return $codigo;
    }
    
    function busca_msj($cod){
         $this->db->where('Codigo',$cod);
         $this->db->limit(1);
         $query = $this->db->get('Mensajes');
            
         if($query ->num_rows()==1){
                return $query->row();
            }else{
                return false;  
         }
    }
    
    function msj_recibido($cod){
        $this->db->where('Receptor',$cod);
        $this->db->order_by('FechaEnvio','INC');
        $query = $this->db->get('Mensajes');
         
        return $query;
    }
    
    function carga_deudores($codigo){
            $this->db->where('Cliente',$codigo);
            $query = $this->db->get('deudor');
            
            return $query;
    }
    
    function agrega_mensaje($datos){
        $this->db->insert('Mensajes',$datos);
    }
    
    function msj_nuevo($usuario){
        $estado="NOLEIDO";
        //$this->db->select('Codigo');
        $this->db->where('Estado',$estado);
        $this->db->where('Receptor',$usuario);
        $query = $this->db->get('Mensajes');
        return $query;
        
    }
    
    function modifica_estado($codigo){
        $estado['Estado']="LEIDO";
        $this->db->where('Codigo',$codigo);
        $this->db->update('Mensajes',$estado);
    }
    function max_conv(){
        $this->db->select_max('Conversacion');
        $query = $this->db->get('Mensajes');
        if($query->row()->codigo){
            $codigo=$query->row()->codigo+1   ;
        }else{
            $codigo=1;
        }
        return $codigo;
    }
    
    function borra_conv($conv){
        $this->db->where('Conversacion',$conv);
        $this->db->delete('Mensajes');
        
    }
        
    }
?>