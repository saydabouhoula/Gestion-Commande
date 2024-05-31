<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Entity\DetailCommande;
use App\Form\CommandeType;
use App\Repository\CommandeRepository;
use App\Repository\ProduitRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class CommandeController extends AbstractController
{



    #[Route('/commande', name: 'commande_index')]
    public function index(CommandeRepository $CommandeRepository, MailerInterface $mailer): Response
    {
        $commandes = $CommandeRepository->findBy([
            'user' => 1, // ! to change $this->getUser()
        ]);
        $cmd_info = [];
        foreach ($commandes as $cmd) {
            $cmd_info[] = [
                'commande' => $CommandeRepository->find($cmd->getId()),
                'id' => $cmd->getId()
            ];
        }
        return $this->render('commande/index.html.twig', [
            'cmd_infos' => $cmd_info
        ]);
    }

    #[Route('/commande/ajouter', name: 'commande_add')]
    public function add(
        SessionInterface  $session,
        ProduitRepository $ProduitRepository,
        UserRepository    $userRepository,
        Request           $request,
        MailerInterface   $mailer
    ): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $panier = $session->get('panier', []);
        $panierWithData = [];
        foreach ($panier as $id => $qte) {
            $panierWithData[] = [
                'produit' => $ProduitRepository->find($id),
                'qte' => $qte
            ];
        }
        if (!empty($panierWithData)) {
            $cmd = new Commande();
            $cmd->setDateCreation(new \DateTime());

            $user = $userRepository->find(1); // to remove
            $cmd->setUser($user); // this->getUser()
            $cmd->setPaymentMethod($request->query->get('payment_method'));
            $cmd->setPaymentBrand($request->query->get('payment_brand'));
            $cmd->setPaymentStatus($request->query->get('payment_status'));
            $cmd->setCcLast($request->query->get('cc_last'));

            $entityManager->persist($cmd);
            $entityManager->flush();
            foreach ($panierWithData as $item) {
                $cmd_detail = new DetailCommande();
                $cmd_detail->setCommande($cmd);
                $cmd_detail->setProduit($item['produit']);
                $cmd_detail->setQte($item['qte']);
                $entityManager->persist($cmd_detail);
                $entityManager->flush();
            }
            $em = $this->getDoctrine()->getManager();
            $detailCmds = $em->getRepository(DetailCommande::class)->findBy(
                ['commande' => $cmd->getId()]
            );

            //clear cart
            $session->remove('panier');

            //send receipt mail
            $receipt = file_get_contents($request->query->get('receipt_url'));
            $email = (new Email())
                ->from('BioLine@example.com')
                ->to('Client@test.com') // $user->getEmail()
                ->subject('Merci pour votre achat!')
                ->html($receipt);

            $mailer->send($email);
        }
        return $this->redirectToRoute("commande_index");
    }

    #[Route('/commande/detail/{id}', name: 'detail_cmd_user')]
    public function aff_detail_cmd(Commande $commande)
    {
        $em = $this->getDoctrine()->getManager();
        $detailCmds = $em->getRepository(DetailCommande::class)->findBy(
            ['commande' => $commande]
        );
        $total = 0;
        foreach ($detailCmds as $item)
            $total += $item->getProduit()->getPrix() * $item->getQte();
        return $this->render('commande/detailcommande.html.twig',
            [
                'detailCmds' => $detailCmds,
                'commande' => $commande,
                'total' => $total
            ]);
    }

    /** Partie Backoffice */

    #[Route('/admin/commandes', name: 'gestion_commande')]
    public function aff_commandes(Request $request): Response
    {
        $em = $this->getDoctrine()->getManager();
        $commandes = $em->getRepository(Commande::class)->findAll();
        if ($request->isXmlHttpRequest() || $request->query->get('showJson') == 1) {
            $jsonData = array();
            $idx = 0;
            foreach ($commandes as $commande) {
                $temp = array(
                    'id' => $commande->getId(),
                    'datecreation' => $commande->getDateCreation(),
                    'status' => $commande->getStatus(),
                    'user' => $commande->getUser()->getPrenom() . ' ' . $commande->getUser()->getPrenom()
                );
                $jsonData[$idx++] = $temp;
            }
            return new JsonResponse($jsonData);
        }
        return $this->render('/commande/admin/listecommandes.html.twig', [
            'commandes' => $commandes
        ]);
    }

    #[Route('/admin/commande/{id}', name: 'detail_commande')]
    public function aff_detail_cmd_admin($id)
    {
        $em = $this->getDoctrine()->getManager();
        $detailCmds = $em->getRepository(DetailCommande::class)->findBy(
            ['commande' => $id]
        );
        $total = 0;
        foreach ($detailCmds as $item)
            $total += $item->getProduit()->getPrix() * $item->getQte();
        return $this->render('commande/admin/detailcommande.html.twig',
            [
                'detailCmds' => $detailCmds,
                'total' => $total
            ]);
    }
/*------------------------------------------*/
    #[Route('/admin/commandes/rechercher', name: 'recherche_commande', methods: "POST")]
    public function recherche_commande(Request $request,CommandeRepository $CommandeRepository)
    {
        if ($request->isXmlHttpRequest()) {
            $req = $request->request->get('req');
            $commandes =$CommandeRepository->findBy(['id'=>$req]);
            $jsonData = array();
            $idx = 0;
            foreach ($commandes as $commande) {
                $temp = array(
                    'id' => $commande->getId(),
                    'datecreation' => $commande->getDateCreation(),
                    'status' => $commande->getStatus(),
                    'user' => $commande->getUser()->getPrenom() . ' ' . $commande->getUser()->getPrenom()
                );
                $jsonData[$idx++] = $temp;
            }
            return new JsonResponse($jsonData);
        }
        return $this->redirectToRoute('gestion_commande');
    }

    #[Route('/admin/commande/supprimer/{id}', name: 'supprimer_commande')]
    public function supprimer_cmd($id)
    {
        $em = $this->getDoctrine()->getManager();
        $cmd = $em->getRepository(Commande::class)->find($id);

        $detaiCmds = $em->getRepository(DetailCommande::class)->findBy(
            ['commande' => $id]
        );
        foreach ($detaiCmds as $item)
            $em->remove($item);
        $em->remove($cmd);
        $em->flush();
        $this->addFlash(
            'info',
            'Commande supprimée avec succée !'
        );
        return $this->redirectToRoute('gestion_commande');
    }

    #[Route('/admin/commande/modifier/{id}', name: 'modifier_commande')]
    public function modifier_cmd(Commande $commande, Request $request)
    {
        $form = $this->createForm(CommandeType::class, $commande)
            ->add('save', SubmitType::class, [
                'label' => 'Modifier le status',
                'attr' => [
                    'class' => 'btn btn-success'
                ]
            ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('gestion_commande');
        }
        return $this->render('/commande/admin/modifiercommande.html.twig', [
            'commande' => $commande,
            'form' => $form->createView()
        ]);
    }


/*---------------------json-------------*/
    #[Route('/api/commandes', name: 'api_commandes_list', methods: ['GET'])]
public function listCommandes(Request $request, SerializerInterface $serializer): JsonResponse
{
    $commandes = $this->getDoctrine()->getRepository(Commande::class)->findAll();

    if (empty($commandes)) {
        return new JsonResponse(['message' => 'No commandes found.'], Response::HTTP_OK);
    }
    
    $data = [];

    foreach ($commandes as $commande) {
        $data[] = [
            'id' => $commande->getId(),
            'date_creation' => $commande->getDateCreation(),
            'status' => $commande->getStatus(),
            //'user_id' => $commande->getUser(),
            'payment_method' => $commande->getPaymentMethod(),
            'payment_brand' => $commande->getPaymentBrand(),
            'cc_last' => $commande->getCcLast(),
            'payment_status' => $commande->getPaymentStatus(),
            ];
    }

    // $json = $serializer->serialize($data, 'json');
    $json = $serializer->serialize($data, 'json', ['groups' => 'read', 'max_depth' => 1]);

    return new JsonResponse($json, Response::HTTP_OK, [], true);
}

/*********
#[Route('/api/commandes/{id}', name: 'api_commandes_update', methods: ['PUT'])]
public function updateCommande(Request $request, SerializerInterface $serializer, int $id): JsonResponse
{
    $entityManager = $this->getDoctrine()->getManager();
    $commande = $entityManager->getRepository(Commande::class)->find($id);
    if (!$commande) {
    
   
        return new JsonResponse(['message' => 'Commande not found.'], Response::HTTP_NOT_FOUND);
        }
        
        // Vérifier si la commande est initialisée, expédiée, efficace ou annulée
        
        
        $status = $commande->getStatus();
        if ($status == 'Initialisée' || $status == 'Expédiée' || $status == 'Efficace' || $status == 'Annulée') {
            
           
        return new JsonResponse(['message' => 'Commande cannot be modified.'], Response::HTTP_BAD_REQUEST);
        }
        
        
        
        // Récupérer les données de la requête
        
        
        $data = json_decode($request->getContent(), true);
        
        // Valider les données
        
        
        $validator = Validation::createValidator();
        
        
        $constraints = new Collection([
            
           
        'date_creation' => new NotBlank(),
            'status' => new NotBlank(),
            'user' => new NotBlank(),
            'payment_method' => new NotBlank(),
            
           
        'payment_brand' => new NotBlank(),
            
           
        'cc_last' => new NotBlank(),
            
           
        'payment_status' => new NotBlank(),
        ]);
        $violations = $validator->validate($data, $constraints);
        if (count($violations) > 0) {
            
           
        return new JsonResponse(['message' => (string) $violations], Response::HTTP_BAD_REQUEST);
        }
        
        // Modifier la commande
        $commande->setDateCreation(new \DateTime($data['date_creation']));
        $commande->setStatus($data['status']);
        
        
        $commande->setUser($data['user']);
        
        
        $commande->setPaymentMethod($data['payment_method']);
        
        
        $commande->setPaymentBrand($data['payment_brand']);
        
        
        $commande->setCcLast($data['cc_last']);
        
        
        $commande->setPaymentStatus($data['payment_status']);
        
        // Enregistrer les modifications dans la base de données
        
        
        $entityManager->flush();
        
        // Renvoyer la réponse
        
        
        $json = $serializer->serialize($commande, 'json', ['groups' => 'read', 'max_depth' => 1]);
        return new JsonResponse($json, Response::HTTP_OK, [], true);
    

    }****** */
    /*-------------------------Update Json--------------------*/
    
    /*#[Route('/api/commandes/{id}', name: 'api_commandes_update', methods: ['PUT'])]
public function updateCommande(Request $request, SerializerInterface $serializer, int $id): JsonResponse
{
    $entityManager = $this->getDoctrine()->getManager();
    $commande = $entityManager->getRepository(Commande::class)->find($id);
       //dd($commande);
    if (!$commande) {
        return new JsonResponse(['message' => 'Commande not found.'], Response::HTTP_NOT_FOUND);
    }
    $json = $request->getContent();
    $data = $serializer->deserialize($json, Commande::class, 'json');
        //dd($data);
    $commande->setStatus($data->getStatus());
    $commande->setPaymentMethod($data->getPaymentMethod());
    $commande->setPaymentBrand($data->getPaymentBrand());
    $commande->setCcLast($data->getCcLast());
    $commande->setPaymentStatus($data->getPaymentStatus());
         //dd($data);
    $entityManager->flush();

    $json = $serializer->serialize($commande, 'json', ['groups' => 'read', 'max_depth' => 1]);
    return new JsonResponse($json, Response::HTTP_OK, [], true);
}*/


/**
 * @Route("/api/commandes/{id}/{status}", name="api_commandes_update", methods={"GET"})
 */
public function updateCommandes(Request $request, SerializerInterface $serializer, int $id, string $status): JsonResponse
{
    $entityManager = $this->getDoctrine()->getManager();
    $commande = $entityManager->getRepository(Commande::class)->find($id);

    if (!$commande) {
        return new JsonResponse(['message' => 'Commande not found.'], Response::HTTP_NOT_FOUND);
    }

    $commande->setStatus($status);
    $entityManager->flush();

    $data = [
        'id' => $commande->getId(),
        'date_creation' => $commande->getDateCreation(),
        'status' => $commande->getStatus(),
        'user' => $commande->getUser(),
        'payment_method' => $commande->getPaymentMethod(),
        'payment_brand' => $commande->getPaymentBrand(),
        'cc_last' => $commande->getCcLast(),
        'payment_status' => $commande->getPaymentStatus(),
    ];

    $json = $serializer->serialize($data, 'json', ['groups' => 'read', 'max_depth' => 1]);

    return new JsonResponse($json, Response::HTTP_OK, [], true);
}


/*-------------------------Delete Json--------------------*/
    #[Route('/api/commandes/{id}', name: 'api_commandes_delete', methods: ['DELETE'])]
public function deleteCommandes(Commande $commande): JsonResponse
{
    $entityManager = $this->getDoctrine()->getManager();
    $entityManager->remove($commande);
    $entityManager->flush();

    return new JsonResponse(['message' => 'commande deleted successfully.'], Response::HTTP_OK);
}


}
