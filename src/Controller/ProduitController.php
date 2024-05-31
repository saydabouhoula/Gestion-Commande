<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Form\ProduitType;
use App\Repository\CategorieProduitRepository;
use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProduitController extends AbstractController
{
    #[Route('/afficheProduit', name: 'afficheProduit')]
    public function afficheProduit(ProduitRepository $productRepository): Response
    {
        $products = $productRepository->findAll();
        return $this->render('produit/afficheProduits.html.twig', [
            'products' => $products,
        ]);
    }

//******************************* Begin Test Field ******************************************* */
    #[Route('/signleProduitcopy', name: 'signleProduitcopy')]
    public function signleProduit(): Response
    {
        return $this->render('produit/single-product.html copy.twig');
    }

    #[Route('/cardProduit', name: 'cardProduit')]
    public function cardProduit(): Response
    {
        return $this->render('produit/panier.html.twig');
    }

    #[Route('/panier', name: 'panier')]
    public function BarpanierProduits(ProduitRepository $productRepository): Response
    {

        $products = $productRepository->findAll();

        return $this->render('baseFrontOffice.html.twig', [
            'products' => $products,
        ]);
    }

//************************************** End Test Field ************************************ */


    #[Route('/{id}/singleProduct', name: 'singleProduct')]
    public function singleProduct($id, ProduitRepository $productRepository): Response
    {
        $entityManager = $this->getDoctrine()->getManager();

        $product = $entityManager->getRepository(Produit::class)->find($id);

        $products = $productRepository->findAll();


        return $this->render('produit/single-product.html.twig', [
            'product' => $product, 'products' => $products
        ]);
    }


    #[Route('/panier', name: 'panier')]
    public function panierProduits(ProduitRepository $productRepository): Response
    {

        $products = $productRepository->findAll();

        return $this->render('produit/panier.html.twig', [
            'products' => $products,
        ]);
    }


    #[Route('/produits', name: 'product_index')]
    public function index(ProduitRepository $productRepository, CategorieProduitRepository $categorieProduitRepository): Response
    {

        $products = $productRepository->findAll();
        $catProducts = $categorieProduitRepository->findAll();

        return $this->render('produit/index.html.twig', [
            'products' => $products, 'catProducts' => $catProducts
        ]);
    }

// -------------------------------------------------Create View-----------------------------------------------------------------------


    #[Route('/produits/newProduit', name: 'produit_new')]
    public function new(Request $request)
    {
        $produit = new Produit();
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($produit);
            $entityManager->flush();

            return $this->redirectToRoute('product_index');
        }

        return $this->render('produit/newProduit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
// -------------------------------------------------End Create View-----------------------------------------------------------------------


// -------------------------------------------------View Item-----------------------------------------------------------------------

    #[Route('/{id}/view', name: 'product_view')]
    public function view(Request $request, Produit $product): Response
    {

        $form = $this->createForm(ProduitType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('product_index');
        }

        return $this->render('produit/view.html.twig', [
            'product' => $product,
            'form' => $form->createView(),
        ]);
    }







// -------------------------------------------------End View Item-----------------------------------------------------------------------


// -------------------------------------------------Begin Edit Item-----------------------------------------------------------------------


    #[Route('/{id}/edit', name: 'product_edit')]
    public function edit(Request $request, Produit $product): Response
    {
        $form = $this->createForm(ProduitType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('product_index');
        }

        return $this->render('produit/edit.html.twig', [
            'product' => $product,
            'form' => $form->createView(),
        ]);
    }


    // -------------------------------------------------Begin Delete Item-----------------------------------------------------------------------


    #[Route('/{id}', name: 'product_delete', methods: 'DELETE')]
    public function delete(Request $request, Produit $product): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($product);
        $entityManager->flush();
        //Delete this padlet?
        //This action cannot be undone.
        //Enter this code to proceed: 7084

        return $this->redirectToRoute('product_index');

    }


}


