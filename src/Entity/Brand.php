<?php
// src/Entity/brands.php
namespace Entity;

use Doctrine\ORM\Mapping as ORM;

use Doctrine\Common\Collections\Collection;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="brands")
 */
class Brand {
    /** @var int */
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $brand_id;

    /** @var string */
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $brand_name;

    /**
     * @ORM\OneToMany(targetEntity="Product", mappedBy="brand")
     */
    private $products;

    // Getters and setters for $brand_id and $brand_name

    public function __construct() {
        $this->products = new ArrayCollection();
    }

    /**
     * Get the value of brand_id
     * 
     * @return int
     */
    public function getBrandId(): int {
        return $this->brand_id;
    }

    /**
     * Get the value of brand_name
     *
     * @return string
     */
    public function getBrandName(): string {
        return $this->brand_name;
    }

    /**
     * Set the value of brand_name
     *
     * @param string $brand_name
     */
    public function setBrandName(string $brand_name): void {
        $this->brand_name = $brand_name;
    }

    /**
     * Get the value of products
     *
     * @return Collection
     */
    public function getProducts(): Collection {
        return $this->products;
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
            'brand_id' => $this->brand_id,
            'brand_name' => $this->brand_name,
        ];
    }
}
?>