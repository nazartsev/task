<?php

namespace App\Service\Rabbit;

use Portiny\RabbitMQ\Queue\AbstractQueue;
use Portiny\RabbitMQ\Queue\QueueBind;

class SitemapQueue extends AbstractQueue
{
    protected function getBindings(): array
    {
        return [new QueueBind('SitemapExchange'),];
    }

    protected function getName(): string
    {
        return 'Sitemap';
    }
}
