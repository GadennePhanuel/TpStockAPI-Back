<?php

namespace App\Controller;

use App\Entity\Stock;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;

class ArticlesWhitoutBelongsController {
    /**
     * @var EntityManagerInterface
     */
    private $manager;
    /**
     * @var ArticleRepository
     */
    private $articleRepository;

    /**
     * @param EntityManagerInterface $manager
     */
    public function __construct(EntityManagerInterface $manager, ArticleRepository $articleRepository){

        $this->manager = $manager;
        $this->articleRepository = $articleRepository;
    }



    public function __invoke(Stock $data)
    {
        return $this->articleRepository->findAllArticleNotInCurrentStock($data->getId(), $data->getUser()->getId());
    }
}