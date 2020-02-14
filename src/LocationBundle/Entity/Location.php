<?php

namespace LocationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * Location
 *
 * @ORM\Table(name="location")
 * @ORM\Entity(repositoryClass="LocationBundle\Repository\LocationRepository")
 */
class Location
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
     * @ORM\Column(name="matricule", type="string")
     */
    private $matricule;

    /**
     * @var string
     *
     * @ORM\Column(name="marque", type="string")
     */
    private $marque;

    /**
     * @var string
     *
     * @ORM\Column(name="model", type="string")
     */
    private $model;

    /**
     * @var string
     *
     * @ORM\Column(name="category", type="string")
     */
    private $category;

    /**
     * @var string
     *
     * @ORM\Column(name="puissance", type="string")
     */
    private $puissance;

    /**
     * @var float
     *
     * @ORM\Column(name="daily_price", type="float")
     */
    private $dailyPrice;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string")
     */
    private $type;


    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinColumn(name="owner",referencedColumnName="id")
     */
    private $utilisateur;



    /**
     * Get id.
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
    public function getMatricule()
    {
        return $this->matricule;
    }

    /**
     * @param string $matricule
     */
    public function setMatricule($matricule)
    {
        $this->matricule = $matricule;
    }

    /**
     * @return mixed
     */
    public function getUtilisateur()
    {
        return $this->utilisateur;
    }

    /**
     * @param mixed $utilisateur
     */
    public function setUtilisateur($utilisateur)
    {
        $this->utilisateur = $utilisateur;
    }

    /**
     * @return string
     */
    public function getMarque()
    {
        return $this->marque;
    }

    /**
     * @param string $marque
     */
    public function setMarque($marque)
    {
        $this->marque = $marque;
    }

    /**
     * @return string
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * @param string $model
     */
    public function setModel($model)
    {
        $this->model = $model;
    }

    /**
     * @return string
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param string $category
     */
    public function setCategory($category)
    {
        $this->category = $category;
    }

    /**
     * @return string
     */
    public function getPuissance()
    {
        return $this->puissance;
    }

    /**
     * @param string $puissance
     */
    public function setPuissance($puissance)
    {
        $this->puissance = $puissance;
    }

    /**
     * @return float
     */
    public function getDailyPrice()
    {
        return $this->dailyPrice;
    }

    /**
     * @param float $dailyPrice
     */
    public function setDailyPrice($dailyPrice)
    {
        $this->dailyPrice = $dailyPrice;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }




}
