<?php


namespace App\EventListener;

use App\Entity\News;
use App\Repository\NewsRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Presta\SitemapBundle\Event\SitemapPopulateEvent;
use Presta\SitemapBundle\Service\UrlContainerInterface;
use Presta\SitemapBundle\Sitemap\Url\UrlConcrete;

class SitemapSubscriber implements EventSubscriberInterface
{
    /**
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;

    /**
     * @var NewsRepository
     */
    private $newsRepository;

    /**
     * @param UrlGeneratorInterface $urlGenerator
     * @param NewsRepository    $blogPostRepository
     */
    public function __construct(UrlGeneratorInterface $urlGenerator, NewsRepository $newsRepository)
    {
        $this->urlGenerator = $urlGenerator;
        $this->newsRepository = $newsRepository;
    }

    /**
     * @inheritdoc
     */
    public static function getSubscribedEvents()
    {
        return [
            SitemapPopulateEvent::ON_SITEMAP_POPULATE => 'populate',
        ];
    }

    /**
     * @param SitemapPopulateEvent $event
     */
    public function populate(SitemapPopulateEvent $event): void
    {
        $this->registerNewsUrls($event->getUrlContainer());
    }

    /**
     * @param UrlContainerInterface $urls
     */
    public function registerNewsUrls(UrlContainerInterface $urls): void
    {
        $news = $this->newsRepository->findAll();

        foreach ($news as $oneNews) {
            $urls->addUrl(
                new UrlConcrete(
                    $this->urlGenerator->generate(
                        'show',
                        ['slug' => $oneNews->getSlug()],
                        UrlGeneratorInterface::ABSOLUTE_URL
                    )
                ),
                'blog'
            );
        }
    }
}