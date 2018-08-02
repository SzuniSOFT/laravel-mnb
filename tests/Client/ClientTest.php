<?php


namespace SzuniSoft\Mnb\Laravel\Tests\Client;


use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class ClientTest extends TestCase {

    /** @test */
    public function it_can_cache_currency_list()
    {
        Cache::shouldReceive('remember')
            ->with('mnb.currencies', $this->timeout, function () {});

        $this->mnb->shouldReceive('currencies')->andReturn($this->currencyList);

        $this->assertSame($this->currencyList, $this->client->currencies());
    }

    /** @test */
    public function it_can_cache_currency_existence()
    {

        Cache::shouldReceive('remember')
            ->with('mnb.currencies', $this->timeout, function () {});

        $this->mnb->shouldReceive('currencies')->andReturn($this->currencyList);
        $this->assertFalse($this->client->hasCurrency('IRRELEVANT'));
    }

    /** @test */
    public function it_can_cache_exchange_rates()
    {

        Cache::shouldReceive('remember')
            ->with('mnb.currencies.rate', $this->timeout, function () {});

        $this->mnb->shouldReceive('currentExchangeRates')
            ->andReturnUsing(function (&$date) {
                $date = '1999-09-09';
                return $this->exchangeRateList;
            });

        $this->assertSame($this->exchangeRateList, $this->client->exchangeRates());
    }

    /** @test */
    public function current_exchange_rates_offers_carbon_instance_for_date()
    {
        $this->mnb->shouldReceive('currentExchangeRates')
            ->andReturnUsing(function (&$date) {
                $date = '1999-09-09';
            });
        $this->client->currentExchangeRates($date);
        $this->assertInstanceOf(Carbon::class, $date);
    }

    /** @test */
    public function current_exchange_rate_offers_carbon_instance_for_date()
    {
        $code = 'EUR';

        $this->mnb->shouldReceive('currentExchangeRate')
            ->andReturnUsing(function ($code, &$date) {
                $date = '199-09-09';
            });
        $this->client->currentExchangeRate($code, $date);
        $this->assertInstanceOf(Carbon::class, $date);
    }

    /** @test */
    public function it_can_cache_exchange_rate_for_currency()
    {
        $code = 'EUR';

        Cache::shouldReceive('remember')
            ->with("mnb.currencies.rate.$code", $this->timeout, function () {});

        $this->mnb->shouldReceive('currentExchangeRate')
            ->with($code, null)
            ->andReturnUsing(function ($code, &$date) {
                $date = '1993-09-09';
                return $this->exchangeRateList[0];
            });

        $this->assertSame($this->exchangeRateList[0], $this->client->exchangeRate($code));
    }

}