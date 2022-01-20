<?php

namespace App\Controller;

use App\Classes\Notify;
use App\Repository\OfferRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

class CronController extends AbstractController
{
    #[Route('/cron/notified-on', name: 'cron_notified_on')]
    public function notifiedOn(OfferRepository $offerRepository): Response
    {
        $date = date("Y-m-d H", strtotime('+1 hour'));
        $offers = $offerRepository->findByNotifiedOn($date);


        $headers = 'Content-type: text/html; charset=utf-8';
        $headers .= 'To: Robé Médical <supports@robe-medical.com>';

        foreach ($offers as $offer) {
            $body = '<h1>Robé Médical</h1>';
            $body .= '<div>' . $offer->getTitle() . '</div>';

            foreach ($offer->getCustomers() as $customer) {
                mail(
                    'stiwy@robe-medical.com',
                    'Robé Médical ' . $offer->getTitle(),
                    $body,
                    $headers
                );
            }
        }

        return $this->json('Send mail success', 200);
    }

    #[Route('/cron/notified-after-store', name: 'cron_notified_after_store')]
    public function notifiedAfterStore(OfferRepository $offerRepository): Response
    {
        $offers = $offerRepository->findByNotifiedAfterStore();
        $headers = 'Content-type: text/html; charset=utf-8';
        $headers .= 'To: Robé Médical <supports@robe-medical.com>';

        foreach ($offers as $offer) {
            $body = '<h1>Robé Médical</h1>';
            $body .= '<div>' . $offer->getTitle() . '</div>';

            foreach ($offer->getCustomers() as $customer) {
                $lastStoreVisit = $customer->getLastStoreVisit();
                $dateOfSendMail = date('Y-m-d', strtotime($offer->getNotifiedAfterStore() . " day"));
                if ($dateOfSendMail == date('Y-m-d')) {
                    mail(
                        'stiwy@robe-medical.com',
                        'Robé Médical ' . $offer->getTitle(),
                        $body,
                        $headers
                    );
                }
            }
        }

        return $this->json('Send mail success', 200);
    }
}
