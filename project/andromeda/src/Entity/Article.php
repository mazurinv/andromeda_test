<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\JoinTable;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ArticleRepository")
 */
class Article
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * Many Articles have Many Tags.
     * @ManyToMany(targetEntity="Tag", inversedBy="articles")
     * @JoinTable(name="articles_tags")
     */
    private $tags;

    public function __construct(string $title) {
        $this->tags = new \Doctrine\Common\Collections\ArrayCollection();
        $this->title = $title;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }


    public function getTags()
    {
        return $this->tags->toArray();
    }

    /**
     * @param array $tags
     */
    public function setTags($tags)
    {
        $this->tags = new ArrayCollection($tags);
    }
}
