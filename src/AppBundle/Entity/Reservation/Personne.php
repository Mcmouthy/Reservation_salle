<?php

namespace AppBundle\Entity\Reservation;

/**
 * Personne
 */
class Personne
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var int
     */
    private $idNiveau;

    /**
     * @var string
     */
    private $nom;

    /**
     * @var string
     */
    private $prenom;


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
     * Set idNiveau
     *
     * @param integer $idNiveau
     *
     * @return Personne
     */
    public function setIdNiveau($idNiveau)
    {
        $this->idNiveau = $idNiveau;

        return $this;
    }

    /**
     * Get idNiveau
     *
     * @return int
     */
    public function getIdNiveau()
    {
        return $this->idNiveau;
    }

    /**
     * Set nom
     *
     * @param string $nom
     *
     * @return Personne
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set prenom
     *
     * @param string $prenom
     *
     * @return Personne
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;

        return $this;
    }

    /**
     * Get prenom
     *
     * @return string
     */
    public function getPrenom()
    {
        return $this->prenom;
    }
}

