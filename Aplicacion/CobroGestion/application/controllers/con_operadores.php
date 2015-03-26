
<?php
    class con_operadores extends CI_Controller{
        
        function __construct(){
            parent::__construct();
//            $prefs = array (
//                'start_day' => 'sunday',
//                'month_type' => 'long',
//                'day_type' => 'short',
//                'show_next_prev' => true
//                
//            );
//            $this->load->library('calendar', $prefs);
//            
//            $this->load->helper('date');
            
             $this->load->model('mod_operadores');
             $this->load->library('session');
        }
        
        function index(){
            //=======================================
            //      CARGA DATOS PARA EL FILTRO
            //=======================================
            $resul=$this->mod_operadores->FiltroPanel();
            $DatosOperadores = array(
                '-1'=>'TODOS',
             );
            if($resul){
               foreach($resul->result() as $row ){
                    $DatosOperadores[$row->codigo]=$row->Nombres." ".$row->Apellidos;       
               }
            }
            $data['DatosOperadores']=$DatosOperadores;
            
            $this->load->view('view_usuario',$data);
            }

            
        }