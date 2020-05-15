<?php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use Knp\Component\Pager\PaginatorInterface;
use Normalizer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

class ArticleController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     */
    public function index(PaginatorInterface $paginator, Request $request, ArticleRepository $repo)
    {

        $query = $repo->findAllQuery();
        $articles =  $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            10
        );
        return $this->render('article/list.html.twig', ['articles' => $articles]);
    }

    /**
     * @Route("/article/show/{slug}", name="article_show")
     */
    public function show(Article $article)
    {
        return $this->render('article/index.html.twig', ['article' => $article]);
    }

    /**
     * @Route("/articleAjax/{id}", name="articleAjax" )
     */
    public function articleAjax(Article $article): Response
    {


        return $this->json(['title' => $article->getTitle(), 200]);
    }
}
