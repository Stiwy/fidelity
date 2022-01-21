<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Entity\Offer;
use App\Form\CustomerType;
use App\Repository\CustomerRepository;
use App\Repository\OfferRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/clients')]
class CustomerController extends AbstractController
{
    #[Route('/', name: 'customer_index', methods: ['GET', 'POST'])]
    public function index(CustomerRepository $customerRepository, OfferRepository $offerRepository): Response
    {
        $customer = new Customer();
        $form = $this->createForm(CustomerType::class, $customer);

        return $this->renderForm('pages/customer/index.html.twig', [
            'customers' => $customerRepository->findAll(),
            'offers' => $offerRepository->findAll(),
            'form' => $form
        ]);
    }

    #[Route('/nouveau', name: 'customer_new', methods: ['POST'])]
    public function new(Request $request, Session $session, EntityManagerInterface $entityManager, OfferRepository $offerRepository): Response
    {
        $customer = new Customer();
        $form = $this->createForm(CustomerType::class, $customer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (empty($customer->getEmail()) && empty($customer->getPhone())) {
                $session->getFlashBag()->add(
                    'error',
                    'Il est obligatoire de renseigner un email ou un numéro de téléphone !'
                );
            } else {
                $offersNewCustomer = $offerRepository->findBy(['new_customer' => true]);
                $customer->setInsertDate(new \DateTime());
                $customer->setLastStoreVisit(new \DateTime());

                foreach ($offersNewCustomer as $offer) {
                    $customer->addOffer($offer);
                }

                $entityManager->persist($customer);
                $entityManager->flush();

                $session->getFlashBag()->add(
                    'success',
                    'Nouveau client créée avec succès !'
                );
            }
        }elseif ($form->isSubmitted()) {
            $session->getFlashBag()->add(
                'error',
                'Les informations saisies sont incorrectes !'
            );
        }

        return $this->redirectToRoute('customer_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}', name: 'customer_show', methods: ['GET'])]
    public function show(Customer $customer): Response
    {
        $body = [];
        $body['id'] = $customer->getId();
        $body['name'] = $customer->getName();
        $body['phone'] = $customer->getPhone();
        $body['email'] = $customer->getEmail();
        $body['last_store_visit'] = $customer->getLastStoreVisit();

        foreach ($customer->getOffers() as $offer) {
            $body['offers'][$offer->getId()] = $offer->getTitle();
        }
        return $this->json($body);
    }

    #[Route('/editer/{id}', name: 'customer_edit', methods: ['POST'])]
    public function edit(Request $request, Session $session, Customer $customer, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CustomerType::class, $customer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (empty($customer->getEmail()) && empty($customer->getPhone())) {
                $session->getFlashBag()->add(
                    'error',
                    'Il est obligatoire de renseigner un email ou un numéro de téléphone !'
                );
            } else {
                $customer->setUpdatedDate(new \DateTime());
                $entityManager->flush();

                $session->getFlashBag()->add(
                    'success',
                    'Client édité avec succès !'
                );
            }
        } elseif ($form->isSubmitted()) {
            $session->getFlashBag()->add(
                'error',
                'Les informations saisies sont incorrectes !'
            );
        }

        return $this->redirectToRoute('customer_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}', name: 'customer_delete', methods: ['POST'])]
    public function delete(Request $request, Session$session, Customer $customer, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete', $request->request->get('_token'))) {
            $entityManager->remove($customer);
            $entityManager->flush();

            $session->getFlashBag()->add(
                'warning',
                'Le client a bien été supprimé !'
            );
        }

        return $this->redirectToRoute('customer_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/offre/{id}', name: 'customer_offer', methods: ['POST'])]
    public function addOffer(Request $request, Session $session, Customer $customer, EntityManagerInterface $entityManager, OfferRepository $offerRepository): Response
    {
        if ($this->isCsrfTokenValid('offer_customer', $request->request->get('_token'))) {
            if ($idOffer = $request->get('offer')['id']) {
                $offer = $offerRepository->findOneBy(['id' => $idOffer]);
                $customer->addOffer($offer);
                $entityManager->flush();

                $session->getFlashBag()->add(
                    'info',
                    "L’offre à bien été ajoutée !"
                );
            }
        }

        return $this->redirectToRoute('customer_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/offre/{idCustomer}/{idOffer}', name: 'customer_offer_remove', methods: ['GET'])]
    public function removeOffer($idCustomer, Session $session, $idOffer, EntityManagerInterface $entityManager, CustomerRepository $customerRepository, OfferRepository $offerRepository): Response
    {
        $customer = $customerRepository->findOneBy(['id' => $idCustomer]);
        $offer = $offerRepository->findOneBy(['id' => $idOffer]);

        if (!empty($customer) && !empty($offer)) {
            $customer->removeOffer($offer);
            $entityManager->flush();

            $session->getFlashBag()->add(
                'info',
                "L’offre à bien été retirée !"
            );
        }

        return $this->redirectToRoute('customer_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/visite_magasin/{id}', name: 'customer_after_visit_store', methods: ['POST'])]
    public function afterVisitStore(Request $request, Session $session, Customer $customer, EntityManagerInterface $entityManager, OfferRepository $offerRepository): Response
    {
        if ($this->isCsrfTokenValid('offer_after_visit_store', $request->request->get('_token'))) {
            $customer->setLastStoreVisit(new \DateTime());
            $entityManager->flush();

            $session->getFlashBag()->add(
                'info',
                "Le client est passé au magasin !"
            );
        }

        return $this->redirectToRoute('customer_index', [], Response::HTTP_SEE_OTHER);
    }
}
