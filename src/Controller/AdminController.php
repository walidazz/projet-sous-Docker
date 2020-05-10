<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use EasyCorp\Bundle\EasyAdminBundle\Controller\EasyAdminController;

class AdminController extends EasyAdminController{



    /**
     * @Route(path = "/admin/user/enable", name = "enable")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function enable()
    {
        // controllers extending the base AdminController get access to the
        // following variables:
        //   $this->request, stores the current request
        //   $this->em, stores the Entity Manager for this Doctrine entity

        // change the properties of the given entity and save the changes
        $id = $this->request->query->get('id');
        $entity = $this->em->getRepository(User::class)->find($id);
        if ($entity->getEnable() === 0) {
            $entity->setEnable(1);
        } else {
            $entity->setEnable(0);
        }
      
        $this->em->flush();

        // redirect to the 'list' view of the given entity ...
        return $this->redirectToRoute('easyadmin', [
            'action' => 'list',
            'entity' => $this->request->query->get('entity'),
        ]);

        // ... or redirect to the 'edit' view of the given entity item
        return $this->redirectToRoute('easyadmin', [
            'action' => 'edit',
            'id' => $id,
            'entity' => $this->request->query->get('entity'),
        ]);
    }
}
