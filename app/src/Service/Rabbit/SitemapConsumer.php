<?php

namespace App\Service\Rabbit;

use Bunny\Message;
use Portiny\RabbitMQ\Consumer\AbstractConsumer;
use Symfony\Component\Process\Process;

class SitemapConsumer extends AbstractConsumer
{
    /**
     * @var string
     */
    private $consolePath;

    /**
     * @param Message $message
     *
     * @return int
     */
    protected function process(Message $message): int
    {
        $this->generate();

        return self::MESSAGE_ACK;
    }

    /**
     * @return void
     */
    private function generate(): void
    {
        $interpreter = $this->getInterpreter();

        $command = [
            $interpreter,
            $this->consolePath,
            'sitemap-generate',
        ];

        $dropProcess = new Process($command);
        $dropProcess->start();

        while (Process::STATUS_TERMINATED !== $dropProcess->getStatus()) {
            sleep(10);
        }
    }

    /**
     * Возвращает путь к интерпретатору PHP версии 7.1 или выше
     *
     * @return string
     */
    private function getInterpreter(): string
    {
        $interpreters = explode(' ', shell_exec('whereis php'));

        foreach ($interpreters as $interpreter) {
            if (preg_match(
                '/^PHP 7.[1-9]{1}.+/u',
                shell_exec("$interpreter -version 2>/dev/null")
            )) {
                return $interpreter;
            }
        }

        return 'php';
    }

    /**
     * @return string
     */
    protected function getQueueName(): string
    {
        return 'Sitemap';
    }
}