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
 * @ORM\Entity(repositoryClass="App\CoreBundle\Repository\ArticuloRepository")
 * @ORM\Table(name="ec_articulos")
 */
class Articulo
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
     * @ORM\Column(type="string", length=191, unique=false, nullable=false)
     */
    protected $name;

    /**
     * @ORM\Column(type="decimal", scale=2, nullable=true)
     */
    protected $price;

    /**
     * @ORM\Column(type="text", unique=false, nullable=false)
     */
    protected $description;

    /**
     * @ORM\ManyToOne(targetEntity="App\CoreBundle\Entity\Producto", inversedBy="articulo", cascade={"persist"})
     * @ORM\JoinColumn(name="producto_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $producto;

    /**
     * @ORM\ManyToMany(targetEntity="App\CoreBundle\Entity\ValorAtributo", inversedBy="articulo")
     * @ORM\JoinTable(name="ec_articulos_atributos")
     */
    protected $valor_atributo;

    /**
     * @ORM\Column(type="integer", unique=false, nullable=true)
     */
    protected $stock;

    /**
     * @ORM\Column(type="integer", unique=false, nullable=true)
     */
    protected $stock_minimo;

    /**
     * @ORM\OneToMany(targetEntity="App\CoreBundle\Entity\Orders\CompraArticulo", mappedBy="articulo", cascade={"persist"}, orphanRemoval=true)
     */
    protected $compra_articulo;

    /**
     * @ORM\Column(type="string", length=191, unique=true, nullable=false)
     */
    protected $hash;

    /**
     * @ORM\Column(type="integer", unique=false, nullable=true)
     */
    protected $totalSold;


    public function __construct()
    {
        $this->compra_articulo = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function __toString()
    {
        return ($this->getHash() != '') ? $this->getHash() : '';
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
     * @return Articulo
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
     * @return Articulo
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
     * @return Articulo
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
     * Set stock
     *
     * @param integer $stock
     *
     * @return Articulo
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
     * @return Articulo
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
     * Set producto
     *
     * @param \App\CoreBundle\Entity\Producto $producto
     *
     * @return Articulo
     */
    public function setProducto(\App\CoreBundle\Entity\Producto $producto = null)
    {
        $this->producto = $producto;

        return $this;
    }

    /**
     * Get producto
     *
     * @return \App\CoreBundle\Entity\Producto
     */
    public function getProducto()
    {
        return $this->producto;
    }

    /**
     * Add compraArticulo
     *
     * @param \App\CoreBundle\Entity\Orders\CompraArticulo $compraArticulo
     *
     * @return Articulo
     */
    public function addCompraArticulo(\App\CoreBundle\Entity\Orders\CompraArticulo $compraArticulo)
    {
        $this->compra_articulo[] = $compraArticulo;

        return $this;
    }

    /**
     * Remove compraArticulo
     *
     * @param \App\CoreBundle\Entity\Orders\CompraArticulo $compraArticulo
     */
    public function removeCompraArticulo(\App\CoreBundle\Entity\Orders\CompraArticulo $compraArticulo)
    {
        $this->compra_articulo->removeElement($compraArticulo);
    }

    /**
     * Get compraArticulo
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCompraArticulo()
    {
        return $this->compra_articulo;
    }

    /**
     * Add valorAtributo
     *
     * @param \App\CoreBundle\Entity\ValorAtributo $valorAtributo
     *
     * @return Articulo
     */
    public function addValorAtributo(\App\CoreBundle\Entity\ValorAtributo $valorAtributo)
    {
        $this->valor_atributo[] = $valorAtributo;

        return $this;
    }

    /**
     * Remove valorAtributo
     *
     * @param \App\CoreBundle\Entity\ValorAtributo $valorAtributo
     */
    public function removeValorAtributo(\App\CoreBundle\Entity\ValorAtributo $valorAtributo)
    {
        $this->valor_atributo->removeElement($valorAtributo);
    }

    /**
     * Get valorAtributo
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getValorAtributo()
    {
        return $this->valor_atributo;
    }

    /**
     * Set hash
     *
     * @param string $hash
     *
     * @return Articulo
     */
    public function setHash($hash)
    {
        $this->hash = $hash;

        return $this;
    }

    /**
     * Get hash
     *
     * @return string
     */
    public function getHash()
    {
        return $this->hash;
    }

    /**
     * Set slug
     *
     * @param string $slug
     *
     * @return Articulo
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
     * Set totalSold
     *
     * @param integer $totalSold
     *
     * @return Articulo
     */
    public function setTotalSold($totalSold)
    {
        $this->totalSold = $totalSold;

        return $this;
    }

    /**
     * Get totalSold
     *
     * @return integer
     */
    public function getTotalSold()
    {
        return $this->totalSold;
    }

    public function toArray()
    {
        return [
            'id' => $this->getId(),
            'tienda' => $this->getProducto()->getTienda()->first()->getId(),
            'parent_product' => $this->getProducto()->getId(),
            'name' => $this->getName(),
            'price' => $this->getPrice(),
            'description' => $this->getDescription(),
            'stock' => $this->getStock(),
            'totalSold' => $this->getTotalSold(),
            'slug' => $this->getSlug()
        ];
    }
}
