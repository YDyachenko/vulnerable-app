<?php

namespace Micro\Db\Adapter;

class Adapter
{

    protected $driver;

    public function __construct($options)
    {
        $dsn = 'mysql:dbname=' . $options['database'] . ';host=' . $options['host'];

        $this->driver = new \PDO($dsn, $options['username'], $options['password'], $options);

        $this->driver->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);
        $this->driver->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    }

    public function query($sql)
    {
        return $this->driver->query($sql);
    }

    public function quote($string)
    {
        return $this->driver->quote($string);
    }

}