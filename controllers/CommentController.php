<?php
    
    class CommentController extends Controller{


        public function create(int $idplace = 0) {
            //Auth::oneRole(['ROLE_LIBRARIAN', 'ROLE_ADMIN']);
            Auth::oneRole(['ROLE_USER']);

            if(!Login::oneRole(['ROLE_USER'])) {
                Session::error("No tienes permiso para hacer esto.");
                redirect('/login');
            }

            $place = Place::getById($idplace);

            $this->loadView('comment/create', ['place' => $place]);
        }

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

    }