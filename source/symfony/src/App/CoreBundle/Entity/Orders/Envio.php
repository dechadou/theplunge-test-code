<?php

namespace App\CoreBundle\Entity\Orders;

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
 * @ORM\Table(name="ec_envios")
 */
class Envio
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
     * @ORM\Column(type="string", unique=false, nullable=true)
     */
    protected $name;

    /**
     * @ORM\Column(type="text", unique=false, nullable=true)
     */
    protected $description;

    /**
     * @ORM\Column(type="decimal", scale=2,nullable=true)
     */
    protected $price;

    /**
     * @ORM\OneToMany(targetEntity="App\CoreBundle\Entity\Orders\Order", mappedBy="envio", cascade={"persist"}, orphanRemoval=true)
     */
    protected $order;

    /**
     * @ORM\OneToMany(targetEntity="App\CoreBundle\Entity\EmailTriggers", mappedBy="envio", cascade={"persist"}, orphanRemoval=true)
     */
    protected $email_trigger;

    /**
     * @ORM\ManyToOne(targetEntity="App\CoreBundle\Entity\Tienda", cascade={"persist"}, fetch="LAZY")
     */
    protected $tienda;

    /**
     * @Gedmo\Slug(fields={"id","name"},updatable=true)
     * @ORM\Column(unique=true)
     */
    private $slug;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $full_data;

    public function toArray()
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'description' => $this->getDescription(),
            'price' => $this->getPrice(),
            'pedir_envio' => ($this->getFullData()) ? true : false
        ];
    }


    public function __construct()
    {
        $this->email_trigger = new ArrayCollection();
        $this->order = new ArrayCollection();
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
     * @return Envio
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
     * @return string
     */
    public function __toString()
    {
        return (string) $this->getName();
    }

    /**
     * Add emailTrigger
     *
     * @param \App\CoreBundle\Entity\EmailTriggers $emailTrigger
     *
     * @return Envio
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
     * Add order
     *
     * @param \App\CoreBundle\Entity\Orders\Order $order
     *
     * @return Envio
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
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param mixed $slug
     * @return Envio
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     * @return Envio
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param mixed $price
     * @return Envio
     */
    public function setPrice($price)
    {
        $this->price = $price;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFullData()
    {
        return $this->full_data;
    }

    /**
     * @param mixed $full_data
     * @return Envio
     */
    public function setFullData($full_data)
    {
        $this->full_data = $full_data;
        return $this;
    }

    /**
     * Set tienda
     *
     * @param \App\CoreBundle\Entity\Tienda $tienda
     *
     * @return Envio
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
