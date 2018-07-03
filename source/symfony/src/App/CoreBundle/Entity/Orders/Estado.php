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
 * @ORM\Table(name="ec_compras_estados")
 */
class Estado
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
     * @ORM\Column(type="string", unique=false, nullable=false)
     */
    protected $name;

    /**
     * @ORM\OneToMany(targetEntity="App\CoreBundle\Entity\Orders\Nota", mappedBy="estado", cascade={"persist"}, orphanRemoval=true)
     */
    protected $nota;

    /**
     * @ORM\OneToMany(targetEntity="App\CoreBundle\Entity\Orders\Order", mappedBy="estado", cascade={"persist"}, orphanRemoval=true)
     */
    protected $order;

    /**
     * @ORM\OneToMany(targetEntity="App\CoreBundle\Entity\EmailTriggers", mappedBy="estado", cascade={"persist"}, orphanRemoval=true)
     */
    protected $email_trigger;

    /**
     * @Gedmo\Slug(fields={"id","name"},updatable=true)
     * @ORM\Column(unique=true)
     */
    private $slug;


    public function __construct()
    {
        $this->nota = new ArrayCollection();
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
     * @return Estado
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
     * Add notum
     *
     * @param \App\CoreBundle\Entity\Orders\Nota $notum
     *
     * @return Estado
     */
    public function addNotum(\App\CoreBundle\Entity\Orders\Nota $notum)
    {
        $this->nota[] = $notum;

        return $this;
    }

    /**
     * Remove notum
     *
     * @param \App\CoreBundle\Entity\Orders\Nota $notum
     */
    public function removeNotum(\App\CoreBundle\Entity\Orders\Nota $notum)
    {
        $this->nota->removeElement($notum);
    }

    /**
     * Get nota
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getNota()
    {
        return $this->nota;
    }

    /**
     * Add order
     *
     * @param \App\CoreBundle\Entity\Orders\Order $order
     *
     * @return Estado
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
     * @return Estado
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
     * @return mixed
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param mixed $slug
     * @return Estado
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
        return $this;
    }


}
