<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Basket;
use AppBundle\Entity\Product;
use AppBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Security("is_granted('ROLE_USER')")
 */
class BasketController extends Controller
{
    /**
     * @Route("/basket/add/{id}", name="basket_add")
     * @ParamConverter("product", class="AppBundle:Product")
     * @param Product $product
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function addAction(Product $product)
    {
        $em = $this->getDoctrine()->getManager();
        /** @var User $user */
        $user = $this->getUser();
        /** @var Basket $basket */
        $basket = $user->getBasket();
        if ( $basket === null ) {
            $basket = new Basket();
        } else {
            $basketProducts = $basket->getProducts();
            foreach ($basketProducts as $basketProduct) {
                if ($basketProduct === $product) {
                    $this->addFlash('danger','Vous avez déjà mis le produit ' . $product->getTitle() . ' dans votre panier!');
                    return $this->redirectToRoute('main_home');
                }
            }
        }
        $basket->addProduct($product);
        $basket->setUpdatedAt(new \DateTime());
        $user->setBasket($basket);

        $em->persist($user);
        $em->flush();

        $this->addFlash('success','Vous avez ajouté le produit ' . $product->getTitle() . ' à votre panier!');
        return $this->redirectToRoute('main_home');
    }

    /**
     * @Route("/basket/view", name="basket_view")
     */
    public function viewAction()
    {
        /** @var User $user */
        $user = $this->getUser();
        /** @var Basket $basket */
        $basket = $user->getBasket();

        return $this->render('basket/view.html.twig', array('basket' => $basket));
    }


}
