<?php


namespace SzuniSoft\Mnb\Laravel\Facade;


use Illuminate\Support\Facades\Facade;
use SzuniSoft\Mnb\Laravel\Client;

class Mnb extends Facade {

    protected static function getFacadeAccessor()
    {
        return Client::class;
    }


}