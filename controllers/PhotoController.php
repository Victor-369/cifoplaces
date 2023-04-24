<?php
    
    class PhotoController extends Controller{        
        public function show(int $id = 0) {
            if(!$id) {
                throw new Exception("No se indicó la foto a mostrar.");
            }

            $photo = Photo::getById($id);

            if(!$photo) {
                throw new Exception("No se encontró la foto indicada.");
            }

            //Propietario de la fotografía
            $photo->owner = $photo->belongsTo('User')->displayname;

            // Lugar
            $place = Place::getById($photo->idplace);

            // Comentarios
            //$comments = $photo->hasMany('Comment');
            $photo->idplace = $place->id;
           
            //$comments = $photo->hasManyComments();
            $comments = Photo::hasManyComments($place->id, $photo->id);
           

            $this->loadView("photo/show", ['photo'    => $photo,
                                           'place'    => $place,
                                           'comments' => $comments]);
        }

        public function create(int $idplace = 0) {
            //Auth::oneRole(['ROLE_LIBRARIAN', 'ROLE_ADMIN']);
            Auth::oneRole(['ROLE_USER', 'ROLE_MODERATOR']);

            if(!Login::oneRole(['ROLE_USER', 'ROLE_MODERATOR'])) {
                Session::error("No tienes permiso para hacer esto.");
                redirect('/login');
            }

            $place = Place::getById($idplace);

            $this->loadView('photo/create', ['place' => $place]);
        }

        public function store(int $idplace = 0) {
            Auth::oneRole(['ROLE_USER', 'ROLE_MODERATOR']);

            if(empty($_POST['guardar'])) {
                throw new Exception("No se recibió el formulario.");
            }

            $photo = new Photo();

            $photo->name =          (DB_CLASS)::escape($_POST['name']);
            $photo->description =   (DB_CLASS)::escape($_POST['description']);
            $photo->date =          (DB_CLASS)::escape($_POST['date']);
            $photo->time =          (DB_CLASS)::escape($_POST['time']);
            $photo->iduser =        Login::user()->id;
            $photo->idplace =       $idplace;            

            $errores = $photo->erroresDeValidacion();

            if(sizeof($errores)) {
                throw new Exception(join("<br>", $errores));
            }

            try {
                //$photo->save();

                if(Upload::arrive('fichero')) {
                    $photo->file = Upload::save(
                                                    'fichero',
                                                    '../public/'.PHOTO_IMAGE_FOLDER,
                                                    true,
                                                    300000,
                                                    'image/*',
                                                    'img_'
                                                );

                    $photo->save();
                    $photo->update();
                }

                Session::flash("success", "Guardado de la foto $photo->name correcto.");
                redirect("/place/show/$idplace");
            } catch(SQLException $e) {
                Session::flash("error", "No se pudo guardar la foto $photo->name.");

                if(DEBUG) {
                    throw new Exception($e->getMessage());
                } else {
                    redirect("/photo/create");
                }
            } catch(UploadException $e) {
                Session::warning("Los detalles de la foto se guardaron correctamente,
                                  pero no se pudo subir el fichero de imagen.");
                
                if(DEBUG) {
                    throw new Exception($e->getMessage());
                } else {
                    redirect("/photo/create");
                }
            }
        }

        public function edit(int $id = 0) {
            Auth::oneRole(['ROLE_USER', 'ROLE_MODERATOR']);

            if(!Login::oneRole(['ROLE_USER', 'ROLE_MODERATOR'])) {
                Session::error("No tienes permiso para hacer esto.");
                redirect('/login');
            }

            if(!$id) {
                throw new Exception("No se indicó el id");
            }

            $photo = Photo::getById($id);
            
            if($photo->iduser != Login::user()->id) {
                throw new Exception("Sólo el propietario de la foto la puede editar.");
            }
            
            $photo->owner = User::getById($photo->iduser)->displayname;
            $place = Place::getById($photo->idplace);

            if(!$photo) {
                throw new Exception("No existe la foto indicada.");
            }

            $this->loadView("photo/edit", [
                                            'photo' => $photo,
                                            'place' => $place,
                                          ]);
        }

        public function update(int $id = 0) {
            Auth::oneRole(['ROLE_USER', 'ROLE_MODERATOR']);

            if(!Login::oneRole(['ROLE_USER', 'ROLE_MODERATOR'])) {
                Session::error("No tienes permiso para hacer esto.");
                redirect('/login');
            }
            
            if(empty($_POST['actualizar'])) {
                throw new Exception("No se recibieron datos.");
            }

            $photo = Photo::getById($id);


            if($photo->iduser != Login::user()->id) {
                throw new Exception("Sólo el propietario de la foto la puede editar.");
            }

            if(!$photo) {
                throw new Exception("No se ha encontrado la foto $id.");
            }

            $photo->name =          (DB_CLASS)::escape($_POST['name']);
            $photo->description =   (DB_CLASS)::escape($_POST['description']);
            $photo->date =          (DB_CLASS)::escape($_POST['date']);
            $photo->time =          (DB_CLASS)::escape($_POST['time']);

            $errores = $photo->erroresDeValidacion();

            if(sizeof($errores)) {
                throw new Exception(join("<br>", $errores));
            }
            
            try {
                $photo->update();

                Session::flash("success", "Actualización de la foto $photo->name correcta.");
                redirect("/photo/edit/$id");
            } catch(SQLException $e) {
                Session::flash("error", "No se pudo actualizar la foto $photo->name.");

                if(DEBUG) {
                    throw new Exception($e->getMessage());
                } else {
                    redirect("/photo/edit/$id");
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
                throw new Exception("No se indicó la foto a borrar.");
            }

            $photo = Photo::getById($id);

            if(!$photo) {
                throw new Exception("No existe la foto $id.");
            }

            if($photo->iduser != Login::user()->id
                || Login::oneRole(['ROLE_MODERATOR', 'ROLE_ADMIN'])) {
                throw new Exception("Sólo el propietario, moderador o administrador puede borrar la foto.");
            }

            $place = Place::getById($photo->idplace);

            $this->loadView("photo/delete", [
                                              'photo' => $photo,
                                              'place' => $place
                                            ]);
        }

        public function destroy(int $id= 0) {
            if(empty($_POST['borrar'])) {
                throw new Exception("No se recibió la confirmación.");
            }
            
            $photo = Photo::getById($id);

            if(!$photo) {
                throw new Exception("No existe la foto $id.");
            }

            $comments = $photo->hasMany('Comment');
            $place = Place::getById($photo->idplace);
            
            try {
                @unlink('../public/'.PHOTO_IMAGE_FOLDER.'/'.$photo->file);
                
                $photo->deleteObject();

                foreach($comments as $comment) {
                    $comment->deleteObject();
                }
                                
                Session::flash("success", "Se ha borrado la foto $photo->name y sus comentarios.");
                redirect("/place/show/$place->id");

            } catch(SQLException $e) {
                Session::flash("error", "No se pudo borrar la foto $photo->name.");

                if(DEBUG) {
                    throw new Exception($e->getMessage());
                } else {
                    redirect("/photo/delete/$id");
                }
            }
        }

    }