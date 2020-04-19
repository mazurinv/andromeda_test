<?php

namespace App\Controller;

use App\Entity\Tag;
use App\Repository\TagRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class TagController extends AbstractController
{
    /**
     * @Route("/tag", name="tag", methods={"GET"})
     */
    public function index(TagRepository $tagRepository)
    {
        return $this->json([
            'status' => 'ok',
            'data' => $tagRepository->findAll()
        ]);
    }

    /**
     * @Route("/tag/{tag}", name="getTag", methods={"GET"}, requirements={"tag"="\d+"})
     */
    public function getTag(Tag $tag)
    {
        return $this->json([
            'status' => 'ok',
            'data' => $tag
        ]);
    }

    /**
     * @Route("/tag", name="tagEditor", methods={"POST"})
     */
    public function tagEditor(Request $request, TagRepository $tagRepository, EntityManagerInterface $entityManager)
    {
        $id = $request->get('id', 0);
        $name = $request->get('name', '');

        if (!empty($id)) {
            $tag = $tagRepository->find($id);
            if (empty($tag)) {
                return $this->json([
                    'status' => 'error',
                    'message' => 'Tag not found'
                ]);
            }
        } else {
            $tag = new Tag();
        }

        $tag->setName($name);
        $entityManager->persist($tag);
        $entityManager->flush();

        return $this->json([
            'status' => 'ok',
            'id' => $tag->getId()
        ]);
    }

    /**
     * @Route("/tag/{tag}", name="removeTag", methods={"POST"}, requirements={"tag"="\d+"})
     */
    public function removeTag(Tag $tag, EntityManagerInterface $entityManager)
    {
        $entityManager->remove($tag);
        $entityManager->flush();
        return $this->json([
            'status' => 'ok'
        ]);
    }
}
