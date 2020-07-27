<?php

namespace App\Service\Rabbit;

use Portiny\RabbitMQ\Producer\AbstractProducer;
use Portiny\RabbitMQ\Producer\Producer;

class SitemapProducer extends AbstractProducer
{
    /**
     * @var Producer
     */
    private $producer;

    /**
     * @param Producer $producer
     */
    public function __construct(Producer $producer)
    {
        $this->producer = $producer;
    }

    /**
     * @return void
     */
    public function publish(): void
    {
        $body = json_encode(
            [
                'message' => 'запуск команды через раббита',
            ]        );

        $this->producer->produce($this, $body);
    }

    /**
     * @return array
     */
    protected function getHeaders(): array
    {
        return [
            'content-type' => self::CONTENT_TYPE_APPLICATION_JSON,
            'delivery-mode' => self::DELIVERY_MODE_PERSISTENT,
        ];
    }

    /**
     * @return string
     */
    protected function getExchangeName(): string
    {
        return 'SitemapExchange';
    }

    protected function getRoutingKey(): string
    {
        return ''; // так как тип обмена - fanout, то ключи роутинга остаются пустыми
    }
}