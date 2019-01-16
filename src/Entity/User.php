<?php
// src/Entity/User.php
namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Mgilet\NotificationBundle\Entity\NotifiableNotification;
use Mgilet\NotificationBundle\Entity\NotificationInterface;

use Mgilet\NotificationBundle\Annotation\Notifiable;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity
 * @Notifiable(name="nom")
 * @UniqueEntity(fields="email", message="Email déjà utilisé")
 * @UniqueEntity(fields="username", message="Username déjà utilisé")
 */
class User implements UserInterface,NotificationInterface
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=64, unique=true)
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    private $email;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @ORM\Column(type="string", length=64, unique=true)
     * @Assert\NotBlank()
     */
    private $username;

    /**
     * @Assert\Length(
     *      min = "5",
     *      max = "50",
     *      minMessage = "Minimum 5 caractères sont requis",
     *      maxMessage = "Vous avez dépassé la limite de 50 caractères autorisés",
     * )
     */
    private $plainPassword;

    /**
     * The below length depends on the "algorithm" you use for encoding
     * the password, but this works well with bcrypt.
     *
     * @ORM\Column(type="string", length=64)
     */
    private $password;

    /**
     * @ORM\Column(type="array")
     */
    private $roles;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $societe;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $prenom;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $titre;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $date_inscription;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $adresse;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $code_postal;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $ville;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $pays;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $telephone;

    public function __construct() {
        $this->roles = array('ROLE_USER');
    }

    // other properties and methods

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function setUsername($username)
    {
        $this->username = $username;
    }

    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    public function setPlainPassword($password)
    {
        $this->plainPassword = $password;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function getSalt()
    {
        // The bcrypt and argon2i algorithms don't require a separate salt.
        // You *may* need a real salt if you choose a different encoder.
        return null;
    }

    public function getRoles()
    {
        return $this->roles;
    }

    public function eraseCredentials()
    {
    }

    public function getSociete(): ?string
    {
        return $this->societe;
    }

    public function setSociete(string $societe): self
    {
        $this->societe = $societe;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getTitre(): ?bool
    {
        return $this->titre;
    }

    public function setTitre(?bool $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    public function getDateInscription(): ?\DateTimeInterface
    {
        return $this->date_inscription;
    }

    public function setDateInscription(\DateTimeInterface $date_inscription): self
    {
        $this->date_inscription = $date_inscription;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(?string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getCodePostal(): ?int
    {
        return $this->code_postal;
    }

    public function setCodePostal(?int $code_postal): self
    {
        $this->code_postal = $code_postal;

        return $this;
    }

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(?string $ville): self
    {
        $this->ville = $ville;

        return $this;
    }

    public function getPays(): ?string
    {
        return $this->pays;
    }

    public function setPays(?string $pays): self
    {
        $this->pays = $pays;

        return $this;
    }

    public function getTelephone(): ?int
    {
        return $this->telephone;
    }

    public function setTelephone(?int $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDate()
    {
        // TODO: Implement getDate() method.
    }

    /**
     * @param \DateTime $date
     *
     * @return NotificationInterface
     */
    public function setDate($date)
    {
        // TODO: Implement setDate() method.
    }

    /**
     * @return string Notification subject
     */
    public function getSubject()
    {
        // TODO: Implement getSubject() method.
    }

    /**
     * @param string $subject Notification subject
     *
     * @return NotificationInterface
     */
    public function setSubject($subject)
    {
        // TODO: Implement setSubject() method.
    }

    /**
     * @return string Notification message
     */
    public function getMessage()
    {
        // TODO: Implement getMessage() method.
    }

    /**
     * @param string $message Notification message
     *
     * @return NotificationInterface
     */
    public function setMessage($message)
    {
        // TODO: Implement setMessage() method.
    }

    /**
     * @return string Link to redirect the user
     */
    public function getLink()
    {
        // TODO: Implement getLink() method.
    }

    /**
     * @param string $link Link to redirect the user
     *
     * @return NotificationInterface
     */
    public function setLink($link)
    {
        // TODO: Implement setLink() method.
    }

    /**
     * @return ArrayCollection|NotifiableNotification[]
     */
    public function getNotifiableNotifications()
    {
        // TODO: Implement getNotifiableNotifications() method.
    }

    /**
     * @param NotifiableNotification $notifiableNotification
     *
     * @return NotificationInterface
     */
    public function addNotifiableNotification(NotifiableNotification $notifiableNotification)
    {
        // TODO: Implement addNotifiableNotification() method.
    }

    /**
     * @param NotifiableNotification $notifiableNotification
     *
     * @return NotificationInterface
     */
    public function removeNotifiableNotification(NotifiableNotification $notifiableNotification)
    {
        // TODO: Implement removeNotifiableNotification() method.
    }
}