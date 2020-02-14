<?php

namespace LocationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * Contrat
 *
 * @ORM\Table(name="contrat")
 * @ORM\Entity(repositoryClass="LocationBundle\Repository\ContratRepository")
 */
class Contrat
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
     * @var datetime
     *
     * @ORM\Column(name="date_debut_location", type="datetime")
     */
    private $dateDebutLocation;

    /**
     * @var datetime
     *
     * @ORM\Column(name="date_fin_location", type="datetime")
     */
    private $dateFinLocation;

    /**
     * @var datetime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string")
     */
    private $type;


    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinColumn(name="owner",referencedColumnName="id", onDelete="CASCADE")
     */
    private $utilisateur;


    /**
     * @ORM\ManyToOne(targetEntity="LocationBundle\Entity\Location")
     * @ORM\JoinColumn(name="location",referencedColumnName="id", onDelete="CASCADE")
     */
    private $location;
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
     * @return datetime
     */
    public function getDateDebutLocation()
    {
        return $this->dateDebutLocation;
    }

    /**
     * @param datetime $dateDebutLocation
     */
    public function setDateDebutLocation($dateDebutLocation)
    {
        $this->dateDebutLocation = $dateDebutLocation;
    }

    /**
     * @return datetime
     */
    public function getDateFinLocation()
    {
        return $this->dateFinLocation;
    }

    /**
     * @param datetime $dateFinLocation
     */
    public function setDateFinLocation($dateFinLocation)
    {
        $this->dateFinLocation = $dateFinLocation;
    }

    /**
     * @return datetime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param datetime $date
     */
    public function setDate($date)
    {
        $this->date = $date;
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
     * @return mixed
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * @param mixed $location
     */
    public function setLocation($location)
    {
        $this->location = $location;
    }



}
