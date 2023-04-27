<?php
    class Photo extends Model {
        
        public function erroresDeValidacion():array {
            $errores = [];

            if(strlen($this->name) < 5 || strlen($this->name) > 128) {
                $errores[] = "Error en el nombre. Muy corto o muy largo.";
            }

            return $errores;
        }

        public static function hasManyComments(int $idplace = 0, int $idphoto = 0):array {
            $consulta = "SELECT c.*, u.displayname as owner 
                        FROM comments c 
                        left join users u on (c.iduser = u.id)
                        WHERE c.idplace = $idplace 
                        and c.idphoto = $idphoto order by created_at DESC";

            return (DB_CLASS)::selectAll($consulta, 'Comment');
        }
    }