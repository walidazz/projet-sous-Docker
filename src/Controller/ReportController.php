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
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validator\ValidatorInterface;

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
 public function reportArticle(Article $article, Request $request, EntityManagerInterface $em, ReportArticleRepository $repo, ValidatorInterface $validator)
 {

  $userConnected = $this->getUser();
  $motif         = $request->get('_motif');
  $message       = $request->get('_message');
  $reportExist   = $repo->findBy(['auteur' => $userConnected, 'reportedArticle' => $article]);

  $lenghtConstraint = new Assert\Length(['max' => 250]);
// all constraint "options" can be set this way

// use the validator to validate the value
  $errors = $validator->validate(
   $message,
   $lenghtConstraint
  );

  if (0 === count($errors)) {
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
    // this is *not* a valid email address

    $this->addFlash('warning', 'Vous avez déja signalé cet article ! ');

    // ... do something with the error
   }
  } else {
   $this->addFlash('warning', 'Texte trop long ! ');
  }

  //$reportExist = $article->getReportExist($user);

  return $this->redirectToRoute('article_show', ['slug' => $article->getSlug()]);
 }

 /**
  * @Route("/report/user/{id}/", name="report_user", methods={"POST","GET"})
  */
 public function reportUser(User $user, Request $request, EntityManagerInterface $em, ReportUserRepository $repo, ValidatorInterface $validator)
 {

  $userConnected = $this->getUser();
  $motif         = $request->get('_motif');
  $message       = $request->get('_message');
  $reportExist   = $repo->findBy(['auteur' => $userConnected, 'reported' => $user]);
  //$reportExist = $article->getReportExist($user);

  $lenghtConstraint = new Assert\Length(['max' => 250]);
// all constraint "options" can be set this way

// use the validator to validate the value
  $errors = $validator->validate(
   $message,
   $lenghtConstraint
  );

  if (0 === count($errors)) {
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
  } else {
   $this->addFlash('warning', 'Texte trop long ! ');

  }
  // $referer = $request->headers->get('referer');

  return $this->redirectToRoute('user_profile', ['id' => $user->getId()]);
 }

 /**
  * @Route("/report/comment/{id}/", name="report_comment", methods={"POST","GET"})
  */
 public function reportComment(Comment $comment, Request $request, EntityManagerInterface $em, ReportCommentRepository $repo, ValidatorInterface $validator)
 {

  $userConnected = $this->getUser();
  $motif         = $request->get('_motif');
  $message       = $request->get('_message');
  $reportExist   = $repo->findBy(['auteur' => $userConnected, 'reportedComment' => $comment]);

  $lenghtConstraint = new Assert\Length(['max' => 250]);
// all constraint "options" can be set this way

// use the validator to validate the value
  $errors = $validator->validate(
   $message,
   $lenghtConstraint
  );

  //$reportExist = $article->getReportExist($user);

  if (0 === count($errors)) {
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
  } else {
   $this->addFlash('warning', 'Texte trop long ! ');

  }
  //$referer = $request->headers->get('referer');

  $articleSlug = $comment->getArticle()->getSlug();

  return $this->redirectToRoute('article_show', ['slug' => $articleSlug]);

 }

}
