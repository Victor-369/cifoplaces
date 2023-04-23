<?php
    class Photo extends Model {
        
        public function erroresDeValidacion():array {
            $errores = [];

            if(strlen($this->name) < 5 || strlen($this->name) > 128) {
                $errores[] = "Error en el nombre. Muy corto o muy largo.";
            }

            return $errores;
        }


        public function hasManyComments():array {
            
            $consulta = "SELECT * FROM comments WHERE idphoto = ". $this->id . " and idplace = " . $this->idplace . " order by created_at DESC";
            
            return (DB_CLASS)::selectAll($consulta, 'Comment');
        }
    }