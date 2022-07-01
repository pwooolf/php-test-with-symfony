<?php

namespace App\Controller;

use App\Entity\News;
use App\Service\NewsProvider\NewsProviderException;
use App\Service\NewsService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class NewsController extends AbstractController
{
    /**
     * @Route("/{source}", name="news_list")
     * @throws NewsProviderException
     */
    public function newsList(
        NewsService $newsService,
        ManagerRegistry $managerRegistry,
        string $source = 'rbc'
    ): Response
    {

        // @TODO Новости лучше получать по cron, а в контроллере только выводить новости из БД
        $newsService->parseNews($source);

        $newsList = $managerRegistry->getRepository(News::class)->findBy(['provider' => $source], ['id' => 'DESC'], 15);

        return $this->render('news/newsList.html.twig', [
            'newsList' => $newsList,
        ]);
    }

    /**
     * @Route("/news/{id}", name="news", methods={"GET"})
     */
    public function news(
        ManagerRegistry $managerRegistry,
        int $id
    ): Response
    {
        $news = $managerRegistry->getRepository(News::class)->find($id);

        return $this->render('news/news.html.twig', [
            'news' => $news
        ]);
    }
}
