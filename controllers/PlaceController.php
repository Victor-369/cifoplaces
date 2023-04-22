<?php
    
    class PlaceController extends Controller{
        public function list(int $page = 1) {
            //Auth::admin();

            $filtro = Filter::apply('place');            
            $limit = RESULTS_PER_PAGE;
            
            $total = $filtro ? Place::filteredResults($filtro) : Place::total();

            $paginator = new Paginator('/place/list', $page, $limit, $total);

            if($filtro) {
                $places = Place::filter($filtro);
            } else {
                $places = Place::orderBy('created_at', 'DESC', $limit, $paginator->getOffset());
            }

            //Introduce en cada lugar (place) la primera foto del listado de fotos de cada lugar.
            foreach($places as $place) {                
                //$place->photo = $place->hasMany('Photo')[0] ?? null;
                $place->photo = $place->hasMany('Photo')[0]->file ?? null;

                // Problemas para confirmar la existencia de la imagen
                
                //clearstatcache();
                //dd(is_file(PHOTO_IMAGE_FOLDER.'/'.$place->photo));
                //dd(filetype(PHOTO_IMAGE_FOLDER.'/'.$place->photo));

                /*
                if(!is_readable(PHOTO_IMAGE_FOLDER.'/'.$place->photo)) {
                    $place->photo = null;
                }
                */
            }

            if($_POST['filtrar'] && $total == 0) {
                //Session::flash('warning', "No existen resultados para la búsqueda actual.");
                $filtro = "No existen resultados para la búsqueda actual.";
            }

            $this->loadView('place/list', [
                                            'places'    => $places,
                                            'paginator' => $paginator,
                                            'filtro'    => $filtro
                            ]);
        }

        public function show(int $id = 0) {
            if(!$id) {
                throw new Exception("No se indicó el lugar a mostrar.");
            }

            $place = Place::getById($id);

            if(!$place) {
                throw new Exception("No se encontró el lugar indicado.");
            }

            $photos = $place->hasMany('Photo');

            // Se agrega el autor de la fotografía
            foreach($photos as $photo) {
                $photo->owner = $photo->belongsTo('User')->displayname;
            }

            $comments = $place->hasMany('Comment');

            // Se agrega el autor del comentario
            foreach($comments as $comment) {
                $comment->owner = $comment->belongsTo('User')->displayname;
            }

            $this->loadView("place/show", ['place'    => $place, 
                                           'photos'   => $photos,
                                           'comments' => $comments]);
        }

    }