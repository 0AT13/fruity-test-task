<?php

namespace App\Helpers;

use Doctrine\ORM\EntityManagerInterface;

class CheckIfExist
{
    /**
     * Create a new record in db if there is no existing one
     * 
     * @param EntityManagerInterface $entityManager
     * @param string $class
     * @param string $value
     * 
     * @return mixed
     */
    public static function createIfNot(EntityManagerInterface $entityManager, string $class, string $value): mixed
    {
        if (!($object = $entityManager->getRepository($class)->findOneByName($value))) {
            $object = new $class;
            $object->setName($value);

            $entityManager->persist($object);
            $entityManager->flush();
        }

        return $object;
    }
}
