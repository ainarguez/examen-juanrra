<?php
   
    class ProgramaBase
    {
        var $seccion;


        function __construct($clase_generica='')
        {
            $this->seccion        = explode('/',$_SERVER['REQUEST_URI'])[1];
            $this->clase_generica = $clase_generica;
            $this->form           = Form::getInstance(); 

        }

        function main()
        {
            ob_start();             
        
            $oper = $this->form->val['oper'];
        
            $errores = [];
        
            switch($oper)
            {
                case 'create':
        
        
                    $this->inicializar();
        
                    if (!empty($this->form->val['paso']))
                    {
                        $salida_json = $this->form->validar();
        
        
                        $salida_json['error'] = true;

                        if(!$this->form->cantidad_errores)
                        {

                            $salida_json['error'] = false;
                            
                            if(!$this->existe())
                            {
                                $this->insertar();
                                $this->form->activeDisable();
                                $salida_json['salida'] = '<div class="exito">'. Literal::getInstance()->lit['mensaje_exito'] .'</div>';
                            }
                            else
                            {
                                $this->form->duplicado = True;
                                $salida_json['salida'] = '<div class="mensaje_error">'. Literal::getInstance()->lit['mensaje_duplicados'] .'</div>';
                            }
                        }

                        echo json_encode($salida_json);
                        exit;
                    }
        
        
                    $html_salida .= $this->cabecera('alta');
                    $html_salida .= $this->formulario($oper,$errores);
        
                break;
                case 'update':
        
                    $this->inicializar();
        
                    if (empty($this->form->val['paso']))
                    {
                        //Cargar los datos
                        $this->recuperar();
                    }
                    else
                    {
                        $salida_json = $this->form->validar();
        
                        $salida_json['error'] = true;
                        if(!$this->form->cantidad_errores)
                        {
                            $salida_json['error'] = false;
                            
                            if (!$this->existe($this->form->val['id']))
                            {
                                $this->actualizar();
                                $this->form->activeDisable();
                                $salida_json['salida'] = '<div class="exito">'. Literal::getInstance()->lit['mensaje_exito'] .'</div>';
                            }
                            else
                            {
                                $this->form->duplicado = True;
                                $salida_json['salida'] = '<div class="mensaje_error">'. Literal::getInstance()->lit['mensaje_duplicados'] .'</div>';
                            }
                        }
                    

                        echo json_encode($salida_json);

                        exit;
    
                    }
        
                    $html_salida .= $this->cabecera('actualizar');
                    $html_salida .= $this->formulario($oper,$errores);
        
                break;
                case 'read':

                    
                    $this->inicializar();
        
                    if (empty($this->form->val['paso']))
                    {
                        //Cargar los datos
                        $this->recuperar();
                    }
                    else
                    {
        
                        $salida_json['error'] = true;
                                            

                        echo json_encode($salida_json);

                        exit;
    
                    }
        
                    $html_salida .= $this->formulario($oper);
                break;
                case 'delete':
        
                    $this->eliminar();
        
                    ob_clean();

                    header("location: /". $this->seccion ."/");
                    exit(0);
        
                break;
                
                default:
        
                    $html_salida .= $this->cabecera();
        
                    $html_salida .= $this->resultados_busqueda();
                    
        
                break;
            }
            



            $html_salida = "<div class=\"container\">{$html_salida}</div><br />";

            return $html_salida;

        }


        function cabecera($titulo_seccion='')
        {
            if(empty($titulo_seccion))
            {
                $breadcrumb = "<li class=\"breadcrumb-item\">". $this->seccion ."</li>";
            }
            else
            {
                
                $breadcrumb = "
                    <li class=\"breadcrumb-item\">". enlace('/'. $this->seccion .'/', $this->seccion ,['title' => 'Volver al <b>listado</b>']) ."</li>
                    <li class=\"breadcrumb-item active\" aria-current=\"page\">{$titulo_seccion}</li>
                ";
            }

            

            return "
                <nav aria-label=\"breadcrumb\">
                    <ol class=\"breadcrumb\">
                        <li class=\"breadcrumb-item\">". enlace('/','Zonzamas',['title' => 'Volver a la página principal']) ."</li>
                        {$breadcrumb}
                    </ol>
                </nav>
            ";
        }


        function formulario($oper,$errores = [])
        {

            $id = $this->form->val['id'];

            $botones_extra = '';
            $mensaje_exito = False;
            if($this->form->val['paso'] && $this->form->cantidad_errores == 0)
            {
                $mensaje_exito = True;
                $botones_extra = enlace("/{$this->seccion}/alta/", Literal::getInstance()->lit['nuevo'] ,['class'=> 'btn btn-primary']);

                if($oper == 'update')
                    $botones_extra .= ' ' .enlace("/{$this->seccion}/actualizar/".$id,Literal::getInstance()->lit['editar'],['class'=> 'btn btn-primary']);
            
            }

            $html_formulario = $this->form->pintar(['botones_extra' => $botones_extra,'exito' =>  $mensaje_exito]);

            return $html_formulario;

        }
        

        function eliminar()
        {

            $this->clase_generica->id = $this->form->val['id'];

            $this->clase_generica->eliminar();

        }

        function actualizar()
        {
            if (!empty($this->form->val['id']))
            {
                $this->clase_generica->inicializar($this->form->val);

                $this->clase_generica->actualizar();
            }
        }

        function leer()
        {
            if (!empty($this->form->val['id']))
            {
                $this->clase_generica->visualizar($this->form->val);
            }
        }

        function insertar()
        {

            $this->clase_generica->inicializar($this->form->val);

            $this->clase_generica->insertar();
        }
    }