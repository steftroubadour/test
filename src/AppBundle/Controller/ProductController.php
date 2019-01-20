<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Product;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends Controller
{
    /**
     * @Route("/{page}", name="main_home", defaults={"page"="1"}, requirements={"page"="\d+"})
     * @param int $page
     * @param int $nbPerPage
     * @return Response
     */
    public function indexAction($page, $nbPerPage = Product::NUMBER_PER_PAGE)
    {
        if ($page < 1) {
            throw new NotFoundHttpException('Page "'.$page.'" inexistante.');
        }

        $products = $this->getDoctrine()
            ->getRepository("AppBundle:Product")
            ->getProducts($page, $nbPerPage)
        ;

        $nbPages = ceil(count($products) / $nbPerPage);

        if ( $page > $nbPages and $nbPages > 0 ) {
            throw $this->createNotFoundException("La page " . $page . " n'existe pas.");
        }

        return $this->render('product/list.html.twig', array(
            'products'  => $products,
            'nbPages'   => $nbPages,
            'page'      => $page,
        ));
    }

    /**
     * @Route("/product/{id}", name="product_view")
     * @ParamConverter("product", class="AppBundle:Product")
     * @param Product $product
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function viewAction(Product $product)
    {
        return $this->render('product/view.html.twig',
            array(
                'product' => $product
            ));
    }
}
