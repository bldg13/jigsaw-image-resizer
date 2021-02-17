<?php

// Source
// https://www.youtube.com/watch?v=qyKt4NF_82g

namespace Bldg13\Helpers\Config;

class Config
{
    protected $data;
    protected $default = null;
    
    public function load($file)
    {
        $this->data = require $file;
    }

    public function get($key, $default = null)
    {
        $this->default = $default;
        $segments = explode('.', $key);
        $data = $this->data;

        foreach ($segments as $segment) {
            if (isset($data[$segment])) {
                $data = $data[$segment];
            } else {
                $data = $this->default;
                break;
            }
        }
        return $data;
    }

    public function exists($key): bool
    {
        return $this->get($key) != $this->default;
    }
}
