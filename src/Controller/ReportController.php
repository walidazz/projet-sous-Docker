<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\ReportArticle;
use App\Repository\ReportArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ReportController extends AbstractController
{

 // https://benoitgrisot.fr/inserer-un-formulaire-dans-une-modale-avec-symfony-et-materializecss/

 /**
  * @Route("/report", name="report")
  */
 public function index()
 {
  return $this->render('report/index.html.twig', [
   'controller_name' => 'ReportController',
  ]);
 }

 /**
  * @Route("/report/article/{id}/", name="report_article", methods={"POST","GET"})
  */
 public function reportArticle(Article $article, Request $request, EntityManagerInterface $em, ReportArticleRepository $repo)
 {

  $user    = $this->getUser();
  $motif   = $request->get('_motif');
  $message = $request->get('_message');
  // $reportExist = $repo->findBy(['auteur' => $user, 'reportedArticle' => $article]);
  $reportExist = $article->getReportExist($user);
  //dd($reportExist);

  if (!$reportExist) {
   $report = new ReportArticle();
   $report->setAuteur($user);
   $report->setReportedArticle($article);
   $report->setMessage($message);
   $report->setMotif($motif);
   $em->persist($report);
   $em->flush();
   $this->addFlash('success', 'Signalement pris en compte ! ');
  } else {
   $this->addFlash('warning', 'Vous avez déja signalé cet article ! ');

  }

  // dd($message);

  return $this->redirectToRoute('article_show', ['slug' => $article->getSlug()]);

  // return $this->render(
  //     //On affiche  une vue twig simple (pas de head ni rien, donc aucun héritage de template...) qui sera intégrée dans la modale.
  //     'report/reportModal.html.twig',
  //     array('form' => $form->createView(), 'article' => $article)
  // );
 }
}
