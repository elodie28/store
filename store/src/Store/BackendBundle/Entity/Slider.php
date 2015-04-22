<?php

namespace Store\BackendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert; // user les contraintes pour pouvoir les utiliser dans les entités (création formulaire)
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity; // pour que le champ soit unique dans le formulaire, à lier avec @UniqueEntity dans la class Product
use Store\BackendBundle\Validator\Constraints as StoreAssert;


/**
 * Slider
 *
 * @ORM\Table(name="slider", indexes={@ORM\Index(name="product_id", columns={"product_id"})})
 * @ORM\Entity(repositoryClass="Store\BackendBundle\Repository\SliderRepository")
 * @UniqueEntity(fields="caption", message="Votre légende de slide est déjà utilisée", groups = {"new"})
 */
class Slider
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @Assert\NotBlank(
     *     message = "La légende de votre slide ne doit pas être vide",
     *     groups = {"new", "edit"}
     * )
     *
     * @Assert\Length(
     *     min = "4",
     *     max = "150",
     *     minMessage = "Votre légende doit faire au moins {{ limit }} caractères",
     *     maxMessage = "Votre légende ne peut pas être plus longue que {{ limit }} caractères",
     *     groups = {"new", "edit"}
     * )
     *
     * @ORM\Column(name="caption", type="text", nullable=true)
     */
    private $caption;

    /**
     * @var string
     *
     * @ORM\Column(name="image", type="string", length=300, nullable=true)
     */
    private $image;

    /**
     * Attribut qui représentera mon fichier uploadé
     * @Assert\Image(
     *     minWidth = 50,
     *     maxWidth = 3000,
     *     minHeight = 50,
     *     maxHeight = 2500,
     *     minWidthMessage = "La largeur de l'image est trop petite",
     *     maxWidthMessage = "La largeur de l'image est trop grande",
     *     minHeightMessage = "La hauteur de l'image est trop petite",
     *     maxHeightMessage = "La hauteur de l'image est trop grande",
     *     groups = {"new", "edit"}
     * )
     */
    protected $file;

    /**
     * @var integer
     *
     * @ORM\Column(name="position", type="integer", nullable=true)
     */
    private $position;

    /**
     * @var boolean
     *
     * @ORM\Column(name="active", type="boolean", nullable=true)
     */
    private $active;

    /**
     * @var \Product
     *
     * @ORM\ManyToOne(targetEntity="Product")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="product_id", referencedColumnName="id")
     * })
     */
    private $product;



    /**
     * Constructeur qui initialise les propriétés de mon objet Slider
     */
    public function __construct() {


        // Initialise les propriétés de mon objet Slider (formulaire de création d'un nouveau slide)
        // La case active dans le formulaire est cochée car le formulaire est lié à l'entité
        $this->active = true;
    }



    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set caption
     *
     * @param string $caption
     * @return Slider
     */
    public function setCaption($caption)
    {
        $this->caption = $caption;

        return $this;
    }

    /**
     * Get caption
     *
     * @return string 
     */
    public function getCaption()
    {
        return $this->caption;
    }

    /**
     * Set image
     *
     * @param string $image
     * @return Slider
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return string 
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set position
     *
     * @param integer $position
     * @return Slider
     */
    public function setPosition($position)
    {
        $this->position = $position;

        return $this;
    }

    /**
     * Get position
     *
     * @return integer 
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * Set active
     *
     * @param boolean $active
     * @return Slider
     */
    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * Get active
     *
     * @return boolean 
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * Set product
     *
     * @param \Store\BackendBundle\Entity\Product $product
     * @return Slider
     */
    public function setProduct(\Store\BackendBundle\Entity\Product $product = null)
    {
        $this->product = $product;

        return $this;
    }

    /**
     * Get product
     *
     * @return \Store\BackendBundle\Entity\Product 
     */
    public function getProduct()
    {
        return $this->product;
    }



    /**
     * Retourne le chemin absolu de mon image
     * @return null|string
     */
    public function getAbsolutePath()
    {
        return null === $this->image ? null : $this->getUploadRootDir().'/'.$this->image;
    }

    /**
     * Retourne le chemin de l'image depuis le dossier web
     * @return null|string
     */
    public function getWebPath()
    {
        return null === $this->image ? null : $this->getUploadDir().'/'.$this->image;
    }

    /**
     * Retourne le chemin de l'image depuis l'entité
     * @return string
     */
    protected function getUploadRootDir()
    {
        // le chemin absolu du répertoire où les documents uploadés doivent être sauvegardés
        return __DIR__.'/../../../../web/'.$this->getUploadDir();
    }

    /**
     * Retourne le dossier d'upload et le sous-dossier slider
     * @return string
     */
    protected function getUploadDir()
    {
        // on se débarrasse de « __DIR__ » afin de ne pas avoir de problème lorsqu'on affiche
        // le document/image dans la vue.
        return 'uploads/slider';
    }


    public function upload()
    {
        // la propriété « file » peut être vide si le champ n'est pas requis
        if (null === $this->file) {
            return;
        }

        // utilisez le nom de fichier original ici mais
        // vous devriez « l'assainir » pour au moins éviter
        // quelconques problèmes de sécurité

        // On déplace le fichier uploadé dans le bon répertoire uploads/slider
        $this->file->move($this->getUploadRootDir(), $this->file->getClientOriginalName());

        // Je stocke le nom du fichier uploadé dans mon attribut image
        $this->image = $this->file->getClientOriginalName();

        // « nettoie » la propriété « file » comme vous n'en aurez plus besoin
        $this->file = null;
    }



    /**
     * @param mixed $file
     */
    public function setFile($file)
    {
        $this->file = $file;
    }

    /**
     * @return mixed
     */
    public function getFile()
    {
        return $this->file;
    }



    /**
     * Retourne la caption
     * @return string
     */
    public function __toString() {
        return $this->caption;
    }

}
