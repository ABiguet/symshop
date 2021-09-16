<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use BadMethodCallException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBag;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    /**
     * @Route("/cart/add/{id}", name="cart_add", requirements={"id":"\d+"})
     */
    public function add($id, Request $request, SessionInterface $session, FlashBagInterface $flashBag, ProductRepository $productRepository)
    {
        if (!$product = $productRepository->find($id)) {
            throw $this->createNotFoundException("Article inconnu");
        }

        // 1. Retrouver le panier dans la session (sous forme de tableau)
        // 2. Si il n'existe pas encore, le créer
        $cart = $session->get('cart', []);

        // 3. Voire si le produit ($id) existe déjà dans la session
        // 4. Si c'est le cas, simplement augmenter la quantité
        // 5. Sinon ajouter le produit avec la quantité 1
        if (array_key_exists($id, $cart)) $cart[$id]++;
        else $cart[$id] = 1;

        // 6. Enregistrer le tableau mis à jour dans la session
        $session->set('cart', $cart);

        $this->addFlash('success', "Le produit a bien été ajouté au panier");

        return $this->redirectToRoute(
            'product_show',
            [
                'slug_category' => $product->getCategory()->getSlug(),
                'slug' => $product->getSlug()
            ]
        );
    }
}
