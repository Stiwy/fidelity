<?php

namespace App\Entity;

use App\Repository\OfferRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OfferRepository::class)]
class Offer
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $title;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private $updated_date;

    #[ORM\Column(type: 'datetime')]
    private $insert_date;

    #[ORM\ManyToMany(targetEntity: Customer::class, mappedBy: 'offers')]
    private $customers;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private $notified_on;

    #[ORM\Column(type: 'boolean')]
    private $new_customer;

    #[ORM\Column(type: 'boolean')]
    private $all_customer;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private $start_offer_on;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private $end_offer_on;

    #[ORM\Column(type: 'boolean')]
    private $after_visit_store;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $notified_after_store;

    public function __construct()
    {
        $this->customers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getUpdatedDate(): ?\DateTimeInterface
    {
        return $this->updated_date;
    }

    public function setUpdatedDate(?\DateTimeInterface $updated_date): self
    {
        $this->updated_date = $updated_date;

        return $this;
    }

    public function getInsertDate(): ?\DateTimeInterface
    {
        return $this->insert_date;
    }

    public function setInsertDate(\DateTimeInterface $insert_date): self
    {
        $this->insert_date = $insert_date;

        return $this;
    }

    /**
     * @return Collection|Customer[]
     */
    public function getCustomers(): Collection
    {
        return $this->customers;
    }

    public function addCustomer(Customer $customer): self
    {
        if (!$this->customers->contains($customer)) {
            $this->customers[] = $customer;
            $customer->addOffer($this);
        }

        return $this;
    }

    public function removeCustomer(Customer $customer): self
    {
        if ($this->customers->removeElement($customer)) {
            $customer->removeOffer($this);
        }

        return $this;
    }

    public function getNotifiedOn(): ?\DateTimeInterface
    {
        return $this->notified_on;
    }

    public function setNotifiedOn(?\DateTimeInterface $notified_on): self
    {
        $this->notified_on = $notified_on;

        return $this;
    }

    public function getNewCustomer(): ?bool
    {
        return $this->new_customer;
    }

    public function setNewCustomer(bool $new_customer): self
    {
        $this->new_customer = $new_customer;

        return $this;
    }

    public function getAllCustomer(): ?bool
    {
        return $this->all_customer;
    }

    public function setAllCustomer(bool $all_customer): self
    {
        $this->all_customer = $all_customer;

        return $this;
    }

    public function getStartOfferOn(): ?\DateTimeInterface
    {
        return $this->start_offer_on;
    }

    public function setStartOfferOn(?\DateTimeInterface $start_offer_on): self
    {
        $this->start_offer_on = $start_offer_on;

        return $this;
    }

    public function getEndOfferOn(): ?\DateTimeInterface
    {
        return $this->end_offer_on;
    }

    public function setEndOfferOn(?\DateTimeInterface $end_offer_on): self
    {
        $this->end_offer_on = $end_offer_on;

        return $this;
    }

    public function getAfterVisitStore(): ?bool
    {
        return $this->after_visit_store;
    }

    public function setAfterVisitStore(bool $after_visit_store): self
    {
        $this->after_visit_store = $after_visit_store;

        return $this;
    }

    public function getNotifiedAfterStore(): ?string
    {
        return $this->notified_after_store;
    }

    public function setNotifiedAfterStore(?string $notified_after_store): self
    {
        $this->notified_after_store = $notified_after_store;

        return $this;
    }
}
