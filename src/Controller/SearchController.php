<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class SearchController extends AbstractController
{
    /**
     * @Route("/search", name="search", methods={"POST"})
     */
    public function search(Request $request, ArticleRepository $repo, PaginatorInterface $paginator)
    {


        $content = $request->get('_contenu');
        $query = $repo->findLike($content);

        $articles =  $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            10
        );


        return $this->render('search/index.html.twig', [
            'articles' => $articles,
        ]);
    }
}
