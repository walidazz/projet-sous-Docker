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
use App\Service\ReportService;
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
  public function reportArticle(Article $article,  ReportService $reportService)
  {

    $lenghtConstraint = $reportService->getLenghtConstraint(['max' => 250]);
    $reportService->createArticleReport($lenghtConstraint, $article);

    return $this->redirectToRoute('article_show', ['slug' => $article->getSlug()]);
  }

  /**
   * @Route("/report/user/{id}/", name="report_user", methods={"POST","GET"})
   */
  public function reportUser(User $user, ReportService $reportService)
  {

    $lenghtConstraint = $reportService->getLenghtConstraint(['max' => 250]);
    $reportService->createUserReport($lenghtConstraint, $user);
    
    return $this->redirectToRoute('user_profile', ['id' => $user->getId()]);
  }

  /**
   * @Route("/report/comment/{id}/", name="report_comment", methods={"POST","GET"})
   */
  public function reportComment(Comment $comment, ReportService $reportService)
  {

    $lenghtConstraint = $reportService->getLenghtConstraint(['max' => 250]);
    $reportService->createCommentReport($lenghtConstraint, $comment);

    return $this->redirectToRoute('article_show', ['slug' => $comment->getArticle()->getSlug()]);
  }
}
