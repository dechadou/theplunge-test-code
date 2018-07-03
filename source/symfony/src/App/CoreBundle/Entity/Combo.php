<?php

namespace App\CoreBundle\Entity;

use App\CoreBundle\Entity\Common\CreatedByEntity;
use App\CoreBundle\Entity\Common\PublishableEntity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use MediaMonks\Doctrine\Mapping\Annotation as MediaMonks;

/**
 * @ORM\Entity
 * @ORM\Table(name="ec_combos")
 */
class Combo
{
    use TimestampableEntity;
    use PublishableEntity;
    use CreatedByEntity;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @Gedmo\Slug(fields={"id","name"},updatable=true)
     * @ORM\Column(unique=true)
     */
    private $slug;

    /**
     * @ORM\Column(type="string", length=191,unique=true, nullable=false)
     */
    protected $name;

    /**
     * @ORM\Column(type="decimal", scale=2, nullable=true)
     */
    protected $price;

    /**
     * @ORM\ManyToOne(targetEntity="App\CoreBundle\Entity\Tienda", inversedBy="combo")
     */
    protected $tienda;

    /**
     * @ORM\Column(type="text", unique=false, nullable=true)
     */
    protected $description;

    /**
     * @ORM\ManyToMany(targetEntity="App\CoreBundle\Entity\Categoria")
     * @ORM\JoinTable(name="ec_categorias_combos")
     */
    protected $categoria;

    /**
     * @ORM\OneToMany(targetEntity="App\CoreBundle\Entity\Orders\CompraCombo", mappedBy="combo", cascade={"persist"}, orphanRemoval=true)
     */
    protected $compra_combo;

    /**
     * @ORM\OneToMany(targetEntity="App\CoreBundle\Entity\ComboMedia", mappedBy="combo", cascade={"persist"}, orphanRemoval=true)
     */
    protected $media;

    /**
     * @ORM\OneToMany(targetEntity="App\CoreBundle\Entity\ComboProducto", mappedBy="combo", cascade={"persist"}, orphanRemoval=true)
     */
    protected $combo_producto;

    public function __toString()
    {
        return (string) $this->getName();
    }

    /**
     * Combo constructor.
     */
    public function __construct()
    {
        $this->categoria = new ArrayCollection();
        $this->media = new ArrayCollection();
        $this->compra_combo = new ArrayCollection();
        $this->combo_producto = new ArrayCollection();
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
     * Set slug
     *
     * @param string $slug
     *
     * @return Combo
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Combo
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set price
     *
     * @param string $price
     *
     * @return Combo
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return string
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Combo
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set tienda
     *
     * @param \App\CoreBundle\Entity\Tienda $tienda
     *
     * @return Combo
     */
    public function setTienda(\App\CoreBundle\Entity\Tienda $tienda = null)
    {
        $this->tienda = $tienda;

        return $this;
    }

    /**
     * Get tienda
     *
     * @return \App\CoreBundle\Entity\Tienda
     */
    public function getTienda()
    {
        return $this->tienda;
    }

    /**
     * Add categorium
     *
     * @param \App\CoreBundle\Entity\Categoria $categorium
     *
     * @return Combo
     */
    public function addCategorium(\App\CoreBundle\Entity\Categoria $categorium)
    {
        $this->categoria[] = $categorium;

        return $this;
    }

    /**
     * Remove categorium
     *
     * @param \App\CoreBundle\Entity\Categoria $categorium
     */
    public function removeCategorium(\App\CoreBundle\Entity\Categoria $categorium)
    {
        $this->categoria->removeElement($categorium);
    }

    /**
     * Get categoria
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCategoria()
    {
        return $this->categoria;
    }

    /**
     * Add compraCombo
     *
     * @param \App\CoreBundle\Entity\Orders\CompraCombo $compraCombo
     *
     * @return Combo
     */
    public function addCompraCombo(\App\CoreBundle\Entity\Orders\CompraCombo $compraCombo)
    {
        $this->compra_combo[] = $compraCombo;

        return $this;
    }

    /**
     * Remove compraCombo
     *
     * @param \App\CoreBundle\Entity\Orders\CompraCombo $compraCombo
     */
    public function removeCompraCombo(\App\CoreBundle\Entity\Orders\CompraCombo $compraCombo)
    {
        $this->compra_combo->removeElement($compraCombo);
    }

    /**
     * Get compraCombo
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCompraCombo()
    {
        return $this->compra_combo;
    }

    /**
     * Add medium
     *
     * @param \App\CoreBundle\Entity\ComboMedia $medium
     *
     * @return Combo
     */
    public function addMedia(\App\CoreBundle\Entity\ComboMedia $medium)
    {
        $this->media[] = $medium;

        return $this;
    }

    /**
     * Remove medium
     *
     * @param \App\CoreBundle\Entity\ComboMedia $medium
     */
    public function removeMedia(\App\CoreBundle\Entity\ComboMedia $medium)
    {
        $this->media->removeElement($medium);
    }

    /**
     * Get media
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMedia()
    {
        return $this->media;
    }

    /**
     * Add comboProducto
     *
     * @param \App\CoreBundle\Entity\ComboProducto $comboProducto
     *
     * @return Combo
     */
    public function addComboProducto(\App\CoreBundle\Entity\ComboProducto $comboProducto)
    {
        $this->combo_producto[] = $comboProducto;

        return $this;
    }

    /**
     * Remove comboProducto
     *
     * @param \App\CoreBundle\Entity\ComboProducto $comboProducto
     */
    public function removeComboProducto(\App\CoreBundle\Entity\ComboProducto $comboProducto)
    {
        $this->combo_producto->removeElement($comboProducto);
    }

    /**
     * Get comboProducto
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getComboProducto()
    {
        return $this->combo_producto;
    }
}
