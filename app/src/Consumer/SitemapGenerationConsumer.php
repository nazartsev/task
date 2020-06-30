<?php
namespace App\Consumer;

use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use PhpAmqpLib\Message\AMQPMessage;
use App\Repository\NewsRepository;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Environment as Twig;

/**
 * Class NotificationConsumer
 */
class SitemapGenerationConsumer implements ConsumerInterface
{
    /** @var NewsRepository $repo */
    private $repo;

    /** @var Twig $twig */
    private $twig;

    /** @var UrlGeneratorInterface $router */
    private $router;

    public function __construct(NewsRepository $repo, Twig $twig, UrlGeneratorInterface $router)
    {
        $this->repo = $repo;
        $this->twig = $twig;
        $this->router = $router;
    }

    /**
     * @var AMQPMessage $msg
     * @return void
     */
    public function execute(AMQPMessage $msg)
    {
        $news = $this->repo->findFilteredList(null, 0);

        $urls = [];
        foreach ($news as $newsEntity) {
            $urls[] = [
                'url' =>  $this->router->generate('front_news_by_slug', [
                    'slug' => $newsEntity->getSlug()
                ]),
                'lastmod' => $newsEntity->getUpdatedAt()->format('Y-m-d H:i:s')
            ];
        }
        
        $sitemap = $this->twig->render('sitemap.xml.twig', [
            'urls' => $urls,
        ]);

        $filename = __DIR__.'/../../public/sitemap.xml';

        file_put_contents($filename, $sitemap);

        chmod($filename, 0755);
        return;
    }
}
