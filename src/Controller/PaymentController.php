<?php

namespace App\Controller;

use App\Form\PaymentType;
use App\Repository\ProduitRepository;
use Stripe\Charge;
use Stripe\Exception\ApiConnectionException;
use Stripe\Exception\ApiErrorException;
use Stripe\Exception\AuthenticationException;
use Stripe\Exception\CardException;
use Stripe\Exception\InvalidRequestException;
use Stripe\Exception\RateLimitException;
use Stripe\Stripe;
use Stripe\Token;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class PaymentController extends AbstractController
{
    #[Route("/payment", name: "payment")]
    public function paymentForm(SessionInterface $session, ProduitRepository $produitRepository, Request $request): Response
    {
        //get the total amount of the cart
        $panier = $session->get('panier', []);
        $panierWithData = [];
        foreach ($panier as $id => $qte) {
            $panierWithData[] = [
                'product' => $produitRepository->find($id),
                'qte' => $qte
            ];
        }
        $totalAmount = 0;
        foreach ($panierWithData as $item) {
            $totalAmount += $item['product']->getPrix() * $item['qte'];
        }
        $form = $this->createForm(PaymentType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            Stripe::setApiKey($_ENV['STRIPE_SECRET_KEY']);
            try {
                $token = Token::create([
                    'card' => [
                        'number' => $form->get('cardNumber')->getData(),
                        'exp_month' => $form->get('expMonth')->getData(),
                        'exp_year' => $form->get('expYear')->getData(),
                        'cvc' => $form->get('cvc')->getData(),
                    ],
                    'amount' => $totalAmount * 100
                ]);

                $charge = Charge::create([
                    'amount' => $totalAmount * 100,
                    'currency' => 'usd',
                    'description' => 'Payment for products',
                    'source' => $token->id,
                ]);
                if ($charge->paid === true) {
                    $data = $form->getData();
                    $data->setStripeToken($token->id);
                    $this->addFlash('success', 'Payment was successful.');
                    return $this->redirectToRoute('commande_add', [
                        "payment_method" => $token->card->object,
                        "payment_brand" => $token->card->brand,
                        "payment_status" => $charge->status,
                        "cc_last" => $token->card->last4,
                        "receipt_url" => $charge->receipt_url
                    ]);
                } else {
                    $this->addFlash('error', 'Payment failed.');
                }
            } catch (CardException $e) {
                $this->addFlash('error', 'Card error: ' . $e->getMessage());
                return $this->redirectToRoute('payment');

            } catch (RateLimitException|InvalidRequestException|AuthenticationException|ApiConnectionException|ApiErrorException $e) {
                $this->addFlash('error', 'Payment error: ' . $e->getMessage());
                return $this->redirectToRoute('payment');

            } catch (\Exception $e) {
                $this->addFlash('error', 'Unexpected error: ' . $e->getMessage());
                return $this->redirectToRoute('payment');
            }
        }

        return $this->render('payment/form.html.twig', [
            'form' => $form->createView(),
            'totalAmount' => $totalAmount
        ]);
    }
}