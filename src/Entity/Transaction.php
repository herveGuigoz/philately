<?php

namespace App\Entity;

use App\Repository\TransactionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TransactionRepository::class)
 */
class Transaction
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @ORM\Column(type="float")
     */
    private $price;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isClosed;

    /**
     * @ORM\ManyToOne(targetEntity=Customer::class, inversedBy="transactions")
     */
    private $customer;

    /**
     * @ORM\ManyToMany(targetEntity=Stamp::class, inversedBy="transactions")
     */
    private $stamps;

    public function __construct()
    {
        $this->stamps = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getIsClosed(): ?bool
    {
        return $this->isClosed;
    }

    public function setIsClosed(bool $isClosed): self
    {
        $this->isClosed = $isClosed;

        return $this;
    }

    public function getCustomer(): ?Customer
    {
        return $this->customer;
    }

    public function setCustomer(?Customer $customer): self
    {
        $this->customer = $customer;

        return $this;
    }

    /**
     * @return Collection|Stamp[]
     */
    public function getStamps(): Collection
    {
        return $this->stamps;
    }

    public function addStamp(Stamp $stamp): self
    {
        if (!$this->stamps->contains($stamp)) {
            $this->stamps[] = $stamp;
        }

        return $this;
    }

    public function removeStamp(Stamp $stamp): self
    {
        $this->stamps->removeElement($stamp);

        return $this;
    }
}
