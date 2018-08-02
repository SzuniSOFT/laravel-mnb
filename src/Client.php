<?php


namespace SzuniSoft\Mnb\Laravel;


use Illuminate\Contracts\Cache\Repository;
use Illuminate\Support\Carbon;

/**
 * Class Client
 * @package SzuniSoft\Mnb\Laravel
 */
class Client {

    /**
     * @var \SzuniSoft\Mnb\Client
     */
    protected $client;

    /**
     * @var Repository
     */
    protected $cache;

    /**
     * @var int
     */
    protected $timeout;

    /**
     * Client constructor.
     * @param \SzuniSoft\Mnb\Client $client
     * @param \Illuminate\Cache\Repository $cache
     * @param $timeout
     */
    public function __construct(
        \SzuniSoft\Mnb\Client $client,
        \Illuminate\Cache\Repository $cache,
        $timeout
    )
    {
        $this->client = $client;
        $this->cache = $cache;
        $this->timeout = $timeout;
    }

    /**
     * @param $date
     */
    protected function normalizeDate(&$date)
    {
        $date = Carbon::createFromFormat('Y-m-d', $date);
    }

    /**
     * @return string[]
     */
    public function currencies()
    {
        return $this->cache->remember('mnb.currencies', $this->timeout, function () {
            return $this->client->currencies();
        });
    }

    /**
     * @param $code
     * @return bool
     */
    public function hasCurrency($code)
    {
        return in_array($code, $this->currencies());
    }

    /**
     * Will return with the cached exchange rate
     *
     * @param $code
     * @param null $date
     * @return null|\SzuniSoft\Mnb\Model\Currency
     */
    public function exchangeRate($code, &$date = null)
    {
        return $this->cache->remember("mnb.currencies.rate.$code", $this->timeout, function () use (&$code, &$date) {
            return $this->currentExchangeRate($code, $date);
        });
    }

    /**
     * @param null $date
     * @return array|\SzuniSoft\Mnb\Model\Currency[]
     */
    public function exchangeRates(&$date = null)
    {
        return $this->cache->remember("mnb.currencies.rate", $this->timeout, function () use (&$date) {
            return $this->currentExchangeRates($date);
        });
    }

    /**
     * Will return with the current exchange rate
     *
     * @param $code
     * @param null|Carbon $date
     * @return null|\SzuniSoft\Mnb\Model\Currency
     */
    public function currentExchangeRate($code, &$date = null)
    {
        $result = $this->client->currentExchangeRate($code, $date);
        $this->normalizeDate($date);
        return $result;
    }

    /**
     * @param null $date
     * @return array|\SzuniSoft\Mnb\Model\Currency[]
     */
    public function currentExchangeRates(&$date = null)
    {
        $result = $this->client->currentExchangeRates($date);
        $this->normalizeDate($date);
        return $result;
    }

}