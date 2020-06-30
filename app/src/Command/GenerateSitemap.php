<?php
namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use OldSound\RabbitMqBundle\RabbitMq\Producer;

class GenerateSitemap extends Command
{
    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'app:sitemap-generate';

    private $producer;

    public function __construct(Producer $producer)
    {
        $this->producer = $producer;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName($this::$defaultName)
            ->setDescription('Generate Sitemap');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->producer->publish('');
    }
}
