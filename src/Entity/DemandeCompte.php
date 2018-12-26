<?php

namespace App\Entity;






use Symfony\Component\Validator\Constraints as Assert;



class DemandeCompte
{

    /**
     * @var string|null
     * @Assert\NotBlank()
     * @Assert\Length(
     *     min=3,max=100,
     *     minMessage = "Minimum 3 caractères sont requis",
     *     maxMessage = "Vous avez atteint la limite de 100 caractères"
     * )
     */
    private $societe;

    /**
     * @var string|null
     * @Assert\NotBlank()
     * @Assert\Length(
     *     min=3,max=100,
     *     minMessage = "Minimum 3 caractères sont requis",
     *     maxMessage = "Vous avez atteint la limite de 100 caractères"
     * )
     */
    private $nom;

    /**
     * @var string|null
     * @Assert\NotBlank()
     * @Assert\Length(
     *     min=3,max=100,
     *     minMessage = "Minimum 3 caractères sont requis",
     *     maxMessage = "Vous avez atteint la limite de 100 caractères"
     * )
     */
    private $prenom;

    /**
     * @var string|null
     *     minMessage = "Minimum 3 caractères sont requis",
     *     maxMessage = "Vous avez atteint la limite de 100 caractères"
     * )
     */
    private $adresse;

    /**
     * @var string|null
     * @Assert\Type (
     *     type="numeric",
     *     message = "Valeur non numérique"
     * )
     */
    private $code_postal;


    /**
     * @var string|null
     * @Assert\Length(min=2,max=30)
     * @Assert\Type(type="string")
     * @Assert\NotBlank()
     */
    private $pays_dest;


    /**
     * @var string|null
     * @Assert\Length(min=2,max=30)
     * @Assert\Type(type="string")
     */
    private $ville;

    /**
     * @var string|null
     * @Assert\Length(min=2,max=30)
     * @Assert\Type(type="string")
     */
    private $pays;

    /**
     * @var string|null
     * @Assert\NotBlank()
     * @Assert\Email(
     *     message = "L'email {{ value }} n'est pas un email valide",
     *     checkMX = true)
     */
    private $email;

    /**
     * @var string|null
     * @Assert\NotBlank()
     * @Assert\Type (
     *     type="numeric",
     *     message = "Valeur non numérique"
     * )
     */
    private $telephone;

    /**
     * @Assert\Type(type="bool")
     */
    private $grossiste_bool;

    /**
     * @Assert\Type(type="bool")
     */
    private $supermarche_bool;


    /**
     * @var string|null
     * @Assert\NotBlank()
     * @Assert\Length(min=10,max=255)
     */
    private $demande;

    /**
     * @return null|string
     */
    public function getSociete()
    {
        return $this->societe;
    }

    /**
     * @param null|string $societe
     * @return Contact
     */
    public function setSociete($societe)
    {
        $this->societe = $societe;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * @param null|string $nom
     * @return Contact
     */
    public function setNom($nom)
    {
        $this->nom = $nom;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * @param null|string $prenom
     * @return Contact
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getAdresse()
    {
        return $this->adresse;
    }

    /**
     * @param null|string $adresse
     * @return Contact
     */
    public function setAdresse($adresse)
    {
        $this->adresse = $adresse;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getCodePostal()
    {
        return $this->code_postal;
    }

    /**
     * @param null|string $code_postal
     * @return Contact
     */
    public function setCodePostal($code_postal)
    {
        $this->code_postal = $code_postal;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getVille()
    {
        return $this->ville;
    }

    /**
     * @param null|string $ville
     * @return Contact
     */
    public function setVille($ville)
    {
        $this->ville = $ville;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getPays()
    {
       return $this->pays;
    }

    /**
     * @param null|string $pays
     * @return Contact
     */
    public function setPays($pays)
    {
        $this->pays = $pays;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param null|string $email
     * @return Contact
     */
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getTelephone()
    {
        return $this->telephone;
    }

    /**
     * @param null|string $telephone
     * @return Contact
     */
    public function setTelephone($telephone)
    {
        $this->telephone = $telephone;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getGrossisteBool()
    {
        return $this->grossiste_bool;
    }

    /**
     * @param mixed $grossiste_bool
     * @return Contact
     */
    public function setGrossisteBool($grossiste_bool)
    {
        $this->grossiste_bool = $grossiste_bool;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSupermarcheBool()
    {
        return $this->supermarche_bool;
    }

    /**
     * @param mixed $supermarche_bool
     * @return Contact
     */
    public function setSupermarcheBool($supermarche_bool)
    {
        $this->supermarche_bool = $supermarche_bool;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getPaysDest()
    {
        return $this->pays_dest;
    }

    /**
     * @param null|string $pays_dest
     * @return Contact
     */
    public function setPaysDest($pays_dest)
    {
        $this->pays_dest = $pays_dest;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getDemande()
    {
        return $this->demande;
    }

    /**
     * @param null|string $demande
     * @return Contact
     */
    public function setDemande($demande)
    {
        $this->demande = $demande;
        return $this;
    }

}
