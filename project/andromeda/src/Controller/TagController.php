<?php

namespace App\Controller;

use App\Entity\Tag;
use App\Repository\TagRepository;
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
     * @Route("/tag/{tag}", name="getTag", methods={"GET"})
     */
    public function getTag($tag, TagRepository $tagRepository)
    {
        return $this->json([
            'status' => 'ok',
            'data' => $tagRepository->find($tag)
        ]);
    }

    /**
     * @Route("/tag", name="tagEditor", methods={"POST"})
     */
    public function tagEditor(Request $request, TagRepository $tagRepository)
    {
        $id = $request->get('id');
        $name = $request->get('name');

        $entityManager = $this->getDoctrine()->getManager();

        if (!empty($id)) {
            $tag = $tagRepository->find($id);
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
}
