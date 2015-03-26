<?php
    class SistemaMOD extends CI_Model{
        function EstadosDocumento(){
            $query = $this->db->get('EstadoDeuda');
            return $query;
        }
        
        function AccionesDocumento(){
            $query = $this->db->get('accion');
            return $query;
        }
        
        function MaxRelEstadoAccion(){
            $this->db->select_max('codigo');
            $query = $this->db->get('RelEstadoAccion');
            if($query->row()->codigo){
              $codigo=$query->row()->codigo+1   ;
            }else{
                $codigo=1;
            }
            return $codigo;
        }
        
         function MaxRelAccionEstado(){
            $this->db->select_max('codigo');
            $query = $this->db->get('RelAccionEstado');
            if($query->row()->codigo){
              $codigo=$query->row()->codigo+1   ;
            }else{
                $codigo=1;
            }
            return $codigo;
        }
        
        function EliminaRelaciones($seqEstado){
            $this->db->where('codEstado',$seqEstado);
            $this->db->delete('RelEstadoAccion');
        }
        
        function EliminaRelacionesAccionEstado($seqAccion){
            $this->db->where('codAccion',$seqAccion);
            $this->db->delete('RelAccionEstado');
        }
        
        function AgregaRelacionEstadoAccion($Relacion){
            $this->db->insert('RelEstadoAccion', $Relacion);
        }
        
        function AgregaRelacionAccionEstado($Relacion){
            $this->db->insert('RelAccionEstado', $Relacion);
        }
        
        function BuscaEstadosCheck($seqEstado){
            $this->db->where('codEstado',$seqEstado);
             //this->db->order_by('txtTipoRut.codigo','ASC');
            $query = $this->db->get('RelEstadoAccion');
            return $query;
        }
        
        function BuscaAccionesCheck($seqAccion){
            $this->db->where('codAccion',$seqAccion);
             //this->db->order_by('txtTipoRut.codigo','ASC');
            $query = $this->db->get('RelAccionEstado');
            return $query;
        }
    }
?>
        