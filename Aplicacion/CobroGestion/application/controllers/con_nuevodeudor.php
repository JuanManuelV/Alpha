<?php
    class con_nuevodeudor extends CI_Controller{
        function __construct(){
            parent::__construct();
             $this->load->helper('form');
              $this->load->model('mod_nuevodeudor');
        }
        
        function agrega_deudor(){

            $datos_deudor =array(
                'codigo'=>$this->mod_nuevodeudor->max_codigo(),
                'nombres'=>$this->input->post("nombre"),
                'apaterno'=>$this->input->post("apat"),
                'amaterno'=>$this->input->post("amat"),
                'direccion'=>$this->input->post("dir"),
                'telefono'=>$this->input->post("telefono"),
                'mail'=>$this->input->post("mail"),
                'deuda'=>$this->input->post("deuda"),
                'contacto'=>$this->input->post("contacto"),
                'etapa_cobranza'=>$this->input->post("IdEtapaCobranza"),
            );           
            
            $this->mod_nuevodeudor->ingresa($datos_deudor);
            redirect("con_usuariovalido");
        }
    }
?>