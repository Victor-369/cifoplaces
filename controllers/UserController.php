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
                redirect("/login");
                
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
                    redirect('/login');
                }
            }
        }

        public function delete(int $id = 0) {
            Auth::check();

            if(!$id) {
                throw new Exception("No se indicó el usuario a borrar.");
            }

            $user = User::getById($id);

            if(!$user) {
                throw new Exception("No existe el usuario $id.");
            }

            $this->loadView("user/delete", ['user' => $user]);
        }

        public function destroy(int $id = 0) {
            Auth::check();

            if(Login::user()->id != $id || !Login::oneRole(['ROLE_USER', 'ROLE_MODERATOR'])) {
                throw new Exception("no tienes permiso para eliminar el usuario.");
            }

            if(empty($_POST['borrar'])) {
                throw new Exception("No se recibió la confirmación.");
            }

            $user = User::getById($id);

            if(!$user) {
                throw new Exception("No existe el usuario $id.");                
            }

            try {
                User::deleteUser($id);

                $user->deleteObject();

                @unlink('../public/'.USER_IMAGE_FOLDER.'/'.$user->picture);
                                
                Session::flash("success", "Se ha borrado el usuario $user->displayname");
                redirect("/logout");

            } catch(SQLException $e) {
                Session::flash("error", "No se pudo borrar el usuario $user->displayname.");

                if(DEBUG) {
                    throw new Exception($e->getMessage());
                } else {
                    redirect("/user/delete/$id");
                }
            }
        }

        public function edit(int $id = 0) {
            if(Login::user()->id != $id) {
                throw new Exception("No tienes permiso para editar el usuario.");
            }
            
            $user = User::getById($id);

            if(!$user) {
                throw new Exception("No se recibió los datos del usuario.");
            }

            $this->loadView('user/edit', ['user' => $user]);
        }

        public function update(int $id = 0) {
            if(empty($_POST['actualizar'])) {
                throw new Exception('No se recibió el formulario');
            }
            
            $user = User::getById($id);

            $user->displayname =     (DB_CLASS)::escape($_POST['displayname']);
            $user->email =           (DB_CLASS)::escape($_POST['email']);
            $user->phone =           (DB_CLASS)::escape($_POST['phone']);
            
            if($_POST['password'] =! $_POST['repeatpassword']) {
                throw new Exception('Las contraseñas no coinciden.');
            }

            //$user->password = $_POST['password'];
            $user->password = md5($_POST['repeatpassword']);

            try {
                $user->update();

                $secondUpdate = false;
                $oldCover = $user->picture;

                if(Upload::arrive('picture')) {
                    $user->picture = Upload::save(
                                                    'picture',
                                                    '../public/'.USER_IMAGE_FOLDER,
                                                    true,
                                                    0,
                                                    'image/*',
                                                    'user_'
                                                );

                    $secondUpdate = true;
                }

                if(isset($_POST['eliminarpicture']) && $oldCover && !Upload::arrive('picture')) {
                    $user->picture = null;
                    $secondUpdate = true;
                }

                if($secondUpdate) {
                    $user->update();
                    @unlink('../public/'.USER_IMAGE_FOLDER.'/'.$oldCover);
                }

                Session::success("Usuario $user->displayname editado con éxito.");
                Login::set($user); // refresca los datos del usuario por pantalla
                redirect("/user/home");
                
            } catch(SQLException $e) {
                Session::error("Se produjo un error al guardar el usuario $user->displayname.");

                if(DEBUG) {
                    throw new Exception($e->getMessage());
                } else {
                    redirect("/user/home");
                }
            } catch(UploadException $e) {
                Session::warning("El usuario se guardó correctamente, pero no se pudo subir el fichero de imagen.");

                if(DEBUG) {
                    throw new Exception($e->getMessage());
                } else {
                    redirect("/user/home");
                }
            }
        }
    }

