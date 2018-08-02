<?php


namespace SzuniSoft\Mnb\Laravel\Tests\Client;


use Illuminate\Support\Facades\Cache;
use Mockery;
use SzuniSoft\Mnb\Laravel\Client;
use SzuniSoft\Mnb\Model\Currency;

class TestCase extends \Orchestra\Testbench\TestCase {

    /**
     * @var Mockery\MockInterface
     */
    protected $mnb;

    /**
     * @var Client
     */
    protected $client;

    /**
     * @var int
     */
    protected $timeout;

    /**
     * @var array
     */
    protected $currencyList = ['EUR', 'BGP'];

    /**
     * @var Currency[]
     */
    protected $exchangeRateList;

    protected function setUp()
    {
        parent::setUp();

        $this->timeout = 10;
        $store = Cache::store();
        $this->mnb = Mockery::mock(\SzuniSoft\Mnb\Client::class);
        $this->client = new Client($this->mnb, $store, $this->timeout);

        $this->exchangeRateList = [
            new Currency('EUR',1, 300)
        ];
    }

}