<?php

namespace App\EntityListener;

use App\Entity\Article;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\String\Slugger\SluggerInterface;

#[AsEntityListener(event: Events::prePersist, entity: Article::class)] //por executer ces fonctions 
#[AsEntityListener(event: Events::preUpdate, entity: Article::class)] //por executer ces fonctions 
class ArticleEntityListner 
{
    public function __construct(private SluggerInterface $slugger, 
    private Security $security)
    {
        
    }

    public function prePersist(Article $article, LifecycleEventArgs $event) {
        $article->computeSlug($this->slugger);
        $article->setUser($this->security->getUser());
    }

    public function preUpdate(Article $article, LifecycleEventArgs $event) {
        $article->computeSlug($this->slugger);
        $article->setUser($this->security->getUser());
    }


}