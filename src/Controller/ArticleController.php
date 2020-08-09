<?php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Cache\CacheInterface;

class ArticleController extends AbstractController
{

    private $repo;
    private $cache;

    public function __construct(ArticleRepository $repo, CacheInterface $cache)
    {
        $this->repo  = $repo;
        $this->cache = $cache;
    }

    // public function getHomepage()
    // {

    //   return  $this->cache->get('homepage', function (ItemInterface $item) {
    //     $item->expiresAfter(24 * 3600);

    //     return $this->renderHomepage();
    //   });
    // }

    /**
     * @Route("/", name="homepage")
     */
    public function getHomepage()
    {

        $globales = $this->repo->findBy([], ['createdAt' => 'DESC'], 3);

        $series = $this->repo->findThreeLast('Séries');
        $films  = $this->repo->findThreeLast('Films');
        $animes = $this->repo->findThreeLast('Animés');
        $news   = $this->repo->findThreeLast('Actualités');

        return $this->render('article/homepage.html.twig', compact('series', 'films', 'animes', 'globales', 'news'));
    }

    /**
     * @Route("/article/{libelle}", name="article_category_list")
     */
    public function articlelList($libelle, PaginatorInterface $paginator, Request $request)
    {

        $query    = $this->repo->findAllByCategory($libelle);
        $articles = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            9
        );
        return $this->render('article/list.html.twig', compact('articles', 'libelle'));
    }

    /**
     * @Route("/tags/{libelle}", name="article_tag_list")
     */
    public function articlelListByTag($libelle, PaginatorInterface $paginator, Request $request)
    {
        $tag      = $libelle;
        $query    = $this->repo->findAllByTags($libelle);
        $articles = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            9
        );

        return $this->render('article/list.html.twig', compact('articles', 'libelle'));
    }

    /**
     * @Route("/article/show/{slug}", name="article_show")
     */
    public function show(Article $article)
    {
        $sameCategory = $this->repo->findThreeLast($article->getCategory()->getLibelle());
        $news         = $this->repo->findBy([], ['createdAt' => 'DESC'], 3);
        // $news = $this->cache->get('news', function () {
        //   return $this->repo->findBy([], ['createdAt' => 'DESC'], 3);
        // });

        return $this->render('article/index.html.twig', compact('article', 'sameCategory', 'news'));
    }
}
