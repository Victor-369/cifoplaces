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

            $comments = Place::hasManyComments($place->id);

            $this->loadView("place/show", ['place'    => $place, 
                                           'photos'   => $photos,
                                           'comments' => $comments]);
        }

        public function create() {
            //Auth::oneRole(['ROLE_LIBRARIAN', 'ROLE_ADMIN']);
            Auth::oneRole(['ROLE_USER', 'ROLE_MODERATOR']);

            if(!Login::oneRole(['ROLE_USER', 'ROLE_MODERATOR'])) {
                Session::error("No tienes permiso para hacer esto.");
                redirect('/');
            }

            $this->loadView('place/create', []);
        }

        public function store() {
            Auth::oneRole(['ROLE_USER', 'ROLE_MODERATOR']);

            if(empty($_POST['guardar'])) {
                throw new Exception("No se recibió el formulario.");
            }

            $place = new Place();

            $place->name =          (DB_CLASS)::escape($_POST['name']);
            $place->type =          (DB_CLASS)::escape($_POST['type']);
            $place->location =      (DB_CLASS)::escape($_POST['location']);
            $place->description =   (DB_CLASS)::escape($_POST['description']);
            $place->iduser =        Login::user()->id;

            $errores = $place->erroresDeValidacion();

            if(sizeof($errores)) {
                throw new Exception(join("<br>", $errores));
            }

            try {
                $place->save();                

                Session::flash("success", "Guardado del lugar $place->name correcto.");
                //redirect("/Photo/create/$place->id");
                $this->loadView("photo/create", ['place' => $place]);

            } catch(SQLException $e) {
                Session::flash("error", "No se pudo guardar el lugar $place->name.");

                if(DEBUG) {
                    throw new Exception($e->getMessage());
                } else {
                    redirect("/place/create");
                }
            }
        }

        public function edit(int $id = 0) {
            Auth::oneRole(['ROLE_USER', 'ROLE_MODERATOR']);

            if(!Login::oneRole(['ROLE_USER', 'ROLE_MODERATOR'])) {
                Session::error("No tienes permiso para hacer esto.");
                redirect('/');
            }

            if(!$id) {
                throw new Exception("No se indicó el id");
            }

            $place = Place::getById($id);

            if(!$place) {
                throw new Exception("No existe el lugar indicado.");
            }

            $this->loadView("place/edit", ['place' => $place]);
        }

        public function update(int $id = 0) {
            Auth::oneRole(['ROLE_USER', 'ROLE_MODERATOR']);

            if(!Login::oneRole(['ROLE_USER', 'ROLE_MODERATOR'])) {
                Session::error("No tienes permiso para hacer esto.");
                redirect('/');
            }
            
            if(empty($_POST['actualizar'])) {
                throw new Exception("No se recibieron datos.");
            }

            //$id = intval($_POST['id']);
            $place = Place::getById($id);

            if(!$place) {
                throw new Exception("No se ha encontrado el lugar $id.");
            }

            $place->name =          (DB_CLASS)::escape($_POST['name']);
            $place->type =          (DB_CLASS)::escape($_POST['type']);
            $place->location =      (DB_CLASS)::escape($_POST['location']);
            $place->description =   (DB_CLASS)::escape($_POST['description']);

            $errores = $place->erroresDeValidacion();

            if(sizeof($errores)) {
                throw new Exception(join("<br>", $errores));
            }
            
            try {
                $place->update();

                Session::flash("success", "Actualización del lugar $place->name correcta.");
                redirect("/Place/edit/$id");
            } catch(SQLException $e) {
                Session::flash("error", "No se pudo actualizar el lugar $place->name.");

                if(DEBUG) {
                    throw new Exception($e->getMessage());
                } else {
                    redirect("/Place/edit/$id");
                }
            }
        }

        public function delete(int $id = 0) {
            Auth::oneRole(['ROLE_USER', 'ROLE_MODERATOR', 'ROLE_ADMIN']);

            if(!Login::oneRole(['ROLE_USER', 'ROLE_MODERATOR', 'ROLE_ADMIN'])) {
                Session::error("No tienes permiso para hacer esto.");
                redirect('/login');
            }

            if(!$id) {
                throw new Exception("No se indicó el lugar a borrar.");
            }

            $place = Place::getById($id);

            if(!$place) {
                throw new Exception("No existe el lugar $id.");                    
            }

            $this->loadView("place/delete", ['place' => $place]);            
        }

        public function destroy(int $id= 0) {
            if(empty($_POST['borrar'])) {
                throw new Exception("No se recibió la confirmación.");
            }
            
            $place = Place::getById($id);

            if(!$place) {
                throw new Exception("No existe el lugar $id.");                
            }

            $photos = $place->hasMany('Photo');
            $comments = $place->hasMany('Comment');
            
            try {
                foreach($photos as $photo) {
                    @unlink('../public/'.PHOTO_IMAGE_FOLDER.'/'.$photo->file);
                }
                
                $place->deleteObject();
                
                foreach($comments as $comment) {
                    $comment->deleteObject();
                }
                                
                Session::flash("success", "Se ha borrado el lugar $place->name y sus comentarios.");
                redirect("/place/list");

            } catch(SQLException $e) {
                Session::flash("error", "No se pudo borrar el lugar $place->name.");

                if(DEBUG) {
                    throw new Exception($e->getMessage());
                } else {
                    redirect("/place/delete/$id");
                }
            }
        }
    }