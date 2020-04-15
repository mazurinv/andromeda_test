<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use App\Repository\TagRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(TagRepository $tagRepository, ArticleRepository $articleRepository)
    {
        return $this->render('index.twig', [
            'tags' => $tagRepository->findAll(),
            'articles' => $articleRepository->findAll()
        ]);
    }
}
