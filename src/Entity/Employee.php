<?php
// src/Entity/employees.php
namespace Entity;

use Doctrine\ORM\Mapping as ORM;

use Entity\Store;

/**
 * @ORM\Entity
 * @ORM\Table(name="employees")
 */
class Employee {
    /** @var int */
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $employee_id;

    /**
     * @ORM\ManyToOne(targetEntity="Store", fetch="EAGER")
     * @ORM\JoinColumn(name="store_id", referencedColumnName="store_id", nullable=false)
     */
    private ?Store $store;

    /** @var string */
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $employee_name;

    /** @var string */
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $employee_email;

    /** @var string */
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $employee_password;

    /** @var string */
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $employee_role;

    // Getters and setters for all properties

    /**
     * Get the value of employee_id
     *
     * @return int
     */
    public function getEmployeeId(): int {
        return $this->employee_id;
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
     * Get the value of employee_name
     *
     * @return string
     */
    public function getEmployeeName(): string {
        return $this->employee_name;
    }

    /**
     * Set the value of employee_name
     *
     * @param string $employee_name
     */
    public function setEmployeeName(string $employee_name): void {
        $this->employee_name = $employee_name;
    }

    /**
     * Get the value of employee_email
     *
     * @return string
     */
    public function getEmployeeEmail(): string {
        return $this->employee_email;
    }

    /**
     * Set the value of employee_email
     *
     * @param string $employee_email
     */
    public function setEmployeeEmail(string $employee_email): void {
        $this->employee_email = $employee_email;
    }

    /**
     * Get the value of employee_password
     *
     * @return string
     */
    public function getEmployeePassword(): string {
        return $this->employee_password;
    }

    /**
     * Set the value of employee_password
     *
     * @param string $employee_password
     */
    public function setEmployeePassword(string $employee_password): void {
        $this->employee_password = $employee_password;
    }

    /**
     * Get the value of employee_role
     *
     * @return string
     */
    public function getEmployeeRole(): string {
        return $this->employee_role;
    }

    /**
     * Set the value of employee_role
     *
     * @param string $employee_role
     */
    public function setEmployeeRole(string $employee_role): void {
        $this->employee_role = $employee_role;
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
            'employee_id' => $this->employee_id,
            'employee_name' => $this->employee_name,
            'employee_email' => $this->employee_email,
            'employee_password' => $this->employee_password,
            'employee_role' => $this->employee_role,
            'store' => $depth > 0 && $this->store ? $this->store->toArray($depth - 1) : null,
        ];
    }

}
?>