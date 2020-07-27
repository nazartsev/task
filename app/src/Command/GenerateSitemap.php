<?php

namespace App\Command;

use App\DTO\DisplayData;
use App\Service\NewsService;
use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Environment as Twig;

class GenerateSitemap extends Command
{
    /**
     * @var NewsService
     */
    private $newsService;

    /**
     * @var Twig $twig
     */
    private $twig;

    /**
     * @var UrlGeneratorInterface $router
     */
    private $router;

    /**
     * @param NewsService           $newsService
     * @param Twig                  $twig
     * @param UrlGeneratorInterface $router
     */
    public function __construct(NewsService $newsService, Twig $twig, UrlGeneratorInterface $router)
    {
        parent::__construct();

        $this->newsService = $newsService;
        $this->twig = $twig;
        $this->router = $router;
    }

    /**
     * @return void
     */
    protected function configure(): void
    {
        $this->setName('generate-sitemap')->setDescription('Создает sitemap');
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $news = $this->newsService->listNews(new DisplayData(20, 0));
            $urls = [];

            foreach ($news as $item) {
                $urls[] = [
                    'url' => $this->router->generate(
                        'news_slug',
                        [
                            'slug' => $item->getSlug(),
                        ]
                    ),
                    'lastmod' => $item->getUpdatedAt()->format('Y-m-d H:i:s'),
                ];
            }

            $sitemap = $this->twig->render(
                'sitemap.xml.twig',
                [
                    'urls' => $urls,
                ]
            );

            $filename = __DIR__.'/../../public/sitemap.xml';
            file_put_contents($filename, $sitemap);
            chmod($filename, 0755);
        } catch (Exception $exception) {
            return 0;
        }

        return 1;
    }
}
