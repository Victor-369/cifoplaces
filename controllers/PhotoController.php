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
            $comments = $photo->hasMany('Comment');

            foreach($comments as $comment) {
                $comment->owner = $comment->belongsTo('User')->displayname;
            }
            

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

    }