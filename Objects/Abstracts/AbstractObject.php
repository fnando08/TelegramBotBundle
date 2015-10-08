<?php

namespace Fpradas\TelegramBotBundle\Objects\Abstracts;

use Fpradas\TelegramBotBundle\Objects\Interfaces\ObjectInterface;

/**
 * Class AbstractObject
 * @package Fpradas\TelegramBotBundle\Objects
 */
abstract class AbstractObject implements ObjectInterface
{

    /**
     * @var array
     */
    protected $elements;

    protected $rawElements;

    /**
     * @param array $elements
     */
    public function __construct(array $elements = [])
    {
        $this->elements = $this->rawElements = $elements;
    }

    /**
     * @return array
     */
    public function get($key)
    {
        $this->resolveRelation($key);

        return $this->has($key) ? $this->elements[$key] : null;
    }



    public function set($key, $value)
    {
        $this->elements[$key] = $value;
    }

    protected function resolveRelation($key)
    {
        $relations = $this->getRelations();

        $value = $this->has($key) ? $this->elements[$key] : null;

        if (!is_object($value) && isset($relations[$key])) {
            $class = $relations[$key];
            $this->elements[$key] = new $class($value);

        }
    }

    public function has($key)
    {
        return isset($this->elements[$key]);
    }

    /**
     * Map property relatives to appropriate objects.
     *
     * @return array|void
     */
    public function resolveRelations()
    {
        $relations = $this->getRelations();

        if (!$relations || !is_array($relations)) {
            return false;
        }

        foreach ($this->elements as $key => $element) {
            $this->resolveRelation($key);
        }

        return true;
    }

    public function toPlainArray()
    {
        return $this->rawElements;
    }

    /**
     * Returns raw response.
     *
     * @return array|mixed
     */
    public function toArray()
    {
        $this->resolveRelations();
        return $this->elements;
    }


    /**
     * Get Status of request.
     *
     * @return mixed
     */
    public function getStatus()
    {
        return $this->elements['ok'];
    }

    /**
     * @param $name
     * @param $arguments
     *
     * @return bool|AbstractObject|mixed|static
     */
    public function __call($name, $arguments)
    {
        $action = substr($name, 0, 3);

        if ($action === 'get') {
            $property = $this->toSnakeCase(substr($name, 3));
            return $this->get($property);
        }

        return false;
    }

    /**
     * @param $input
     *
     * @return string
     */
    private function toSnakeCase($input)
    {
        if (preg_match('/[A-Z]/', $input) === 0) {
            return $input;
        }
        $pattern = '/([a-z])([A-Z])/';
        $r = strtolower(preg_replace_callback($pattern, function ($a) {
            return $a[1]."_".strtolower($a[2]);
        }, $input));

        return $r;
    }

}
