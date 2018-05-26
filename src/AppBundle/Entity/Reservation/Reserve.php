<?php

namespace AppBundle\Entity\Reservation;

/**
 * Reserve
 */
class Reserve
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var int
     */
    private $idSalle;

    /**
     * @var int
     */
    private $dureeReservation;

    /**
     * @var \DateTime
     */
    private $dateDebut;

    /**
     * @var \DateTime
     */
    private $dateFin;

    /**
     * @var int
     */
    private $codeReservation;

    /**
     * @var int
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
     * Set idSalle
     *
     * @param integer $idSalle
     *
     * @return Reserve
     */
    public function setIdSalle($idSalle)
    {
        $this->idSalle = $idSalle;

        return $this;
    }

    /**
     * Get idSalle
     *
     * @return int
     */
    public function getIdSalle()
    {
        return $this->idSalle;
    }

    /**
     * Set dureeReservation
     *
     * @param integer $dureeReservation
     *
     * @return Reserve
     */
    public function setDureeReservation($dureeReservation)
    {
        $this->dureeReservation = $dureeReservation;

        return $this;
    }

    /**
     * Get dureeReservation
     *
     * @return int
     */
    public function getDureeReservation()
    {
        return $this->dureeReservation;
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
     * Set codeReservation
     *
     * @param integer $codeReservation
     *
     * @return Reserve
     */
    public function setCodeReservation($codeReservation)
    {
        $this->codeReservation = $codeReservation;

        return $this;
    }

    /**
     * Get codeReservation
     *
     * @return int
     */
    public function getCodeReservation()
    {
        return $this->codeReservation;
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

