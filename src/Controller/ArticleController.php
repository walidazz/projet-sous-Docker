<?php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{

 /**
  * @Route("/", name="homepage")
  */
 public function index(ArticleRepository $repo)
 {

  $globales = $repo->findBy([], ['createdAt' => 'DESC'], 3);
  $series   = $repo->findThreeLast('Séries');
  $films    = $repo->findThreeLast('Films');
  $animes   = $repo->findThreeLast('Animés');

  return $this->render('article/homepage.html.twig', compact('series', 'films', 'animes', 'globales'));
 }

/**
 * @Route("/article/{libelle}", name="article_category_list")
 */
 public function articlelList($libelle, PaginatorInterface $paginator, Request $request, ArticleRepository $repo)
 {
 
  $query    = $repo->findAllByCategory($libelle);
  $articles = $paginator->paginate(
   $query,
   $request->query->getInt('page', 1),
   9
  );
  return $this->render('article/list.html.twig', compact('articles','libelle'));
 }

/**
 * @Route("/tags/{libelle}", name="article_tag_list")
 */
public function articlelListByTag($libelle, PaginatorInterface $paginator, Request $request, ArticleRepository $repo)
{
  $tag = $libelle;
 $query    = $repo->findAllByTags($libelle);
 $articles = $paginator->paginate(
  $query,
  $request->query->getInt('page', 1),
  9
 );

 return $this->render('article/list.html.twig', compact('articles','libelle'));
}




 /**
  * @Route("/article/show/{slug}", name="article_show")
  */
 public function show(Article $article, ArticleRepository $repo)
 {
  $sameCategory = $repo->findThreeLast($article->getCategory()->getLibelle());
  $news         = $repo->findBy([], ['createdAt' => 'DESC'], 3);

  return $this->render('article/index.html.twig', compact('article', 'sameCategory', 'news'));
 }

//  /**
 //   * @Route("/articleAjax/{id}", name="articleAjax" )
 //   */
 //  public function articleAjax(Article $article): Response
 //  {
 //   return $this->json(['title' => $article->getTitle(), 200]);
 //  }

//  /**
 //   * @Route("/panier/", name="panier_index" )
 //   */
 //  public function favorite(SessionInterface $session, ArticleRepository $repo)
 //  {
 //   $panier         = $session->get('panier', []);
 //   $panierWithData = [];

//   foreach ($panier as $id => $value) {

//    $panierWithData[] = [
 //     'product' => $repo->find($id),

//    ];
 //   }
 //   // dd($panierWithData);
 //   return $this->render(
 //    'article/favorite.html.twig',
 //    ['items' => $panierWithData]
 //   );
 //  }

 /**
  * @Route("/panier/add/{id}", name="panier_add" )
  */
//  public function addFavorite($id, SessionInterface $session)
 //  {

//   $panier = $session->get('panier', []);
 //   if (!empty($panier[$id])) {
 //    $panier[$id]++;
 //   } else {
 //    $panier[$id] = 1;
 //   }

//   $panier = $session->set('panier', $panier);

//   return $this->redirectToRoute('panier_index');
 //  }

}
