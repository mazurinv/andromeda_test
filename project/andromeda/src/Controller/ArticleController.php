<?php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use App\Repository\TagRepository;
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
    public function getArticle($id, ArticleRepository $articleRepository)
    {
        $article = $articleRepository->find($id);
        $article->tags = $article->getTags();
        return $this->json([
            'status' => 'ok',
            'data' => $article
        ]);
    }

    /**
     * @Route("/articlesByTags", name="getArticlesByTags", methods={"POST"})
     */
    public function getArticlesByTags(Request $request, ArticleRepository $articleRepository) {
        $tags = $request->get('tags');
        $tags = array_filter($tags, function ($val) {
            return $val > 0;
        });
        $articles = $articleRepository->getArticlesByTags($tags);
        return $this->json([
            'status' => 'ok',
            'data' => $articles
        ]);
    }

    /**
     * @Route("/article", name="articleEditor", methods={"POST"})
     */
    public function articleEditor(Request $request, ArticleRepository $articleRepository, TagRepository $tagRepository)
    {
        $id = $request->get('id');
        $title = $request->get('title');
        $tags = $request->get('tags');
        $entityManager = $this->getDoctrine()->getManager();

        if (!empty($id)) {
            $article = $articleRepository->find($id);

        } else {
            $article = new Article();
        }

        $tagsToInsert = [];
        foreach ($tags as $tag) {
            if ($tag > 0) {
                $tagsToInsert[$tag] = $tagRepository->find($tag);
            }
        }
        $article->setTags(array_values($tagsToInsert));


        $article->setTitle($title);
        $entityManager->persist($article);
        $entityManager->flush();

        $article->tags = $article->getTags();
        return $this->json([
            'status' => 'ok',
            'id' => $article
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
