<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Comment;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CommentController extends AbstractController
{
    /**
     * 
     * @Route("/comment/new/{id}", name="comment_new")
     */
    public function new(Article $article, Request $request, EntityManagerInterface $em)
    {
        $content = $request->get('content');
        $user = $this->getUser();
        $comment = new Comment();
        $comment->setArticle($article)
            ->setAuteur($user)
            ->setContent($content);
        $em->persist($comment);
        $em->flush();
        $this->addFlash('success', 'Commentaire posté !');
        return $this->redirectToRoute('article_show', ['slug' => $article->getSlug()]);
    }
    /**
     * @Route("/comment/delete/{id}", name="comment_delete")
     */
    public function delete(Comment $comment, EntityManagerInterface $em, Request $request)
    {
        $referer = $request->headers->get('referer');
        if ($this->isCsrfTokenValid('delete' . $comment->getId(), $request->request->get('_token'))) {
            $em->remove($comment);
            $em->flush();
            $this->addFlash('success', 'Commentaire supprimé!');
        }
        return $this->redirect($referer);
    }
}
