<?php

namespace App\Controller;

use BadMethodCallException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    /**
     * @Route("/cart/add/{id}", name="cart_add")
     */
    public function add($id, Request $request)
    {
        // 1. Retrouver le panier dans la session (sous forme de tableau)
        // 2. Si il n'existe pas encore, le créer
        $cart = $request->getSession()->get('cart', []);

        // 3. Voire si le produit ($id) existe déjà dans la session
        // 4. Si c'est le cas, simplement augmenter la quantité
        // 5. Sinon ajouter le prduit avec la quantité 1
        if (array_key_exists($id, $cart)) $cart[$id]++;
        else $cart[$id] = 1;

        // 6. Enregistrer le tableau mis à jour dans la session
        $request->getSession()->set('cart', $cart);

        dd($request->getSession()->get('cart'));
    }
}
