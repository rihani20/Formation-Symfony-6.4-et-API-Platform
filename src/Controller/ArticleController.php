<?php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use App\Repository\UserRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class ArticleController extends AbstractController
{
    public function __construct(private ArticleRepository $articleRepository ,
    private UserRepository $userRepository ,
    private NormalizerInterface $normalizerInterface )
    {
        
    }
    #[Route('/article', name: 'app_article', methods: 'GET')]
    public function index(): JsonResponse
    {
        return $this->json($this->normalizerInterface->normalize($this->articleRepository->findAll(), context: [
            'groups' => ['article:read']
        ]));
    }

    #[Route('/article', name: 'app_article_new', methods: 'POST')]
    public function new(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $article = new Article();
        $article->setTitle($data['title']);
        $article->setDescription($data['description']);
        $article->setSlug($data['slug']);
        $article->setUser($this->userRepository->find($data['user']));
        $this->articleRepository->save($article, true);


        return $this->json($this->normalizerInterface->normalize($article, context: [
            'groups' => ['article:read']
        ]));
    }

    #[Route('/article/{id}', name: 'app_article_detail', methods: 'GET')]
    public function detail(?Article $article): JsonResponse
    {
        if (!$article) {
            $this->json([
                "message" => "Article not found",
                "status" => false
            ])  ; 
         }

        return $this->json($this->normalizerInterface->normalize($article, context: [
            'groups' => ['article:read']
        ]));
    }

    
}
