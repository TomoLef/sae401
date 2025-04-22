<?php
// src/Entity/stores.php

namespace Entity;

use \Doctrine\Common\Collections\Collection;

use \Doctrine\Common\Collections\ArrayCollection;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="stores")
 */
class Store {
    /** @var int */
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $store_id;

    /** @var string */
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $store_name;

    /** @var ?string */
    /**
     * @ORM\Column(type="string", length=25, nullable=true)
     */
    private $phone;

    /** @var ?string */
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $email;

    /** @var ?string */
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $street;

    /** @var ?string */
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $city;

    /** @var ?string */
    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     */
    private $state;

    /** @var ?string */
    /**
     * @ORM\Column(type="string", length=5, nullable=true)
     */
    private $zip_code;

    /**
     * @ORM\OneToMany(targetEntity="Stock", mappedBy="store")
     */
    private Collection $stocks;

    /**
     * @ORM\OneToMany(targetEntity="Employee", mappedBy="store")
     */
    private Collection $employees;

    // Getters and setters for all properties

    /**
     * Get the value of store_id
     *
     * @return int
     */
    public function getStoreId(): int {
        return $this->store_id;
    }

    /**
     * Get the value of store_name
     *
     * @return string
     */
    public function getStoreName(): string {
        return $this->store_name;
    }

    /**
     * Set the value of store_name
     *
     * @param string $store_name
     */
    public function setStoreName(string $store_name): void {
        $this->store_name = $store_name;
    }

    /**
     * Get the value of phone
     *
     * @return ?string
     */
    public function getPhone(): ?string {
        return $this->phone;
    }

    /**
     * Set the value of phone
     *
     * @param ?string $phone
     */
    public function setPhone(?string $phone): void {
        $this->phone = $phone;
    }

    /**
     * Get the value of email
     *
     * @return ?string
     */
    public function getEmail(): ?string {
        return $this->email;
    }

    /**
     * Set the value of email
     *
     * @param ?string $email
     */
    public function setEmail(?string $email): void {
        $this->email = $email;
    }

    /**
     * Get the value of street
     *
     * @return ?string
     */
    public function getStreet(): ?string {
        return $this->street;
    }

    /**
     * Set the value of street
     *
     * @param ?string $street
     */
    public function setStreet(?string $street): void {
        $this->street = $street;
    }

    /**
     * Get the value of city
     *
     * @return ?string
     */
    public function getCity(): ?string {
        return $this->city;
    }

    /**
     * Set the value of city
     *
     * @param ?string $city
     */
    public function setCity(?string $city): void {
        $this->city = $city;
    }

    /**
     * Get the value of state
     *
     * @return ?string
     */
    public function getState(): ?string {
        return $this->state;
    }

    /**
     * Set the value of state
     *
     * @param ?string $state
     */
    public function setState(?string $state): void {
        $this->state = $state;
    }

    /**
     * Get the value of zip_code
     *
     * @return ?string
     */
    public function getZipCode(): ?string {
        return $this->zip_code;
    }

    /**
     * Set the value of zip_code
     *
     * @param ?string $zip_code
     */
    public function setZipCode(?string $zip_code): void {
        $this->zip_code = $zip_code;
    }

    /**
     * Get the value of stocks
     *
     * @return Collection
     */
    public function getStocks(): Collection {
        return $this->stocks;
    }

    /**
     * Get the value of employees
     *
     * @return Collection
     */
    public function getEmployees(): Collection {
        return $this->employees;
    }
    
    //function autre

    public function jsonSerialize(){
        $res = array();
        foreach ($this as $key => $value) {
            $res[$key] = $value;
        }
        return $res;
    }

    public function toArray(int $depth = 1): array{
        return [
            'store_id' => $this->store_id,
            'store_name' => $this->store_name,
            'phone' => $this->phone,
            'email' => $this->email,
            'street' => $this->street,
            'city' => $this->city,
            'state' => $this->state,
            'zip_code' => $this->zip_code,
        ];
    }

}
?>