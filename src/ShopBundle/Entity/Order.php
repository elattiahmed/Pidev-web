<?php
/**
 * Created by PhpStorm.
 * User: ZerOo
 * Date: 2/23/2019
 * Time: 11:41 AM
 */

namespace ShopBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Payment\CoreBundle\Entity\PaymentInstruction;

/**
 * @ORM\Table(name="orders")
 * @ORM\Entity
 */
class Order
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    /**
     * @ORM\ManyToOne(targetEntity="ShopBundle\Entity\Panier", cascade={"persist"})
     * @ORM\JoinColumn(name="produit_id" ,referencedColumnName="id" , nullable=true, onDelete="CASCADE")
     */
    private $produitP;

    /**
     * @return mixed
     */
    public function getProduitP()
    {
        return $this->produitP;
    }

    /**
     * @param mixed $produitP
     */
    public function setProduitP($produitP)
    {
        $this->produitP = $produitP;
    }




    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", cascade={"persist"})
     * @ORM\JoinColumn(name="user_id"  ,referencedColumnName="id")
     */
    private $user;

    /**
     * @var string
     *
     * @ORM\Column(name="state", type="string")
     */
    private $state;

    /**
     * @var string
     *
     * @ORM\Column(name="country", type="string")
     */
    private $country;

    /**
     * @var int
     *
     * @ORM\Column(name="zip_code", type="integer")
     */
    private $zipCode;

    /**
     * @var string
     *
     * @ORM\Column(name="etat", type="string")
     */
    private $etat;

    /**
     * @var integer
     *
     * @ORM\Column(name="phone", type="integer")
     */
    private $phone;

    /**
     * @var float
     *
     * @ORM\Column(name="price", type="float")
     */
    private $price;



    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return string
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * @param string $state
     */
    public function setState($state)
    {
        $this->state = $state;
    }

    /**
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param string $country
     */
    public function setCountry($country)
    {
        $this->country = $country;
    }

    /**
     * @return int
     */
    public function getZipCode()
    {
        return $this->zipCode;
    }

    /**
     * @param int $zipCode
     */
    public function setZipCode($zipCode)
    {
        $this->zipCode = $zipCode;
    }

    /**
     * @return string
     */
    public function getEtat()
    {
        return $this->etat;
    }

    /**
     * @param string $etat
     */
    public function setEtat($etat)
    {
        $this->etat = $etat;
    }

    /**
     * @return int
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param int $phone
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
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




}