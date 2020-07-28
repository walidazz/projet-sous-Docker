<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Comment;
use App\Entity\ReportArticle;
use App\Entity\ReportComment;
use App\Entity\ReportUser;
use App\Entity\User;
use App\Repository\ReportArticleRepository;
use App\Repository\ReportCommentRepository;
use App\Repository\ReportUserRepository;
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

  $userConnected = $this->getUser();
  $motif         = $request->get('_motif');
  $message       = $request->get('_message');
  $reportExist   = $repo->findBy(['auteur' => $userConnected, 'reportedArticle' => $article]);
  //$reportExist = $article->getReportExist($user);
  if (!$reportExist) {
   $report = new ReportArticle();
   $report->setAuteur($userConnected);
   $report->setReportedArticle($article);
   $report->setMessage($message);
   $report->setMotif($motif);
   $em->persist($report);
   $em->flush();
   $this->addFlash('success', 'Signalement pris en compte ! ');
  } else {
   $this->addFlash('warning', 'Vous avez déja signalé cet article ! ');

  }
  return $this->redirectToRoute('article_show', ['slug' => $article->getSlug()]);
 }

 /**
  * @Route("/report/user/{id}/", name="report_user, methods={"POST","GET"})
  */
 public function reportUser(User $user, Request $request, EntityManagerInterface $em, ReportUserRepository $repo)
 {

  $userConnected = $this->getUser();
  $motif         = $request->get('_motif');
  $message       = $request->get('_message');
  $reportExist   = $repo->findBy(['auteur' => $userConnected, 'reported' => $user]);
  //$reportExist = $article->getReportExist($user);
  if (!$reportExist) {

   $report = new ReportUser();
   $report->setAuteur($userConnected);
   $report->setReported($user);
   $report->setMessage($message);
   $report->setMotif($motif);
   $em->persist($report);
   $em->flush();
   $this->addFlash('success', 'Signalement pris en compte ! ');
  } else {
   $this->addFlash('warning', 'Vous avez déja signalé cet utilisateur ! ');

  }
  $referer = $request->headers->get('referer');

  return $this->redirectToRoute($referer);
 }

 /**
  * @Route("/report/comment/{id}/", name="report_comment, methods={"POST","GET"})
  */
 public function reportComment(Comment $comment, Request $request, EntityManagerInterface $em, ReportCommentRepository $repo)
 {

  $userConnected = $this->getUser();
  $motif         = $request->get('_motif');
  $message       = $request->get('_message');
  $reportExist   = $repo->findBy(['auteur' => $userConnected, 'reportedComment' => $comment]);
  //$reportExist = $article->getReportExist($user);
  if (!$reportExist) {

   $report = new ReportComment();
   $report->setAuteur($userConnected);
   $report->setReportedComment($comment);
   $report->setMessage($message);
   $report->setMotif($motif);
   $em->persist($report);
   $em->flush();
   $this->addFlash('success', 'Signalement pris en compte ! ');
  } else {
   $this->addFlash('warning', 'Vous avez déja signalé ce commentaire ! ');

  }
  $referer = $request->headers->get('referer');

  return $this->redirectToRoute($referer);
 }

}
