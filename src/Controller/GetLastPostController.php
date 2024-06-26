<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use Nelmio\ApiDocBundle\Annotation\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class GetLastPostController extends AbstractController {

    public function __construct(private ArticleRepository $articleReo , private NormalizerInterface $normalizer)
    {
    
    }
    
    #[Route('/api/articles/last', name: 'article_get_last_post', methods: ['GET'])]
 
        public function getLastArticle(): Response
    {
        return $this->json($this->normalizer->normalize($this->articleReo->findLastArticle(), context: [
            'groups' => ['article:read']
        ]));
    }
}