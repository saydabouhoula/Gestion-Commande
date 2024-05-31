<?php

namespace App\Controller;
use App\Entity\CategorieProduit;
use Symfony\Component\HttpFoundation\Request;
use App\Form\CategorieProduitType;




use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategorieProduitController extends AbstractController
{
    #[Route('/categorie/produit', name: 'app_categorie_produit')]
    public function index(): Response
    {
        return $this->render('categorie_produit/index.html.twig', [
            'controller_name' => 'CategorieProduitController',
        ]);
    }



    
    #[Route('/categorie_produit/newCategorie', name: 'new_categorie')]

    public function new(Request $request)
    {
        $categorie = new CategorieProduit();
        $form = $this->createForm(CategorieProduitType::class, $categorie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($categorie);
            $entityManager->flush();

            return $this->redirectToRoute('product_index');
        }

        return $this->render('categorie_produit/newCategorie.html.twig', [
            'form' => $form->createView(),
        ]);
    }






    #[Route('/{id}/viewCat', name: 'view_category')]

    public function view(Request $request, CategorieProduit $categorieProduit ): Response
    {

        $form = $this->createForm(CategorieProduitType::class, $categorieProduit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('product_index');
        }

        return $this->render('categorie_produit/viewCat.html.twig', [
            'categorieProduit' => $categorieProduit,
            'form' => $form->createView(),
        ]);
    }





    #[Route('/{id}/editCat', name: 'category_edit')]

    public function edit(Request $request, CategorieProduit $categorieProduit): Response
    {
        $form = $this->createForm(CategorieProduitType::class, $categorieProduit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('product_index');
        }

        return $this->render('categorie_produit/editCat.html.twig', [
            'product' => $categorieProduit,
            'form' => $form->createView(),
        ]);
    }


    #[Route('{id}', name: 'category_delete', methods: 'DELETE')]
    public function delete(Request $request, CategorieProduit $categorieProduit): Response
    {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($categorieProduit);
            $entityManager->flush();
        //Delete this padlet?
            //This action cannot be undone.
            //Enter this code to proceed: 7084

        return $this->redirectToRoute('product_index');
        
    }




    
}
