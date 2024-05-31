<?php

namespace App\Controller;

use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class PanierController extends AbstractController
{
    #[Route('/panier', name: 'panier_index')]
    public function index(SessionInterface $session, ProduitRepository $ProduitRepository): Response
    {
        $panier = $session->get('panier', []);
        $panierWithData = [];
        foreach ($panier as $id => $qte) {
            $panierWithData[] = [
                'product' => $ProduitRepository->find($id),
                'qte' => $qte
            ];
        }
        $total = 0;
        foreach ($panierWithData as $item) {
            $total += $item['product']->getPrix() * $item['qte'];
        }
        return $this->render('panier/index.html.twig', [
            'items' => $panierWithData,
            'total' => $total,
        ]);
    }

    #[Route('/panier/count', name: 'panier_count')]
    public function count(SessionInterface $session): JsonResponse
    {
        $panier = $session->get('panier', []);
        $count = 0;
        foreach ($panier as $id => $qte)
            $count += $qte;
        return $this->json([
            'code' => 200,
            'count' => $count,
        ], 200);
    }

    #[Route('/panier/add/{id}', name: 'panier_add')]
    public function add($id, SessionInterface $session): JsonResponse
    {
        $panier = $session->get('panier', []);
        $panier[$id] = (empty($panier[$id]) ? 1 : $panier[$id] + 1);
        $session->set('panier', $panier);
        $count = 0;
        foreach ($panier as $id => $qte)
            $count += $qte;
        return $this->json([
            'code' => 200,
            'message' => 'produit ajoute avec succee au panier',
            'count' => $count,
        ], 200);
    }

    #[Route('/panier/remove/{id}', name: 'panier_remove')]
    public function remove($id, SessionInterface $session): RedirectResponse
    {
        $panier = $session->get('panier', []);
        if (!empty($panier[$id])) {
            unset($panier[$id]);
        }
        $session->set('panier', $panier);
        return $this->redirectToRoute('panier_index');
    }

    #[Route('/panier/clear', name: 'panier_clear')]
    public function clear(SessionInterface $session): RedirectResponse
    {
        $session->remove('panier');
        return $this->redirectToRoute('panier_index');
    }

    #[Route('/panier/inc/{id}', name: 'panier_inc')]
    public function increase($id, SessionInterface $session)
    {
        $panier = $session->get('panier', []);
        $panier[$id] -= 1;
        $session->set('panier', $panier);
        foreach ($panier as $id => $qte)
            $count += $qte;
        return $this->json([
            'code' => 200,
            'count' => $count,
        ], 200);
    }

    #[Route('/panier/dec/{id}', name: 'panier_dec')]
    public function decrease($id, SessionInterface $session)
    {
        $panier = $session->get('panier', []);
        $panier[$id] += 1;
        $session->set('panier', $panier);
        foreach ($panier as $id => $qte)
            $count += $qte;
        return $this->json([
            'code' => 200,
            'count' => $count,
        ], 200);
    }
}
