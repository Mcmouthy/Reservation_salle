<?php

namespace AppBundle\Entity\Reservation;

/**
 * Salle
 */
class Salle
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var int
     */
    private $typeSalle;

    /**
     * @var string
     */
    private $numeroSalle;

    /**
     * @var int
     */
    private $capaciteSalle;


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
     * Set typeSalle
     *
     * @param integer $typeSalle
     *
     * @return Salle
     */
    public function setTypeSalle($typeSalle)
    {
        $this->typeSalle = $typeSalle;

        return $this;
    }

    /**
     * Get typeSalle
     *
     * @return int
     */
    public function getTypeSalle()
    {
        return $this->typeSalle;
    }

    /**
     * Set numeroSalle
     *
     * @param string $numeroSalle
     *
     * @return Salle
     */
    public function setNumeroSalle($numeroSalle)
    {
        $this->numeroSalle = $numeroSalle;

        return $this;
    }

    /**
     * Get numeroSalle
     *
     * @return string
     */
    public function getNumeroSalle()
    {
        return $this->numeroSalle;
    }

    /**
     * Set capaciteSalle
     *
     * @param integer $capaciteSalle
     *
     * @return Salle
     */
    public function setCapaciteSalle($capaciteSalle)
    {
        $this->capaciteSalle = $capaciteSalle;

        return $this;
    }

    /**
     * Get capaciteSalle
     *
     * @return int
     */
    public function getCapaciteSalle()
    {
        return $this->capaciteSalle;
    }
}

