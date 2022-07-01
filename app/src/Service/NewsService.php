<?php

namespace App\Service;

use App\Entity\News;
use App\Service\NewsProvider\NewsProviderException;
use App\Service\NewsProvider\RbcProvider;
use Doctrine\Persistence\ManagerRegistry;

class NewsService
{
    private ManagerRegistry $managerRegistry;

    public function __construct(
        ManagerRegistry $managerRegistry
    )
    {
        $this->managerRegistry = $managerRegistry;
    }

    /**
     * @throws NewsProviderException
     */
    public function parseNews(
        string $source
    ): void
    {
        $entityManager = $this->managerRegistry->getManager();

        $allNewsGuid = $entityManager->getRepository(News::class)->getAllGuids($source);

        $provider = $this->getProvider($source);
        $news = $provider->getNews($allNewsGuid);

        foreach ($news as $item) {
            $entityManager->persist($item);
        }

        $entityManager->flush();
    }

    /**
     * @throws NewsProviderException
     */
    private function getProvider(string $source): RbcProvider
    {
        switch ($source) {
            case 'rbc':
                return new RbcProvider();
            default:
                throw new NewsProviderException('Неизвестный провайдер новостей');
        }
    }
}