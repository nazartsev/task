<?php

namespace App\Service\Rabbit;

use Portiny\RabbitMQ\Exchange\AbstractExchange;

class SitemapExchange extends AbstractExchange
{
    /**
     * @return string
     */
    protected function getType(): string
    {
        return self::TYPE_FANOUT;
    }

    /**
     * @return string
     */
    protected function getName(): string
    {
        return 'SitemapExchange';
    }
}