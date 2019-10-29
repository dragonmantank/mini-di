<?php

namespace MiniDI;

use Psr\Container\ContainerInterface;

class Container implements ContainerInterface
{
    protected $factories;
    protected $config;

    public function __construct($factories = [], $config = [])
    {
        $this->factories = $factories;
        $this->$config = $config;
    }

    public function getConfig()
    {
        return $this->config;
    }

    public function get($class)
    {
        if (isset($this->factories[$class])) {
            return (new $this->factories[$class])($this);
        } else {
            return $this->build($class);
        }
    }

    /**
     * Not implemented
     */
    public function has($class)
    {
        return true;
    }

    protected function build($class)
    {
        $arguments = [];
        $refClass = new \ReflectionClass($class);
        $constructor = $refClass->getConstructor();
        if ($constructor) {
            $parameters = $constructor->getParameters();
            if (count($parameters)) {
                foreach ($parameters as $param) {
                    if ($param->getClass()) {
                        $arguments[] = $this->get($param->getClass()->getName());
                    } else {
                        $arguments[] = $this->getConfig()[$param->name];
                    }
                }
                return new $class(...$arguments);
            } else {
                return new $class;
            }
        } else {
            return new $class;
        }
    }
}