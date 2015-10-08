<?php

namespace Fpradas\TelegramBotBundle\Objects\Factory;

class AbstractObjectFactory
{

    const FACTORY_INTERFACE = 'Fpradas\TelegramBotBundle\Objects\Interfaces\ObjectInterface';

    private $factoryClass;

    /**
     * @param mixed $factoryClass
     */
    public function setFactoryClass($factoryClass)
    {
        $this->factoryClass = $factoryClass;
    }

    public function create(array $data = [])
    {
        $class = $this->factoryClass;
        $reflectionClass = new \ReflectionClass($class);

        if(!$reflectionClass->isSubclassOf(self::FACTORY_INTERFACE)){
            throw new \Exception('This abstract factory only can build instances of '.self::FACTORY_INTERFACE);
        }

        $reflectionMethod = $reflectionClass->getConstructor();

        $reflectionMethod->getParameters();

        $instance = new $class($data);

        return $instance;

    }
}