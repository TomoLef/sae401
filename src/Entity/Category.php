<?php
// src/Entity/categories.php
namespace Entity;

use Doctrine\ORM\Mapping as ORM;

use Doctrine\Common\Collections\Collection;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="categories")
 */
class Category {
    /** @var int */
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $category_id;

    /** @var string */
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $category_name;

    /**
     * @ORM\OneToMany(targetEntity="Product", mappedBy="category")
     */
    private $products;

    // Getters and setters for $category_id and $category_name

    public function __construct() {
        $this->products = new ArrayCollection();
    }

    /**
     * Get the value of category_id
     *
     * @return int
     */
    public function getCategoryId(): int {
        return $this->category_id;
    }

    /**
     * Get the value of category_name
     *
     * @return string
     */
    public function getCategoryName(): string {
        return $this->category_name;
    }

    /**
     * Set the value of category_name
     *
     * @param string $category_name
     */
    public function setCategoryName(string $category_name): void {
        $this->category_name = $category_name;
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
            'category_id' => $this->category_id,
            'category_name' => $this->category_name,
        ];
    }
}
?>