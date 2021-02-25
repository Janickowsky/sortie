<?php

namespace App\Entity;

use App\Repository\SortieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=SortieRepository::class)
 */
class Sortie
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer", options={"unsigned=true"},name="id")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\NotBlank(message="Veuillez saisir un nom!")
     * @Assert\Length(min = 4, max = 100, minMessage = "Veuillez saisir un nom d'au moins 4 caratères",
     *      maxMessage = "Veuillez saisir un mot de passe d'au moins 100 caratères")
     */
    private $nom;

    /**
     * @ORM\Column(type="datetime", name="date_heure_debut")
     * @Assert\NotBlank(message="Veuillez saisir une date de début!")
     * @Assert\DateTime(message="La valeur saisie doit être un date heure!")
     */
    private $dateHeureDebut;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank(message="Veuillez saisir une duree!")
     * @Assert\Positive(message="La duree doit être positive!")
     *  @Assert\Type(
     *     type="integer",
     *     message="La valeur {{ value }} n'est pas un valide {{ type }}."
     * )
     */
    private $duree;

    /**
     * @ORM\Column(type="datetime", name="date_limite_inscription")
     * @Assert\NotBlank(message="Veuillez saisir une date limite d'inscription!")
     * @Assert\DateTime(message="La valeur saisie doit être un date heure!")
     */
    private $dateLimiteInscription;

    /**
     * @ORM\Column(type="smallint", name="nb_inscription_max")
     * @Assert\NotBlank(message="Veuillez saisir un nombre de place!")
     * @Assert\Positive(message="La duree doit être positive!")
     * @Assert\Type(
     *     type="integer",
     *     message="La valeur {{ value }} n'est pas un valide {{ type }}."
     * )
     */
    private $nbInscriptionMax;

    /**
     * @ORM\Column(type="text", name="infos_sortie")
     * @Assert\NotBlank(message="Veuillez saisir une description!")
     * @Assert\Type("string")
     */
    private $infosSortie;

    /**
     * @ORM\ManyToOne(targetEntity=Lieu::class, inversedBy="sorties")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank(message="Veuillez saisir un lieu!")
     */
    private $lieu;

    /**
     * @ORM\ManyToOne(targetEntity=Etat::class, inversedBy="sorties")
     * @ORM\JoinColumn(nullable=false)
     *
     */
    private $etat;

    /**
     * @ORM\ManyToOne(targetEntity=Site::class, inversedBy="sorties")
     * @ORM\JoinColumn(nullable=false)
     *
     */
    private $campus;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="mesEvenements")
     * @ORM\JoinColumn(nullable=false)
     */
    private $organisateur;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, inversedBy="sorties")
     */
    private $participants;

    public function __construct()
    {
        $this->participants = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getDateHeureDebut(): ?\DateTimeInterface
    {
        return $this->dateHeureDebut;
    }

    public function setDateHeureDebut(?\DateTimeInterface $dateHeureDebut): self
    {
        $this->dateHeureDebut = $dateHeureDebut;

        return $this;
    }

    public function getDuree(): ?int
    {
        return $this->duree;
    }

    public function setDuree(?int $duree): self
    {
        $this->duree = $duree;

        return $this;
    }

    public function getDateLimiteInscription(): ?\DateTimeInterface
    {
        return $this->dateLimiteInscription;
    }

    public function setDateLimiteInscription(?\DateTimeInterface $dateLimiteInscription): self
    {
        $this->dateLimiteInscription = $dateLimiteInscription;

        return $this;
    }

    public function getNbInscriptionMax(): ?int
    {
        return $this->nbInscriptionMax;
    }

    public function setNbInscriptionMax(?int $nbInscriptionMax): self
    {
        $this->nbInscriptionMax = $nbInscriptionMax;

        return $this;
    }

    public function getInfosSortie(): ?string
    {
        return $this->infosSortie;
    }

    public function setInfosSortie(?string $infosSortie): self
    {
        $this->infosSortie = $infosSortie;

        return $this;
    }
    
    public function getLieu(): ?Lieu
    {
        return $this->lieu;
    }

    public function setLieu(?Lieu $lieu): self
    {
        $this->lieu = $lieu;

        return $this;
    }

    public function getEtat(): ?Etat
    {
        return $this->etat;
    }

    public function setEtat(?Etat $etat): self
    {
        $this->etat = $etat;

        return $this;
    }

    public function getCampus(): ?Site
    {
        return $this->campus;
    }

    public function setCampus(?Site $campus): self
    {
        $this->campus = $campus;

        return $this;
    }

    public function getOrganisateur(): ?User
    {
        return $this->organisateur;
    }

    public function setOrganisateur(?User $organisateur): self
    {
        $this->organisateur = $organisateur;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getParticipants(): Collection
    {
        return $this->participants;
    }

    public function addParticipant(User $participant): self
    {
        if (!$this->participants->contains($participant)) {
            $this->participants[] = $participant;
        }

        return $this;
    }

    public function removeParticipant(User $participant): self
    {
        $this->participants->removeElement($participant);

        return $this;
    }
}
