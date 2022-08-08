<?php

namespace App\EntityListener;

use App\Entity\Article;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\String\Slugger\SluggerInterface;

class ArticleEntityListener
{
    /**
     * @var SluggerInterface
     */
    private SluggerInterface $slugger;

    /**
     * @param SluggerInterface $slugger
     */
    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    /**
     * @param Article $article
     * @param LifecycleEventArgs $event
     */
    public function prePersist(Article $article, LifecycleEventArgs $event)
    {
        $article->computeSlug($this->slugger);
    }

    /**
     * @param Article $article
     * @param LifecycleEventArgs $event
     */
    public function preUpdate(Article $article, LifecycleEventArgs $event)
    {
        $article->computeSlug($this->slugger);
    }
}