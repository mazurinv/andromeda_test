<?php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use App\Repository\TagRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class ArticleController extends AbstractController
{
    /**
     * @Route("/article", name="article", methods={"GET"})
     */
    public function index(ArticleRepository $articleRepository, NormalizerInterface $normalizer)
    {
        return $this->json([
            'status' => 'ok',
            'data' => $normalizer->normalize($articleRepository->findAll())
        ]);
    }

    /**
     * @Route("/article/{article}", name="getArticle", methods={"GET"}, requirements={"article"="\d+"})
     */
    public function getArticle(Article $article, NormalizerInterface $normalizer)
    {
        return $this->json([
            'status' => 'ok',
            'data' => $normalizer->normalize($article)
        ]);
    }

    /**
     * @Route("/articlesByTags", name="getArticlesByTags", methods={"POST"})
     */
    public function getArticlesByTags(
        Request $request,
        ArticleRepository $articleRepository,
        NormalizerInterface $normalizer
    ) {
        $tags = $request->get('tags');
        $tags = array_filter($tags, function ($val) {
            return $val > 0 && is_numeric($val);
        });
        $articles = $articleRepository->getArticlesByTags($tags);
        $articles = $normalizer->normalize($articles);
        return $this->json([
            'status' => 'ok',
            'data' => $articles
        ]);
    }

    /**
     * @Route("/article", name="articleEditor", methods={"POST"})
     */
    public function articleEditor(
        Request $request,
        ArticleRepository $articleRepository,
        TagRepository $tagRepository,
        EntityManagerInterface $entityManager,
        NormalizerInterface $normalizer
    ) {
        $id = $request->get('id', 0);
        $title = $request->get('title', '');
        $tags = $request->get('tags', []);
        $tags = array_filter($tags, function ($val) {
            return $val > 0 && is_numeric($val);
        });

        if (!empty($id)) {
            $article = $articleRepository->find($id);
            $article->setTitle($title);
            if (empty($article)) {
                return $this->json([
                    'status' => 'error',
                    'message' => 'Article not found'
                ]);
            }
        } else {
            $article = new Article($title);
        }

        $tagsToInsert = [];
        foreach ($tags as $tag) {
            if ($tag > 0) {
                $tagsToInsert[$tag] = $tagRepository->find($tag);
            }
        }
        $article->setTags(array_values($tagsToInsert));

        $entityManager->persist($article);
        $entityManager->flush();

        return $this->json([
            'status' => 'ok',
            'data' => $normalizer->normalize($article)
        ]);
    }

    /**
     * @Route("/article/{article}", name="removeArticle", methods={"POST"}, requirements={"article"="\d+"})
     */
    public function removeArticle(Article $article, EntityManagerInterface $entityManager)
    {
        $entityManager->remove($article);
        $entityManager->flush();
        return $this->json([
            'status' => 'ok'
        ]);
    }
}
