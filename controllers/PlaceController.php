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
                $place->photo = $place->hasMany('Photo')[0] ?? null;
                
                if(file_exists(PHOTO_IMAGE_FOLDER.'/'.$place->photo->file)) {
                    //confirma que NO existe el fichero
                    $place->photo->file = DEFAULT_PHOTO_IMAGE;                    
                }
            }

            $this->loadView('place/list', [
                                            'places'    => $places,
                                            'paginator' => $paginator,
                                            'filtro'    => $filtro
                            ]);
        }

    }