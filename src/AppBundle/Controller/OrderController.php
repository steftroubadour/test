<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Basket;
use AppBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Security("is_granted('ROLE_USER')")
 */
class OrderController extends Controller
{
    /**
     * @Route("/order/validation", name="order_validation")
     * @return Response
     */
    public function indexAction()
    {
        /** @var User $user */
        $user = $this->getUser();
        /** @var Basket $basket */
        $basket = $user->getBasket();

        $em = $this->getDoctrine()
            ->getManager();
        $em->remove($basket);

        $user->setBasket(null);
        $em->persist($user);
        $em->flush();

        return $this->render('order/valid.html.twig');
    }
}