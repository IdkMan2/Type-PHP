<?php
  namespace App\Bootstrap\Utils;
  
  use LogicException;

  class ClassHelpers {
  
    public static function getNameFromObject(object $object): string {
      $className = get_class($object);
  
      return self::getName($className);
    }
    
    public static function getNameFromClass(string $className): string {
      if(!class_exists($className))
        throw new LogicException("Class with name `{$className}` do not exists.");
  
      return self::getName($className);
    }
    
    private static function getName(string $className): string {
      return 'c' === $className[0] && 0 === strpos($className, "class@anonymous\0") ? get_parent_class($className).'@anonymous' : $className;
    }
    
  }