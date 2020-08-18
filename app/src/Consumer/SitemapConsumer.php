<?php
namespace App\Consumer;


use App\Entity\NewsEntity;
use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use PhpAmqpLib\Message\AMQPMessage;
use Psr\Container\ContainerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class SitemapConsumer implements ConsumerInterface
{

    private $container;
    private $router;

    public function __construct(ContainerInterface $container, UrlGeneratorInterface $router, ParameterBagInterface $parameters)
    {
        $this->container = $container;
        $this->router = $router;
        $this->router->getContext()->setBaseUrl($parameters->get('host'));
    }

    public function execute(AMQPMessage $msg)
    {
        $this->sitemapCreate();
    }

    private function sitemapCreate() {

        $eManager = $this->container->get('doctrine')->getManager();

        $connection = $eManager->getConnection();
        if ($connection->ping() === false) {
            $connection->close();
            $connection->connect();
        }

        $simpleXml = new \SimpleXMLElement('<urlset />');
        $simpleXml->addAttribute('xmlns', 'http://www.sitemaps.org/schemas/sitemap/0.9');
        $indexPageRoot = $simpleXml->addChild('url');
        $indexPageRoot->addChild('loc', $this->router->generate('news_entity_index'));
        foreach($eManager->getRepository(NewsEntity::class)->findForSitemap() ?? [] as $article) {
            $child = $simpleXml->addChild('url');
            $child->addChild('loc', $this->router->generate('news_entity_show', ['slug' => $article->getSlug()]));
            $child->addChild('lastmod', (!empty($article->getUpdatedAt()) && $article->getUpdatedAt() instanceof \DateTime) ?
                $article->getUpdatedAt()->format('Y-m-d\TH:i:s+00:00') : '');
        }
        file_put_contents(realpath('./public/sitemap.xml'), $simpleXml->asXML());
    }
}
