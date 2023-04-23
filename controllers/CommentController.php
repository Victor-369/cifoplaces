<?php
    
    class CommentController extends Controller{


        public function create(int $idplace = 0, int $idphoto = 0) {
            //Auth::oneRole(['ROLE_LIBRARIAN', 'ROLE_ADMIN']);
            Auth::oneRole(['ROLE_USER']);

            if(!Login::oneRole(['ROLE_USER'])) {
                Session::error("No tienes permiso para hacer esto.");
                redirect('/login');
            }

            $place = Place::getById($idplace);

            if(!$place) {
                throw new Exception("No se encontró el lugar indicado para guardar el comentario.");
            }

            if($idphoto != 0) {
                $photo = Photo::getById($idphoto);

                if(!$photo) {
                    throw new Exception("No se encontró la foto indicada para guardar el comentario.");
                }
            } else {
                $photo = null;
            }

            $this->loadView('comment/create', [
                                                'place' => $place,
                                                'photo' => $photo
                                              ]);
        }

        // Para guardar comentarios de los lugares
        public function storeplace(int $idplace = 0) {
            Auth::oneRole(['ROLE_USER']);

            if(empty($_POST['guardar'])) {
                throw new Exception("No se recibió el formulario.");
            }

            $comment = new Comment();

            $comment->text =      (DB_CLASS)::escape($_POST['text']);
            $comment->iduser =    Login::user()->id;
            $comment->idphoto =   null;
            $comment->idplace =   $idplace;

            try {
                $comment->save();                

                Session::flash("success", "Guardado del comentario correcto.");
                
                //$place = Place::getById($idplace);
                //$this->loadView("place/show", ['place' => $place]);
                redirect("/place/show/$idplace");
            } catch(SQLException $e) {
                Session::flash("error", "No se pudo guardar el comentario $comment->text.");

                if(DEBUG) {
                    throw new Exception($e->getMessage());
                } else {
                    redirect("/place/show/$idplace");
                }
            }
        }

        // Para guardar comentarios de las fotos
        public function storephoto(int $idplace = 0, int $idphoto = 0) {
            Auth::oneRole(['ROLE_USER']);

            if(empty($_POST['guardar'])) {
                throw new Exception("No se recibió el formulario.");
            }

            $comment = new Comment();

            $comment->text =      (DB_CLASS)::escape($_POST['text']);
            $comment->iduser =    Login::user()->id;
            $comment->idphoto =   $idphoto;
            $comment->idplace =   $idplace;

            try {
                $comment->save();                

                Session::flash("success", "Guardado del comentario correcto.");
                
                //$place = Place::getById($idplace);
                //$this->loadView("place/show", ['place' => $place]);
                redirect("/photo/show/$idphoto");
            } catch(SQLException $e) {
                Session::flash("error", "No se pudo guardar el comentario $comment->text.");

                if(DEBUG) {
                    throw new Exception($e->getMessage());
                } else {
                    redirect("/photo/show/$idphoto");
                }
            }
        }

        // Para borrar comentarios en los lugares
        public function deleteplace(int $id = 0) {
            Auth::oneRole(['ROLE_USER', 'ROLE_MODERATOR', 'ROLE_ADMIN']);

            if(!Login::oneRole(['ROLE_USER', 'ROLE_MODERATOR', 'ROLE_ADMIN'])) {
                Session::error("No tienes permiso para hacer esto.");
                redirect('/login');
            }

            if(!$id) {
                throw new Exception("No se indicó el lugar a borrar.");
            }

            $comment = Comment::getById($id);
            
            if(!$comment) {
                throw new Exception("No existe el comentario $id.");                    
            }

            $place = Place::getById($comment->idplace);
            $photo = null;

            $this->loadView("comment/delete", [
                                               'comment' => $comment,
                                               'place' => $place,
                                               'photo' => $photo
                                              ]);
        }

        // Para borrar comentarios en los lugares
        public function destroyplace(int $id= 0, int $idplace = 0) {
            if(empty($_POST['borrar'])) {
                throw new Exception("No se recibió la confirmación.");
            }
            
            $comment = Comment::getById($id);

            if(!$comment) {
                throw new Exception("No existe el comentario $id.");
            }
            
            try {                
                $comment->deleteObject();             
                                
                Session::flash("success", "Se ha borrado el comentario.");
                redirect("/place/show/$idplace");

            } catch(SQLException $e) {
                Session::flash("error", "No se pudo borrar el comentario $comment->id.");

                if(DEBUG) {
                    throw new Exception($e->getMessage());
                } else {
                    redirect("/place/show/$idplace");
                }
            }
        }

        // Para borrar comentarios en las fotos
        public function deletephoto(int $id = 0) {
            Auth::oneRole(['ROLE_USER', 'ROLE_MODERATOR', 'ROLE_ADMIN']);

            if(!Login::oneRole(['ROLE_USER', 'ROLE_MODERATOR', 'ROLE_ADMIN'])) {
                Session::error("No tienes permiso para hacer esto.");
                redirect('/login');
            }

            if(!$id) {
                throw new Exception("No se indicó el lugar a borrar.");
            }

            $comment = Comment::getById($id);
            
            if(!$comment) {
                throw new Exception("No existe el comentario $id.");                    
            }

            $place = Place::getById($comment->idplace);
            $photo = Photo::getById($comment->idphoto);

            $this->loadView("comment/delete", [
                                               'comment' => $comment,
                                               'place' => $place,
                                               'photo' => $photo
                                              ]);
        }

        // Para borrar comentarios en las fotos
        public function destroyphoto(int $id= 0, int $idphoto = 0) {
            if(empty($_POST['borrar'])) {
                throw new Exception("No se recibió la confirmación.");
            }
            
            $comment = Comment::getById($id);

            if(!$comment) {
                throw new Exception("No existe el comentario $id.");
            }
            
            try {                
                $comment->deleteObject();             
                                
                Session::flash("success", "Se ha borrado el comentario.");
                redirect("/photo/show/$idphoto");

            } catch(SQLException $e) {
                Session::flash("error", "No se pudo borrar el comentario $comment->id.");

                if(DEBUG) {
                    throw new Exception($e->getMessage());
                } else {
                    redirect("/photo/show/$idphoto");
                }
            }
        }

    }