<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Reserve
 *
 * @ORM\Table(name="reserve")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ReserveRepository")
 */
class Reserve
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
     * @var int
     *
     * @ORM\Column(name="personneId", type="integer")
     */
    private $personneId;

    /**
     * @var int
     *
     * @ORM\Column(name="salleId", type="integer")
     */
    private $salleId;

    /**
     * @var int
     *
     * @ORM\Column(name="duree", type="integer")
     */
    private $duree;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateDebut", type="timestamp")
     */
    private $dateDebut;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateFin", type="timestamp")
     */
    private $dateFin;

    /**
     * @var int
     *
     * @ORM\Column(name="code", type="integer")
     */
    private $code;

    /**
     * @var int
     *
     * @ORM\Column(name="status", type="integer")
     */
    private $status;


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
     * Set personneId
     *
     * @param integer $personneId
     *
     * @return Reserve
     */
    public function setPersonneId($personneId)
    {
        $this->personneId = $personneId;

        return $this;
    }

    /**
     * Get personneId
     *
     * @return int
     */
    public function getPersonneId()
    {
        return $this->personneId;
    }

    /**
     * Set salleId
     *
     * @param integer $salleId
     *
     * @return Reserve
     */
    public function setSalleId($salleId)
    {
        $this->salleId = $salleId;

        return $this;
    }

    /**
     * Get salleId
     *
     * @return int
     */
    public function getSalleId()
    {
        return $this->salleId;
    }

    /**
     * Set duree
     *
     * @param integer $duree
     *
     * @return Reserve
     */
    public function setDuree($duree)
    {
        $this->duree = $duree;

        return $this;
    }

    /**
     * Get duree
     *
     * @return int
     */
    public function getDuree()
    {
        return $this->duree;
    }

    /**
     * Set dateDebut
     *
     * @param \DateTime $dateDebut
     *
     * @return Reserve
     */
    public function setDateDebut($dateDebut)
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }

    /**
     * Get dateDebut
     *
     * @return \DateTime
     */
    public function getDateDebut()
    {
        return $this->dateDebut;
    }

    /**
     * Set dateFin
     *
     * @param \DateTime $dateFin
     *
     * @return Reserve
     */
    public function setDateFin($dateFin)
    {
        $this->dateFin = $dateFin;

        return $this;
    }

    /**
     * Get dateFin
     *
     * @return \DateTime
     */
    public function getDateFin()
    {
        return $this->dateFin;
    }

    /**
     * Set code
     *
     * @param integer $code
     *
     * @return Reserve
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code
     *
     * @return int
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set status
     *
     * @param integer $status
     *
     * @return Reserve
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }
}

