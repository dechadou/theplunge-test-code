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
 * @ORM\Entity(repositoryClass="App\CoreBundle\Repository\TiendaRepository")
 * @ORM\Table(name="ec_tiendas")
 */
class Tienda
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
     * @ORM\Column(type="string", length=191,unique=true, nullable=false)
     */
    protected $name;

    /**
     * @ORM\Column(type="string", length=191,unique=true, nullable=false, length=128)
     */
    protected $app_id;

    /**
     * @ORM\Column(type="string", length=191,unique=true, nullable=false, length=128)
     */
    protected $app_secret;

    /**
     * @ORM\Column(type="string", unique=false, nullable=true)
     */
    protected $mercadopago_key;

    /**
     * @ORM\Column(type="string", unique=false, nullable=true)
     */
    protected $mercadopago_secret;

    /**
     * @ORM\Column(type="string", unique=false, nullable=true)
     */
    protected $hostname;

    /**
     * @Gedmo\Slug(fields={"id","name"},updatable=true)
     * @ORM\Column(unique=true)
     */
    protected $slug;

    /**
     * @ORM\OneToMany(targetEntity="App\CoreBundle\Entity\Combo", mappedBy="tienda", cascade={"all"}, orphanRemoval=true)
     */
    protected $combo;

    /**
     * @ORM\OneToMany(targetEntity="App\CoreBundle\Entity\Categoria", mappedBy="tienda", cascade={"all"}, orphanRemoval=true)
     */
    protected $categoria;

    /**
     * @ORM\ManyToMany(targetEntity="App\CoreBundle\Entity\Producto", mappedBy="tienda")
     */
    protected $producto;

    /**
     * @ORM\OneToMany(targetEntity="App\CoreBundle\Entity\Orders\Order", mappedBy="tienda", cascade={"all"}, orphanRemoval=true)
     */
    protected $order;

    /**
     * @ORM\ManyToOne(targetEntity="Media", cascade={"persist"}, fetch="LAZY")
     */
    protected $image;

    /**
     * @ORM\OneToOne(targetEntity="App\CoreBundle\Entity\User", mappedBy="tienda", cascade={"all"}, orphanRemoval=true)
     */
    protected $user;

    /**
     * @ORM\OneToMany(targetEntity="App\CoreBundle\Entity\ComboProducto", mappedBy="tienda", cascade={"persist"}, orphanRemoval=true)
     */
    protected $combo_producto;

    /**
     * @ORM\OneToMany(targetEntity="App\CoreBundle\Entity\EmailTriggers", mappedBy="tienda", cascade={"persist"}, orphanRemoval=true)
     */
    protected $email_trigger;


    /**
     * @ORM\OneToMany(targetEntity="App\CoreBundle\Entity\Tienda", mappedBy="tienda", cascade={"persist"}, orphanRemoval=true)
     */
    protected $envio;

    /**
     * @ORM\Column(type="string", unique=true, nullable=false)
     */
    protected $order_prefix;


    /**
     * Tienda constructor.
     */
    public function __construct()
    {
        $this->producto = new ArrayCollection();
        $this->combo = new ArrayCollection();
        $this->categoria = new ArrayCollection();
        $this->order = new ArrayCollection();
        $this->combo_producto = new ArrayCollection();
        $this->email_trigger = new ArrayCollection();
        $this->envio = new ArrayCollection();
    }

    public function __toString()
    {
        return (string) $this->getName();
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
     * @return Tienda
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
     * Set appId
     *
     * @param string $appId
     *
     * @return Tienda
     */
    public function setAppId($appId)
    {
        $this->app_id = $appId;

        return $this;
    }

    /**
     * Get appId
     *
     * @return string
     */
    public function getAppId()
    {
        return $this->app_id;
    }

    /**
     * Set appSecret
     *
     * @param string $appSecret
     *
     * @return Tienda
     */
    public function setAppSecret($appSecret)
    {
        $this->app_secret = $appSecret;

        return $this;
    }

    /**
     * Get appSecret
     *
     * @return string
     */
    public function getAppSecret()
    {
        return $this->app_secret;
    }

    /**
     * Set slug
     *
     * @param string $slug
     *
     * @return Tienda
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
     * Add combo
     *
     * @param \App\CoreBundle\Entity\Combo $combo
     *
     * @return Tienda
     */
    public function addCombo(\App\CoreBundle\Entity\Combo $combo)
    {
        $this->combo[] = $combo;

        return $this;
    }

    /**
     * Remove combo
     *
     * @param \App\CoreBundle\Entity\Combo $combo
     */
    public function removeCombo(\App\CoreBundle\Entity\Combo $combo)
    {
        $this->combo->removeElement($combo);
    }

    /**
     * Get combo
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCombo()
    {
        return $this->combo;
    }

    /**
     * Add categorium
     *
     * @param \App\CoreBundle\Entity\Categoria $categorium
     *
     * @return Tienda
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
     * Add producto
     *
     * @param \App\CoreBundle\Entity\Producto $producto
     *
     * @return Tienda
     */
    public function addProducto(\App\CoreBundle\Entity\Producto $producto)
    {
        $this->producto[] = $producto;

        return $this;
    }

    /**
     * Remove producto
     *
     * @param \App\CoreBundle\Entity\Producto $producto
     */
    public function removeProducto(\App\CoreBundle\Entity\Producto $producto)
    {
        $this->producto->removeElement($producto);
    }

    /**
     * Get producto
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProducto()
    {
        return $this->producto;
    }

    /**
     * Add order
     *
     * @param \App\CoreBundle\Entity\Orders\Order $order
     *
     * @return Tienda
     */
    public function addOrder(\App\CoreBundle\Entity\Orders\Order $order)
    {
        $this->order[] = $order;

        return $this;
    }

    /**
     * Remove order
     *
     * @param \App\CoreBundle\Entity\Orders\Order $order
     */
    public function removeOrder(\App\CoreBundle\Entity\Orders\Order $order)
    {
        $this->order->removeElement($order);
    }

    /**
     * Get order
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * @return mixed
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param mixed $image
     * @return Tienda
     */
    public function setImage(\App\CoreBundle\Entity\Media $image = null)
    {
        $this->image = $image;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     * @return Tienda
     */
    public function setUser($user)
    {
        $this->user = $user;
        return $this;
    }




    /**
     * Add comboProducto
     *
     * @param \App\CoreBundle\Entity\ComboProducto $comboProducto
     *
     * @return Tienda
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
     * Add emailTrigger
     *
     * @param \App\CoreBundle\Entity\EmailTriggers $emailTrigger
     *
     * @return Tienda
     */
    public function addEmailTrigger(\App\CoreBundle\Entity\EmailTriggers $emailTrigger)
    {
        $this->email_trigger[] = $emailTrigger;

        return $this;
    }

    /**
     * Remove emailTrigger
     *
     * @param \App\CoreBundle\Entity\EmailTriggers $emailTrigger
     */
    public function removeEmailTrigger(\App\CoreBundle\Entity\EmailTriggers $emailTrigger)
    {
        $this->email_trigger->removeElement($emailTrigger);
    }

    /**
     * Get emailTrigger
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEmailTrigger()
    {
        return $this->email_trigger;
    }

    /**
     * Set mercadopagoKey
     *
     * @param string $mercadopagoKey
     *
     * @return Tienda
     */
    public function setMercadopagoKey($mercadopagoKey)
    {
        $this->mercadopago_key = $mercadopagoKey;

        return $this;
    }

    /**
     * Get mercadopagoKey
     *
     * @return string
     */
    public function getMercadopagoKey()
    {
        return $this->mercadopago_key;
    }

    /**
     * Set mercadopagoSecret
     *
     * @param string $mercadopagoSecret
     *
     * @return Tienda
     */
    public function setMercadopagoSecret($mercadopagoSecret)
    {
        $this->mercadopago_secret = $mercadopagoSecret;

        return $this;
    }

    /**
     * Get mercadopagoSecret
     *
     * @return string
     */
    public function getMercadopagoSecret()
    {
        return $this->mercadopago_secret;
    }

    /**
     * @return mixed
     */
    public function getOrderPrefix()
    {
        return $this->order_prefix;
    }

    /**
     * @param mixed $order_prefix
     * @return Tienda
     */
    public function setOrderPrefix($order_prefix)
    {
        $this->order_prefix = $order_prefix;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getHostname()
    {
        return $this->hostname;
    }

    /**
     * @param mixed $hostname
     * @return Tienda
     */
    public function setHostname($hostname)
    {
        $this->hostname = $hostname;
        return $this;
    }


    /**
     * Add envio
     *
     * @param \App\CoreBundle\Entity\Tienda $envio
     *
     * @return Tienda
     */
    public function addEnvio(\App\CoreBundle\Entity\Tienda $envio)
    {
        $this->envio[] = $envio;

        return $this;
    }

    /**
     * Remove envio
     *
     * @param \App\CoreBundle\Entity\Tienda $envio
     */
    public function removeEnvio(\App\CoreBundle\Entity\Tienda $envio)
    {
        $this->envio->removeElement($envio);
    }

    /**
     * Get envio
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEnvio()
    {
        return $this->envio;
    }
}
