<?php

declare(strict_types=1);

namespace Kawanamiyuu\HtbFeed;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use Kawanamiyuu\HtbFeed\Framework\FrameworkModule;
use Ray\Di\AbstractModule;
use Ray\Di\Scope;

class AppModule extends AbstractModule
{
    protected function configure(): void
    {
        $this->install(new FrameworkModule());

        $this->bind(ClientInterface::class)
            ->to(Client::class)->in(Scope::SINGLETON);
    }
}
