<?php

namespace App\Controller;

use App\Entity\Offer;
use App\Form\OfferType;
use App\Repository\CustomerRepository;
use App\Repository\OfferRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/offres')]
class OfferController extends AbstractController
{
    #[Route('/', name: 'offer_index', methods: ['GET'])]
    public function index(OfferRepository $offerRepository): Response
    {
        $offer = new Offer();
        $form = $this->createForm(OfferType::class, $offer);

        return $this->renderForm('pages/offer/index.html.twig', [
            'offers' => $offerRepository->findAll(),
            'form' => $form
        ]);
    }

    #[Route('/nouveau', name: 'offer_new', methods: ['POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, CustomerRepository $customerRepository): Response
    {
        $offer = new Offer();
        $form = $this->createForm(OfferType::class, $offer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $offer->setInsertDate(new \DateTime());

            if ($offer->getAllCustomer()) {
                $customers = $customerRepository->findAll();
                foreach ($customers as $customer) {
                    $offer->addCustomer($customer);
                }
            }

            $entityManager->persist($offer);
            $entityManager->flush();
        }

        return $this->redirectToRoute('offer_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}', name: 'offer_show', methods: ['GET'])]
    public function show(Offer $offer): Response
    {
        $body = [];
        $body['id'] = $offer->getId();
        $body['title'] = $offer->getTitle();
        $body['notified_on'] = $offer->getNotifiedOn();
        $body['notified_after_store'] = $offer->getNotifiedAfterStore();
        $body['new_customer'] = $offer->getNewCustomer();
        $body['all_customer'] = $offer->getAllCustomer();
        $body['after_visit_store'] = $offer->getAfterVisitStore();
        $body['start_offer_on'] = $offer->getStartOfferOn();
        $body['end_offer_on'] = $offer->getEndOfferOn();

        foreach ($offer->getCustomers() as $customer) {
            $body['customer'][$customer->getId()] = $customer->getName();
        }
        return $this->json($body);
    }

    #[Route('/editer/{id}', name: 'offer_edit', methods: ['POST'])]
    public function edit(Request $request, Offer $offer, EntityManagerInterface $entityManager, CustomerRepository $customerRepository): Response
    {
        $form = $this->createForm(OfferType::class, $offer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if ($offer->getAllCustomer()) {
                $customers = $customerRepository->findAll();
                foreach ($customers as $customer) {
                    $offer->addCustomer($customer);
                }
            }

            $offer->setUpdatedDate(new \DateTime());
            $entityManager->flush();
        }

        return $this->redirectToRoute('offer_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}', name: 'offer_delete', methods: ['POST'])]
    public function delete(Request $request, Offer $offer, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete', $request->request->get('_token'))) {
            $entityManager->remove($offer);
            $entityManager->flush();
        }

        return $this->redirectToRoute('offer_index', [], Response::HTTP_SEE_OTHER);
    }
}
