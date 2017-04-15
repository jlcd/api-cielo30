<?php

namespace jlcd\Cielo\Responses;

class ResourceResponse
{
    private $data    = null;
    private $status  = null;
    private $message = null;

    public function __construct($status, $message, $data = null)
    {
        $this->setStatus($status);
        $this->setMessage($message);
        $this->setData($data);
    }

    public function setData($data)
    {
        $this->data = $data;
    }
    public function setStatus($status)
    {
        $this->status = $status;
    }
    public function setMessage($message)
    {
        $this->message = $message;
    }

    public function getData()
    {
        return $this->data;
    }
    public function getStatus()
    {
        return $this->status;
    }
    public function getMessage()
    {
        return $this->message;
    }

    public function __toString()
    {
        $json = [];

        $json['data']    = $this->getData();
        $json['status']  = $this->getStatus();
        $json['message'] = $this->getMessage();

        return json_encode($json);
    }
}
