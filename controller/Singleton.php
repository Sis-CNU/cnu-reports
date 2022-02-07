<?php

namespace Controller;

/**
 * Trait Patrón de Diseño Singleton
 */
trait Singleton
{
   /**
    * Instancia Singleton
    * @var Singleton
    */
   private static $_instance = NULL;

   /**
    * Constructor privado
    * Se evita utilizar el constructor para crear
    * una instancia de la clase.
    *
    * @return void
    */
   private function __construct()
   {
   }

   /**
    * Método mágico privado __clone, 
    * previniendo la clonación de la instancia Singleton.
    *
    * @return void
    * 
    */
   private function __clone()
   {
   }

   /**
    * Método mágico privado __wakeup, 
    * evitando la deserialización de la instancia Singleton.
    *
    * @return void
    * 
    */
   private function __wakeup()
   {
   }

   /**
    * Instancia Singleton
    *
    * @return Singleton
    * 
    */
   public static function getInstance()
   {
      if (!(self::$_instance instanceof self)) {
         self::$_instance = new self();
      }
      return self::$_instance;
   }
}
