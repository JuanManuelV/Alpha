
<?php
    class mod_operadores extends CI_Model{

        function FiltroPanel(){
            $query=$this->db->get('Operador');
            return $query;
        }
        
        function FiltroOperador($CodNombre,$CodUsuario){
            $this->db->select('Operador.codigo,Operador.Nombre,Operador.Apellidos,Operador.Direccion,Operador.Telefono,Operador.Mail,usuarios.nombre');
            $this->db->from('Operador');
            $this->db->join('usuarios','Operador.Usuario=usuarios.codigo','inner');
            
            if($CodNombre!="-1" and $CodUsuario!="-1"){
                $this->db->where('Operador.codigo',$CodNombre);     
                $this->db->or_where('Operador.codigo',$CodUsuario);
                $query = $this->db->get();
            }
            if($CodNombre=="-1" and $CodUsuario!="-1"){
                $this->db->where('Operador.codigo',$CodNombre);     
                //$this->db->or_where('Operador.codigo',$CodUsuario);
                $query = $this->db->get();
            }
            if($CodNombre!="-1" and $CodUsuario=="-1"){
                //$this->db->where('Operador.codigo',$CodNombre);     
                $this->db->where('Operador.codigo',$CodUsuario);
                $query = $this->db->get();
            }
            if($CodNombre=="-1" and $CodUsuario=="-1"){
                $this->db->where('Operador.codigo',$CodUsuario);
                $query = $this->db->get();
            }
            
            
            return $query;
        }
        
}