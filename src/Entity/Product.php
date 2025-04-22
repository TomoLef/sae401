<?php
// src/Entity/products.php

namespace Entity;

use Doctrine\ORM\Mapping as ORM;

use Doctrine\Common\Collections\Collection;

use Doctrine\Common\Collections\ArrayCollection;

use Entity\Brand;
use Entity\Category;

/**
 * @ORM\Entity
 * @ORM\Table(name="products")
 */
class Product {
    /** @var int */
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $product_id;

    /** @var string */
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $product_name;

    /**
     * @ORM\ManyToOne(targetEntity="Brand")
     * @ORM\JoinColumn(name="brand_id", referencedColumnName="brand_id", nullable=false)
     */
    private ?Brand $brand;

    /**
     * @ORM\ManyToOne(targetEntity="Category")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="category_id", nullable=false)
     */
    private ?Category $category;

    /** @var int */
    /**
     * @ORM\Column(type="smallint")
     */
    private $model_year;

    /** @var float */
    /**
     * @ORM\Column(type="decimal", precision=10, scale=2)
     */
    private $list_price;

    /**
     * @ORM\OneToMany(targetEntity="Stock", mappedBy="product")
     */
    private $stocks;

    // Getters and setters for all properties

    public function __construct() {
        $this->stocks = new ArrayCollection();
    }

    /**
     * Get the value of product_id
     *
     * @return int
     */
    public function getProductId(): int {
        return $this->product_id;
    }

    /**
     * Get the value of product_name
     *
     * @return string
     */
    public function getProductName(): string {
        return $this->product_name;
    }

    /**
     * Set the value of product_name
     *
     * @param string $product_name
     */
    public function setProductName(string $product_name): void {
        $this->product_name = $product_name;
    }

    /**
     * Get the value of brand
     *
     * @return Brand
     */
    public function getBrand(): Brand {
        return $this->brand;
    }

    /**
     * Set the value of brand
     *
     * @param Brand $brand
     */
    public function setBrand(Brand $brand): void {
        $this->brand = $brand;
    }

    /**
     * Get the value of category
     *
     * @return Category
     */
    public function getCategory(): Category {
        return $this->category;
    }

    /**
     * Set the value of category
     *
     * @param Category $category
     */
    public function setCategory(Category $category): void {
        $this->category = $category;
    }

    /**
     * Get the value of model_year
     *
     * @return int
     */
    public function getModelYear(): int {
        return $this->model_year;
    }

    /**
     * Set the value of model_year
     *
     * @param int $model_year
     */
    public function setModelYear(int $model_year): void {
        $this->model_year = $model_year;
    }

    /**
     * Get the value of list_price
     *
     * @return float
     */
    public function getListPrice(): float {
        return $this->list_price;
    }

    /**
     * Set the value of list_price
     *
     * @param float $list_price
     */
    public function setListPrice(float $list_price): void {
        $this->list_price = $list_price;
    }

    /**
     * Get the value of stocks
     *
     * @return Collection
     */
    public function getStocks(): Collection {
        return $this->stocks;
    }
    
    //function autre

    public function jsonSerialize(){
        $res = array();
        foreach ($this as $key => $value) {
            if($key=="brand"){
                $res[$key] = $value->jsonSerialize();
            }else if($key=="category"){
                $res[$key] = $value->jsonSerialize();
            }
            else $res[$key] = $value;
        }
        return $res;
    }

    public function toArray(int $depth = 1): array {
        return [
            'product_id' => $this->product_id,
            'product_name' => $this->product_name,
            'brand' => $depth > 0 && $this->brand ? $this->brand->toArray($depth - 1) : null,
            'category' => $depth > 0 && $this->category ? $this->category->toArray($depth - 1) : null,
            'model_year' => $this->model_year,
            'list_price' => $this->list_price,

        ];
    }
}
?>