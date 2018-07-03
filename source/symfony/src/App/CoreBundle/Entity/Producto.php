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
 * @ORM\Table(name="ec_productos")
 */
class Producto
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
     * @ORM\Column(type="string", length=191, unique=false, nullable=false)
     */
    protected $name;

    /**
     * @ORM\Column(type="text", unique=false, nullable=true)
     */
    protected $description;

    /**
     * @Gedmo\Slug(fields={"id","name"},updatable=true)
     * @ORM\Column(unique=true)
     */
    private $slug;

    /**
     * @ORM\Column(type="decimal", scale=2, nullable=true)
     */
    protected $price;

    /**
     * @ORM\ManyToOne(targetEntity="App\CoreBundle\Entity\TipoProducto", inversedBy="producto", cascade={"persist"})
     * @ORM\JoinColumn(name="tipo_producto_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $tipo_producto;

    /**
     * @ORM\ManyToMany(targetEntity="App\CoreBundle\Entity\Tienda", inversedBy="producto")
     * @ORM\JoinTable(name="ec_tiendas_productos")
     */
    protected $tienda;

    /**
     * @ORM\OneToMany(targetEntity="App\CoreBundle\Entity\Articulo", mappedBy="producto", cascade={"persist"}, orphanRemoval=true)
     */
    protected $articulo;

    /**
     * @ORM\OneToMany(targetEntity="App\CoreBundle\Entity\Atributo", mappedBy="producto", cascade={"persist"}, orphanRemoval=true)
     */
    protected $atributo;

    /**
     * @ORM\OneToMany(targetEntity="App\CoreBundle\Entity\ProductoMedia", mappedBy="producto", cascade={"persist"}, orphanRemoval=true)
     */
    protected $media;

    /**
     * @ORM\OneToMany(targetEntity="App\CoreBundle\Entity\ComboProducto", mappedBy="producto", cascade={"persist"}, orphanRemoval=true)
     */
    protected $combo_producto;

    /**
     * @ORM\Column(type="integer", unique=false, nullable=true)
     */
    protected $stock;

    /**
     * @ORM\Column(type="integer", unique=false, nullable=true)
     */
    protected $stock_minimo;

    /**
     * @ORM\ManyToMany(targetEntity="App\CoreBundle\Entity\Categoria", inversedBy="producto" ,cascade={"persist"})
     * @ORM\JoinTable(name="ec_categorias_productos")
     */
    protected $categoria;

    /**
     * @ORM\Column(type="boolean", unique=false, nullable=true)
     */
    protected $in_production;


    /**
     * Producto constructor.
     */
    public function __construct()
    {
        $this->tienda = new ArrayCollection();
        $this->atributo = new ArrayCollection();
        $this->articulo = new ArrayCollection();
        $this->media = new ArrayCollection();
        $this->combo_producto = new ArrayCollection();
        $this->categoria = new ArrayCollection();
    }

    public function toArray()
    {
        $articulos = [];
        if ($this->getArticulo()) {
            foreach ($this->getArticulo() as $ar) {
                $articulos[] = $ar->toArray();
            }
        }

        $tipoProducto = null;
        if ($this->getTipoProducto()) {
            $tipoProducto = $this->getTipoProducto()->toArray();
        }


        return [
            'id' => $this->getId(),
            'tipo_producto_id' => $tipoProducto,
            'name' => $this->getName(),
            'description' => $this->getDescription(),
            'slug' => $this->getSlug(),
            'price' => $this->getPrice(),
            'stock' => $this->getStock(),
            'stock_minimo' => $this->getStockMinimo(),
            'articulos' => $articulos,
        ];
    }

    public function __toString()
    {
        return (string)$this->getName();
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
     * Set name
     *
     * @param string $name
     *
     * @return Producto
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
     * Set description
     *
     * @param string $description
     *
     * @return Producto
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
     * Set slug
     *
     * @param string $slug
     *
     * @return Producto
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
     * Set price
     *
     * @param string $price
     *
     * @return Producto
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
     * Set tipoProducto
     *
     * @param \App\CoreBundle\Entity\TipoProducto $tipoProducto
     *
     * @return Producto
     */
    public function setTipoProducto(\App\CoreBundle\Entity\TipoProducto $tipoProducto = null)
    {
        $this->tipo_producto = $tipoProducto;

        return $this;
    }

    /**
     * Get tipoProducto
     *
     * @return \App\CoreBundle\Entity\TipoProducto
     */
    public function getTipoProducto()
    {
        return $this->tipo_producto;
    }

    /**
     * Add tienda
     *
     * @param \App\CoreBundle\Entity\Tienda $tienda
     *
     * @return Producto
     */
    public function addTienda(\App\CoreBundle\Entity\Tienda $tienda)
    {
        $this->tienda[] = $tienda;

        return $this;
    }

    /**
     * Remove tienda
     *
     * @param \App\CoreBundle\Entity\Tienda $tienda
     */
    public function removeTienda(\App\CoreBundle\Entity\Tienda $tienda)
    {
        $this->tienda->removeElement($tienda);
    }

    /**
     * Get tienda
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTienda()
    {
        return $this->tienda;
    }

    /**
     * Add articulo
     *
     * @param \App\CoreBundle\Entity\Articulo $articulo
     *
     * @return Producto
     */
    public function addArticulo(\App\CoreBundle\Entity\Articulo $articulo)
    {
        $this->articulo[] = $articulo;

        return $this;
    }

    /**
     * Remove articulo
     *
     * @param \App\CoreBundle\Entity\Articulo $articulo
     */
    public function removeArticulo(\App\CoreBundle\Entity\Articulo $articulo)
    {
        $this->articulo->removeElement($articulo);
    }

    /**
     * Get articulo
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getArticulo()
    {
        return $this->articulo;
    }


    /**
     * Add medium
     *
     * @param \App\CoreBundle\Entity\ProductoMedia $medium
     *
     * @return Producto
     */
    public function addMedia(\App\CoreBundle\Entity\ProductoMedia $medium)
    {
        $this->media[] = $medium;

        return $this;
    }

    /**
     * Remove medium
     *
     * @param \App\CoreBundle\Entity\ProductoMedia $medium
     */
    public function removeMedia(\App\CoreBundle\Entity\ProductoMedia $medium)
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
     * @return Producto
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

    /**
     * Set stock
     *
     * @param integer $stock
     *
     * @return Producto
     */
    public function setStock($stock)
    {
        $this->stock = $stock;

        return $this;
    }

    /**
     * Get stock
     *
     * @return integer
     */
    public function getStock()
    {
        return $this->stock;
    }

    /**
     * Set stockMinimo
     *
     * @param integer $stockMinimo
     *
     * @return Producto
     */
    public function setStockMinimo($stockMinimo)
    {
        $this->stock_minimo = $stockMinimo;

        return $this;
    }

    /**
     * Get stockMinimo
     *
     * @return integer
     */
    public function getStockMinimo()
    {
        return $this->stock_minimo;
    }

    /**
     * Add categorium
     *
     * @param \App\CoreBundle\Entity\Categoria $categorium
     *
     * @return Producto
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

    public function getPrimaryImage()
    {

    }

    /**
     * Add atributo
     *
     * @param \App\CoreBundle\Entity\Atributo $atributo
     *
     * @return Producto
     */
    public function addAtributo(\App\CoreBundle\Entity\Atributo $atributo)
    {
        $this->atributo[] = $atributo;

        return $this;
    }

    /**
     * Remove atributo
     *
     * @param \App\CoreBundle\Entity\Atributo $atributo
     */
    public function removeAtributo(\App\CoreBundle\Entity\Atributo $atributo)
    {
        $this->atributo->removeElement($atributo);
    }

    /**
     * Get atributo
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAtributo()
    {
        return $this->atributo;
    }

    /**
     * @return mixed
     */
    public function getInProduction()
    {
        return $this->in_production;
    }

    /**
     * @param mixed $in_production
     * @return Producto
     */
    public function setInProduction($in_production)
    {
        $this->in_production = $in_production;
        return $this;
    }


}
