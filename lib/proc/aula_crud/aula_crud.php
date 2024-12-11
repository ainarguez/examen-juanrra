<?php

    class AulaCRUD extends ProgramaBase
    {
        const LIMITE_SCROLL = 5;

        function __construct()
        {

            $this->aula = new aula();


            parent::__construct($this->aula);

        }

        function inicializar()
        {
            $paso        = new Hidden('paso'); 
            $paso->value = 1;

            $oper        = new Hidden('oper'); 
            $id          = new Hidden('id');        

            $nombre      = new Input   ('nombre'       ,['placeholder' => 'Nombre del aula...'     , 'validar' => True, 'ereg' => EREG_TEXTO_100_OBLIGATORIO  ]);
            $letra       = new Select  ('letra'        ,aula::LETRAS,['validar' => True]);
            $numero      = new Input   ('numero'       ,['placeholder' => 'Número del aula...'      , 'validar' => True, 'ereg' => EREG_TEXTO_150_OBLIGATORIO  ]);
            $planta      = new Select  ('planta'       ,aula::PLANTAS,['validar' => True]);

            $this->form->cargar($paso);
            $this->form->cargar($oper);
            $this->form->cargar($id);
            $this->form->cargar($nombre);
            $this->form->cargar($letra);
            $this->form->cargar($numero);
            $this->form->cargar($planta);
        }
        function visualizar()
        {
            $paso        = new Hidden('paso'); 
            $paso->value = 1;

            $oper        = new Hidden('oper'); 
            $id          = new Hidden('id');        

            $this->form->cargar($paso);
            $this->form->cargar($oper);
            $this->form->cargar($id);
            $this->form->cargar($nombre);
            $this->form->cargar($letra);
            $this->form->cargar($numero);
            $this->form->cargar($planta);
        }


        function existe($id='numero')
        {

            $cantidad = 0;
            if (   !empty($this->form->val['nombre']) 
                && !empty($this->form->val['letra'])
                && !empty($this->form->val['numero'])
                && !empty($this->form->val['planta'])
            )
            {   

                $cantidad = $this->aula->existeaula(
                    $this->form->val['nombre']
                ,$this->form->val['letra']
                ,$this->form->val['numero']
                ,$this->form->val['planta']
                ,$this->form->val['id']
                );
            }

            return $cantidad;
        }


        function recuperar()
        {

            $this->aula->recuperar($this->form->val['id']);

            $this->form->elementos['nombre']->value      = $this->aula->nombre;
            $this->form->elementos['letra']->value       = $this->aula->letra;
            $this->form->elementos['numero']->value      = $this->aula->numero;
            $this->form->elementos['planta']->value      = $this->aula->planta;
        }


        function resultados_busqueda()
        {
            $listado_aulas = '
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Nombre</th>
                        <th scope="col">Letra</th>
                        <th scope="col">Número</th>
                        <th scope="col">Planta</th>
                    </tr>
                </thead>
                <tbody>
            
            ';

            $limite = AulaCrud::LIMITE_SCROLL;

            $pagina = $this->form->val['pagina'];

            $offset = $pagina * $limite;



            $opt = [];
    
            $opt['orderby']['nombre'] = 'ASC';   
            $opt['offset'] = $offset;
            $opt['limit']  = $limite;
        
        
            $resultado = $this->aula->seleccionar($opt);

            if ($resultado->num_rows > 0) 
            {
                while ($fila = $resultado->fetch_assoc()) 
                {

                    
                    

                    $listado_aulas .= "
                        <tr>
                            <th scope=\"row\">
                                ". enlace("/{$this->seccion}/leer/{$fila['id']}",'Leer',['class' => 'btn btn-info']) ."
                                ". enlace("/{$this->seccion}/actualizar/{$fila['id']}",'Actualizar',['class' => 'btn btn-primary']) ."
                                ". enlace("#",'Eliminar',['class' => 'btn btn-danger','onclick' => "if(confirm('Cuidado, estás tratando de eliminar el aula: {$fila['nombre']}')) location.href = '/aula/eliminar/{$fila['id']}';"]) ."
                            </th>
                            <td>{$fila['nombre']}</td>
                            <td>{$fila['letra']}</td>
                            <td>{$fila['numero']}</td>
                            <td>". aula::PLANTAS[$fila['planta']] ."</td>
                        </tr>
                    ";
                }

                if ($resultado->num_rows == AulaCRUD::LIMITE_SCROLL)
                    $siguiente = '<li class="page-item">'. enlace("/{$this->seccion}/pag/". ($pagina + 1), 'Siguiente',['class' => 'page-link']) .'</li>';
            } 
            else 
            {
                $listado_aulas = '<tr><td colspan="5">No hay resultados</td></tr>';
            }

            if($pagina)
                $pagina_anterior = '<li class="page-item">'. enlace("/{$this->seccion}/pag/". ($pagina - 1), 'Anterior',['class' => 'page-link']) .'</li>';
            

            $listado_aulas .= '
                    </tbody>
                </table>
                <nav aria-label="Page navigation example">
                    <ul class="pagination">
                        '. $pagina_anterior .'
                        '. $siguiente .'
                    </ul>
                </nav>


                <div class="alta">'. enlace("/{$this->seccion}/alta/", 'Alta de aula',['class' => 'btn btn-success']) .'</div>
            ';
            

            return $listado_aulas;


        }
    }





