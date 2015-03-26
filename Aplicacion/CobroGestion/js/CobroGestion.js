            //================================================
            //
            //             MUESTRA CALENDARIOS
            //
            //================================================
            $(function() {
                $('.dropdown-toggle').dropdown()                
                $('#datetimepicker0').datetimepicker({pickTime: false});
                $('#datetimepicker1').datetimepicker({pickTime: false});
                $('#datetimepicker2').datetimepicker({pickTime: false});
                $('#datetimepicker3').datetimepicker({pickTime: false});
                $('#datetimepicker4').datetimepicker({pickTime: false});
                $('#datetimepicker5').datetimepicker({pickTime: false});
                $('#datetimepicker6').datetimepicker({pickTime: false});
            });

            //================================================
            //
            //                  TABLAS 
            //
            //================================================
               $(document).ready(function() {
                    $('#TablaAcciones').tablesorter();
                    $('#example1').tablesorter();
                    $('#example2').tablesorter();
                    $('#example3').tablesorter();
                    $('#example4').tablesorter();
                    $('#tblDocumento').tablesorter();
                    
                    $("#btn_edicion_masiva").click(function() {
                        var items = [];
                        $("input[name='acciones[]']:checked").each(function(){items.push($(this).val());});
                        $("#modal_acciones").val(items);
                        $("#EdicionMasiva").modal('show');
                        
                        $("#comen").val('');
                        $("#ProxGestionMulti").val('');
                        $('#TipoAccionMulti').val(0);      
                    });
                    
                    $("#AgergaDeudorOculto").click(function() {
                        $("#TDeudorHidden").val($("#TPersonaOculto").val());
                        $("#NomDeudorHidden").val($("#NomDeudorOculto").val());                                
                        $("#ApellDuedorHidden").val($("#ApellDeudorOculto").val());                                 
                        $("#DirDeudorHidden").val($("#DirDeudorOculto").val());                             
                        $("#CiuDeudorHidden").val($("#RegionDeudorOculto").val());
                        $("#ComDeudorHidden").val($("#ComunaDeudorOculto").val());                               
                        $("#TelDeudorHidden").val($("#TelDeudorOculto").val());                               
                        $("#MailDeudorHidden").val($("#MailDeudorOculto").val());
                       // $('#').val($('#').val());
                        $('#TipoTribDeudorHidden').val($('#TipoRutDeudorOculto').val());
                        $('#IdTribDeudorHidden').val($('#RutDeudorOculto').val());
                        
                        
                    });
                    
                    $("#RecaudaDatos").click(function() {
                        //alert("leo");
                        /*if ($('#TipoAccion').val()==3){
                            $("#num1Add").val($("#txtNum1").val());
                            $("#num2Add").val($("#txtNum2").val());
                            $("#num3Add").val($("#txtNum3").val());
                        
                            $("#LugRecauda").val($("#LugarRecauda").val());
                            $("#ConRecauda").val($("#ContactoRecauda").val());
                            $("#HrRecauda").val($("#HoraRecauda").val());
                            $("#FormaPago").val($("#TipoDocRecauda").val());
                        }
                        
                        if ($('#TipoAccionGestiona').val()==3){
                            
                            //alert($("#num1Mod").val()+"---"+$("#txtNum1").val());
                            //alert();
                            
                            $("#num1Mod").val($("#txtNum1").val());
                            $("#num2Mod").val($("#txtNum2").val());
                            $("#num3Mod").val($("#txtNum3").val());
                        
                            $("#LugRecaudaMod").val($("#LugarRecauda").val());
                            $("#ConRecaudaMod").val($("#ContactoRecauda").val());
                            $("#HrRecaudaMod").val($("#HoraRecauda").val());
                            $("#FormaPagoMod").val($("#TipoDocRecauda").val());
                        }
                        if ($('#TipoAccionMulti').val()==3){
                            $("#num1Multi").val($("#txtNum1").val());
                            $("#num2Multi").val($("#txtNum2").val());
                            $("#num3Multi").val($("#txtNum3").val());
                            
                            $("#LugRecaudaMulti").val($("#LugarRecauda").val());
                            $("#ConRecaudaMulti").val($("#ContactoRecauda").val());
                            $("#HrRecaudaMulti").val($("#HoraRecauda").val());
                            $("#FormaPagoMulti").val($("#TipoDocRecauda").val());
                        }              */              
                        
                        
                        //<input type="hidden" name="num1" id="num1" />
                    });
                    
                    /*$("#RecaudaDatos").click(function() {
                        if ($('#TipoAccion').val()==3){
                            $("#LugRecauda").val($("#LugarRecauda").val());
                            $("#ConRecauda").val($("#ContactoRecauda").val());
                            $("#HrRecauda").val($("#HoraRecauda").val());
                            $("#FormaPago").val($("#TipoDocRecauda").val());
                        }
                        
                        if ($('#TipoAccionGestiona').val()==3){
                            $("#LugRecaudaMod").val($("#LugarRecauda").val());
                            $("#ConRecaudaMod").val($("#ContactoRecauda").val());
                            $("#HrRecaudaMod").val($("#HoraRecauda").val());
                            $("#FormaPagoMod").val($("#TipoDocRecauda").val());
                        }
                        if ($('#TipoAccionMulti').val()==3){
                            $("#LugRecaudaMulti").val($("#LugarRecauda").val());
                            $("#ConRecaudaMulti").val($("#ContactoRecauda").val());
                            $("#HrRecaudaMulti").val($("#HoraRecauda").val());
                            $("#FormaPagoMulti").val($("#TipoDocRecauda").val());
                        }       
                    });*/
                
                });
                
            //================================================
            //
            //             OCULTA DATOS
            //
            //================================================            
            
            
            //================================================
            //
            //             CAMBIA EL TIPO DE PERSONA
            //
            //================================================
            
                    
            
            //================================================
            //
            //             GUARDA LOS CHECKS
            //
            //================================================

            
            //================================================
            //
            //             CAMBIA EL TIPO DE PERSONA
            //
            //================================================
            
            
            //================================================
            //
            //             CAMBIA EL TIPO DE PERSONA
            //
            //================================================
            
            
            //================================================
            //
            //             CAMBIA EL TIPO DE PERSONA
            //
            //================================================
            
            //================================================
            //
            //             CAMBIA EL TIPO DE PERSONA
            //
            //================================================
            
            
            
               
               