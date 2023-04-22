<?php

    class UserController extends Controller{        

        public function home(){
            Auth::check();

            $user = Login::user();
            $places = $user->hasMany('Place');


            //Introduce en cada lugar (place) la primera foto del listado de fotos de cada lugar.
            foreach($places as $place) {                
                //$place->photo = $place->hasMany('Photo')[0] ?? null;
                $place->photo = $place->hasMany('Photo')[0]->file ?? null;
            }

            $this->loadView('user/home', [
                                          'user'   => Login::user(),
                                          'places' => $places
                                         ]);
        }
        
        
        public function create() {
            //global $roles;

            //if(!Auth::check()) {
            if(!Login::oneRole(['ROLE_USER', 'ROLE_MODERATOR', 'ROLE_ADMIN'])) {
                $this->loadView('user/create', []); //['roles' => $roles]);
            } else {
                Session::flash('error', "No tienes permiso para crear usuarios.");
                redirect("/"); //$this->home();
            }            
        }


        public function registered(string $email = '') {
            header('Content-Type: application/json');
            $response = new stdClass();
            
            $response->status = "OK";
            $response->registered = User::checkEmail($email);

            echo json_encode($response);
        }


        public function store() {
            if(empty($_POST['guardar'])) {
                throw new Exception('No se recibió el formulario');
            }

            $user = new User();

            $user->password = md5($_POST['password']);
            $repeat = md5($_POST['repeatpassword']);

            if($user->password != $repeat) {
                throw new Exception("Las claves no coinciden");
            }

            $user->displayname = (DB_CLASS)::escape($_POST['displayname']);
            $user->email =       (DB_CLASS)::escape($_POST['email']);
            $user->phone =       (DB_CLASS)::escape($_POST['phone']);
            $user->password =    md5((DB_CLASS)::escape($_POST['password']));
            $user->addRole('ROLE_USER');            

            /*
            $errores = $user->erroresDeValidacion();

            if(sizeof($errores)) {
                throw new Exception(join("<br>", $errores));
            }
            */

            try {
                $user->save();

                if(Upload::arrive('picture')) {
                    $user->picture = Upload::save(
                                                    'picture',
                                                    '../public/'.USER_IMAGE_FOLDER,
                                                    true,
                                                    0,
                                                    'image/*',
                                                    'user_'
                                                );

                    $user->update();
                }

                Session::success("Nuevo usuario $user->displayname creado con éxito.");                
                $this->home(); //redirect("/");
                
            } catch(SQLException $e) {
                Session::error("Se produjo un error al guardar el usuario $user->displayname.");

                if(DEBUG) {
                    throw new Exception($e->getMessage());
                } else {
                    redirect('/user/create');
                }
            } catch(UploadException $e) {
                Session::warning("El usuario se guardó correctamente, pero no se pudo subir el fichero de imagen.");

                if(DEBUG) {
                    throw new Exception($e->getMessage());
                } else {
                    redirect('/');
                }
            }
        }

    }

