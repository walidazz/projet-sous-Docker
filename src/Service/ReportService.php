<?php

namespace App\Service;

use App\Entity\User;
use App\Entity\Article;
use App\Entity\Comment;
use App\Entity\ReportUser;
use App\Entity\ReportArticle;
use App\Entity\ReportComment;
use App\Repository\ReportUserRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ReportArticleRepository;
use App\Repository\ReportCommentRepository;
use phpDocumentor\Reflection\Types\Boolean;
use PhpParser\Node\Expr\Cast\Bool_;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class ReportService extends AbstractController
{

    private User $userConnected;

    private $motif;

    private $message;

    private EntityManagerInterface $em;

    private ValidatorInterface $validator;

    private  $ReportUserRepo;

    private $ReportArticleRepo;

    private $ReportCommentRepo;

    public function __construct(Security $security, RequestStack $request, ReportUserRepository $reportUserRepo, ValidatorInterface $validator, ReportArticleRepository $reportArticleRepo, ReportCommentRepository $reportCommentRepo, EntityManagerInterface $em)
    {
        $this->userConnected = $security->getUser();
        $this->motif = $request->getCurrentRequest()->get('_motif');
        $this->message = $request->getCurrentRequest()->get('_message');
        $this->ReportUserRepo = $reportUserRepo;
        $this->validator = $validator;
        $this->ReportArticleRepo = $reportArticleRepo;
        $this->ReportCommentRepo = $reportCommentRepo;
        $this->em = $em;
    }

    public function reportUserExist(User $user)
    {
        return  $this->ReportUserRepo->findBy(['auteur' => $this->userConnected, 'reported' => $user]);
    }

    public function reportArticleExist(Article $article)
    {
        return $this->ReportArticleRepo->findBy(['auteur' => $this->userConnected, 'reportedArticle' => $article]);
    }

    public function reportCommentExist(Comment $comment)
    {
        return  $this->ReportCommentRepo->findBy(['auteur' => $this->userConnected, 'reportedComment' => $comment]);
    }




    public function getLenghtConstraint(array $array)
    {
        return new Assert\Length($array);
    }

    public function validateContraints(
        $lenghtConstraint
    ) {

        return
            $this->validator->validate(
                $this->message,
                $lenghtConstraint
            );
    }



    public function createUserReport($lenghtConstraint, user $user)
    {

        if (0 === count($this->validateContraints($lenghtConstraint))) {
            if (!$this->reportUserExist($user)) {
                $report = new ReportUser();
                $report->setAuteur($this->userConnected);
                $report->setReported($user);
                $report->setMessage($this->message);
                $report->setMotif($this->motif);
                $this->em->persist($report);
                $this->em->flush();
                $this->addFlash('success', 'Signalement pris en compte ! ');
            } else {
                $this->addFlash('warning', 'Vous avez déja signalé cet utilisateur ! ');
            }
        } else {
            $this->addFlash('warning', 'Texte trop long ! ');
        }
    }


    public function createArticleReport($lenghtConstraint, Article $article)
    {
        if (0 === count($this->validateContraints($lenghtConstraint))) {
            if (!$this->reportArticleExist($article)) {
                $report = new ReportArticle();
                $report->setAuteur($this->userConnected);
                $report->setReportedArticle($article);
                $report->setMessage($this->message);
                $report->setMotif($this->motif);
                $this->em->persist($report);
                $this->em->flush();
                $this->addFlash('success', 'Signalement pris en compte ! ');
            } else {

                $this->addFlash('warning', 'Vous avez déja signalé cet article ! ');
            }
        } else {
            $this->addFlash('warning', 'Texte trop long ! ');
        }
    }

    public function createCommentReport($lenghtConstraint, Comment $comment)
    {
        if (0 === count($this->validateContraints($lenghtConstraint))) {
            if (!$this->reportCommentExist($comment)) {
                $report = new ReportComment();
                $report->setAuteur($this->userConnected);
                $report->setReportedComment($comment);
                $report->setMessage($this->message);
                $report->setMotif($this->motif);
                $this->em->persist($report);
                $this->em->flush();
                $this->addFlash('success', 'Signalement pris en compte ! ');
            } else {
                $this->addFlash('warning', 'Vous avez déja signalé ce commentaire ! ');
            }
        } else {
            $this->addFlash('warning', 'Texte trop long ! ');
        }
    }
}
