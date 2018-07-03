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
 * @ORM\Table(name="ec_combos_productos")
 */
class ComboProducto
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
     * @ORM\ManyToOne(targetEntity="App\CoreBundle\Entity\Producto", inversedBy="combo_producto", cascade={"persist"})
     * @ORM\JoinColumn(name="producto_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $producto;

    /**
     * @ORM\ManyToOne(targetEntity="App\CoreBundle\Entity\Combo", inversedBy="combo_producto", cascade={"persist"})
     * @ORM\JoinColumn(name="combo_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $combo;

    /**
     * @ORM\Column(type="integer", unique=false, nullable=false)
     */
    protected $amount;

    /**
     * @ORM\ManyToOne(targetEntity="App\CoreBundle\Entity\Tienda", inversedBy="combo_producto", cascade={"persist"})
     * @ORM\JoinColumn(name="tienda_id", referencedColumnName="id", onDelete="CASCADE", nullable=true)
     */
    protected $tienda;


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
     * Set amount
     *
     * @param integer $amount
     *
     * @return ComboProducto
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * Get amount
     *
     * @return integer
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Set producto
     *
     * @param \App\CoreBundle\Entity\Producto $producto
     *
     * @return ComboProducto
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
     * Set combo
     *
     * @param \App\CoreBundle\Entity\Combo $combo
     *
     * @return ComboProducto
     */
    public function setCombo(\App\CoreBundle\Entity\Combo $combo = null)
    {
        $this->combo = $combo;

        return $this;
    }

    /**
     * Get combo
     *
     * @return \App\CoreBundle\Entity\Combo
     */
    public function getCombo()
    {
        return $this->combo;
    }

    /**
     * Set tienda
     *
     * @param \App\CoreBundle\Entity\Tienda $tienda
     *
     * @return ComboProducto
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
}
