<?php

namespace App\Support\Storage;

use App\Support\Storage\Contracts\StorageInterface;
use Countable;


class SessionStorage implements StorageInterface, Countable
{
    private $bucket;

    public function __construct($bucket = 'default')
    {
        $this->bucket = $bucket;
    }

    /**
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function get($index)
    {
        return session()->get($this->bucket . '.' . $index);
    }

    public function set($index, $value)
    {
        session()->put($this->bucket . '.' . $index, $value);
    }

    /**
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function all()
    {
        return session()->get($this->bucket) ?? [];
    }

    public function exists($index): bool
    {
        return session()->has($this->bucket . '.' . $index);
    }

    public function unset($index)
    {
        session()->forget($this->bucket . '.' . $index);
    }

    public function clear()
    {
        session()->forget($this->bucket);
    }

    /**
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function count(): int
    {
        return count($this->all());
    }
}
