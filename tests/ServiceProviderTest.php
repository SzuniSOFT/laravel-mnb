<?php


namespace SzuniSoft\Mnb\Laravel\Tests;


use Orchestra\Testbench\TestCase;
use SzuniSoft\Mnb\Laravel\Client;
use SzuniSoft\Mnb\Laravel\Facade\Mnb;
use SzuniSoft\Mnb\Laravel\MnbServiceProvider;

class ServiceProviderTest extends TestCase {

    protected function getPackageProviders($app)
    {
        return [
            MnbServiceProvider::class
        ];
    }

    /** @test */
    public function it_registers_client()
    {
        $this->assertInstanceOf(Client::class, $this->app->make(Client::class));
    }

    /** @test */
    public function client_is_accessible_by_facade()
    {
        $this->assertInstanceOf(Client::class, Mnb::getFacadeRoot());
    }

    /** @test */
    public function client_is_accessible_by_alias()
    {
        $this->assertInstanceOf(Client::class, $this->app->make('mnb.client'));
    }

}