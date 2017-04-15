<?php

namespace jlcd\Cielo\Resources;

class CieloOrder
{
    private $id = null;

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }
}
