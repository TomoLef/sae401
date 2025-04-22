<?php
// src/Entity/stocks.php
namespace Entity;

use Doctrine\ORM\Mapping as ORM;

use Entity\Store;
use Entity\Product;

/**
 * @ORM\Entity
 * @ORM\Table(name="stocks")
 */
class Stock {
    /** @var int */
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $stock_id;

    /**
     * @ORM\ManyToOne(targetEntity="Store", inversedBy="stocks")
     * @ORM\JoinColumn(name="store_id", referencedColumnName="store_id", nullable=false)
     */
    private ?Store $store;

    /**
     * @ORM\ManyToOne(targetEntity="Product", inversedBy="stocks")
     * @ORM\JoinColumn(name="product_id", referencedColumnName="product_id", nullable=false)
     */
    private ?Product $product;

    /** @var int */
    /**
     * @ORM\Column(type="integer")
     */
    private $quantity;

    // Getters and setters for all properties

    /**
     * Get the value of stock_id
     *
     * @return int
     */
    public function getStockId(): int {
        return $this->stock_id;
    }

    /**
     * Get the value of store
     *
     * @return Store
     */
    public function getStore(): Store {
        return $this->store;
    }

    /**
     * Set the value of store
     *
     * @param Store $store
     */
    public function setStore(Store $store): void {
        $this->store = $store;
    }

    /**
     * Get the value of product
     *
     * @return Product
     */
    public function getProduct(): Product {
        return $this->product;
    }

    /**
     * Set the value of product
     *
     * @param Product $product
     */
    public function setProduct(Product $product): void {
        $this->product = $product;
    }

    /**
     * Get the value of quantity
     *
     * @return int
     */
    public function getQuantity(): int {
        return $this->quantity;
    }

    /**
     * Set the value of quantity
     *
     * @param int $quantity
     */
    public function setQuantity(int $quantity): void {
        $this->quantity = $quantity;
    }
    
    //function autre

    public function jsonSerialize(){
        $res = array();
        foreach ($this as $key => $value) {
            $res[$key] = $value;
        }
        return $res;
    }

    public function toArray(int $depth = 1): array {
        return [
            'stock_id' => $this->stock_id,
            'store' => $depth > 0 && $this->store ? $this->store->toArray($depth - 1) : null,
            'product' => $depth > 0 && $this->product ? $this->product->toArray($depth - 1) : null,
            'quantity' => $this->quantity,
        ];
    }
}
?>