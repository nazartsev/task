<?php

namespace App\Service\Rabbit;

use Portiny\RabbitMQ\BunnyManager;

class Declarator
{
    /**
     * @var BunnyManager
     */
    private $bunnyManager;

    /**
     * @param BunnyManager $bunnyManager
     */
    public function __construct(BunnyManager $bunnyManager)
    {
        $this->bunnyManager = $bunnyManager;
    }

    /**
     * @return void
     */
    public function declare(): void
    {
        $this->bunnyManager->declare();
    }
}
