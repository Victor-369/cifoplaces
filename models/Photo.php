<?php
    class Photo extends Model {







        
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
    }