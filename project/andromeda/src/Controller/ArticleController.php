<?php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    /**
     * @Route("/article", name="article", methods={"GET"})
     */
    public function index(ArticleRepository $articleRepository)
    {
        return $this->json([
            'status' => 'ok',
            'data' => $articleRepository->findAll()
        ]);
    }

    /**
     * @Route("/article/{id}", name="getArticle", methods={"GET"})
     */
    public function getTag($id, ArticleRepository $articleRepository)
    {
        return $this->json([
            'status' => 'ok',
            'data' => $articleRepository->find($id)
        ]);
    }

    /**
     * @Route("/article", name="articleEditor", methods={"POST"})
     */
    public function articleEditor(Request $request, ArticleRepository $articleRepository)
    {
        $id = $request->get('id');
        $title = $request->get('title');
        $entityManager = $this->getDoctrine()->getManager();

        if (!empty($id)) {
            $tag = $articleRepository->find($id);
        } else {
            $tag = new Article();
        }

        $tag->setTitle($title);
        $entityManager->persist($tag);
        $entityManager->flush();

        return $this->json([
            'status' => 'ok',
            'id' => $tag->getId()
        ]);
    }

    /**
     * @Route("/article/{id}", name="removeArticle", methods={"POST"})
     */
    public function removeArticle($id, ArticleRepository $articleRepository)
    {
        $articleToRemove = $articleRepository->find($id);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($articleToRemove);
        $entityManager->flush();
        return $this->json([
            'status' => 'ok'
        ]);
    }
}
