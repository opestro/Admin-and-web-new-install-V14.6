<?php

namespace App\Library;

class Receiver
{
    private $name;
    private $image;

    public function __construct($name, $image)
    {
        $this->name = $name;
        $this->image = $image;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getImage()
    {
        return $this->image;
    }

    public function information(): array
    {
        return [
            'name' => $this->getName(),
            'image' => $this->getImage(),
        ];
    }
}
