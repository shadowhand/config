<?php

namespace Sinergi\Config;

use ReflectionClass;
use Interop\Container\ContainerInterface;

class Container implements ContainerInterface
{
    /**
     * @var ContainerInterface
     */
    private $diContainer;

    /**
     * @var array
     */
    private $container = [];

    /**
     * @var array
     */
    private $instances = [];

    public function __construct(ContainerInterface $container)
    {
        $this->diContainer = $container;
    }

    public function get($id)
    {
        if (isset($this->instances[$id])) {
            return $this->instances[$id];
        }

        $className = $this->getContainerValue($id);
        if (is_callable($className)) {
            return $className($this->diContainer, $id);
        }
        $class = new $className;
        return $this->instances[$id] = $class($this->diContainer);
    }

    public function has($id)
    {
        if (isset($this->instances[$id])) {
            return true;
        }
        return $this->getContainerValue($id) !== null;
    }

    protected function getContainerValue($id)
    {
        if (isset($this->container[$id])) {
            return $this->container[$id];
        }
        foreach ($this->container as $alias => $concrete) {
            $class = new ReflectionClass($id);
            if (false === $class) {
                return null;
            }
            do {
                $name = $class->getName();
                if ($alias == $name) {
                    return $concrete;
                }
                $interfaces = $class->getInterfaceNames();
                if (is_array($interfaces) && in_array($alias, $interfaces)) {
                    return $concrete;
                }
                $class = $class->getParentClass();
            } while (false !== $class);
            return null;
        }
        return null;
    }

    public function add($alias, $className, $target = null)
    {
        if (null === $target) {
            $target = $className;
        } else {
            $this->container[$className] = $target;
        }
        $this->container[$alias] = $target;
    }
}
