<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Burger
 *
 * @ORM\Table(name="burger")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\BurgerRepository")
 */
class Burger
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=36)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=128)
     */
    private $desc;

    /**
     * @var float
     *
     * @ORM\Column(name="price", type="float", scale=2)
     */
    private $price;

    /**
     * @var float
     *
     * @ORM\Column(name="supp_double", type="float", scale=2)
     */
    private $supp_double;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getDesc()
    {
        return $this->desc;
    }

    /**
     * @param string $desc
     */
    public function setDesc($desc)
    {
        $this->desc = $desc;
    }

    /**
     * @return float
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param float $price
     */
    public function setPrice($price)
    {
        $this->price = $price;
    }

    /**
     * @return float
     */
    public function getSuppDouble()
    {
        return $this->supp_double;
    }

    /**
     * @param float $supp_double
     */
    public function setSuppDouble($supp_double)
    {
        $this->supp_double = $supp_double;
    }


}

