<?php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{

 /**
  * @Route("/", name="homepage")
  */
 public function index(ArticleRepository $repo)
 {

//   $articles = $repo->findBy([], ['createdAt' => 'DESC'], 3);
$series = $repo->findThreeLast('Séries');
$films = $repo->findThreeLast('Films');
$animes = $repo->findThreeLast('Animés');

  return $this->render('article/homepage.html.twig', compact('series','films','animes'));
 }

/**
 * @Route("/article/list", name="article_list")
 */
 public function articlelList(PaginatorInterface $paginator, Request $request, ArticleRepository $repo)
 {

  $query    = $repo->findAllQuery();
  $articles = $paginator->paginate(
   $query,
   $request->query->getInt('page', 1),
   9
  );
  return $this->render('article/list.html.twig', compact('articles'));
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

 /**
  * @Route("/panier/", name="panier_index" )
  */
 public function favorite(SessionInterface $session, ArticleRepository $repo)
 {
  $panier         = $session->get('panier', []);
  $panierWithData = [];

  foreach ($panier as $id => $value) {

   $panierWithData[] = [
    'product' => $repo->find($id),

   ];
  }
  // dd($panierWithData);
  return $this->render(
   'article/favorite.html.twig',
   ['items' => $panierWithData]
  );
 }

 /**
  * @Route("/panier/add/{id}", name="panier_add" )
  */
 public function addFavorite($id, SessionInterface $session)
 {

  $panier = $session->get('panier', []);
  if (!empty($panier[$id])) {
   $panier[$id]++;
  } else {
   $panier[$id] = 1;
  }

  $panier = $session->set('panier', $panier);

  return $this->redirectToRoute('panier_index');
 }

}
