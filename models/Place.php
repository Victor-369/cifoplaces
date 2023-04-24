<?php
    class Place extends Model {
        public function erroresDeValidacion():array {
            $errores = [];

            if(strlen($this->name) < 5 || strlen($this->name) > 128) {
                $errores[] = "Error en el nombre. Muy corto o muy largo.";
            }

            if(strlen($this->type) < 5 || strlen($this->type) > 128) {
                $errores[] = "Error en el tipo. Muy corto o muy largo.";
            }

            if(strlen($this->location) < 5 || strlen($this->location) > 128) {
                $errores[] = "Error en la localización. Muy corto o muy largo.";
            }

            return $errores;
        }
        
        public function hasManyComments():array {
            
            $consulta = "SELECT * FROM comments WHERE idplace = ". $this->id . " and idphoto is null order by created_at DESC";
            
            return (DB_CLASS)::selectAll($consulta, 'Comment');
        }
                
        public static function ownerComment(int $idcomment = 0):object {
            
            $consulta = "SELECT u.displayname
                        FROM comments c
                            left join users u on (c.iduser = u.id)
                        WHERE c.id = $idcomment";

            return (DB_CLASS)::select($consulta, 'stdClass');
        }
    }