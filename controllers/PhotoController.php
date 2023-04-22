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

    }