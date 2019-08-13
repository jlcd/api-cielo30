<?php
namespace jlcd\Cielo\Requests;

use Cielo\API30\Ecommerce\Request\AbstractRequest;
use Cielo\API30\Ecommerce\Environment;
use Cielo\API30\Merchant;


class BinQuery extends AbstractRequest
{
    private $environment;

    public function __construct(Merchant $merchant, Environment $environment)
    {
        parent::__construct($merchant);
        
        $this->environment = $environment;
    }

    public function execute($bin)
    {
        $url = $this->environment->getApiQueryUrl() . '1/cardBin/'.$bin;
        return $this->sendRequest('GET', $url);
    }

    protected function unserialize($json)
    {
        return json_decode($json, true);
    }
}
