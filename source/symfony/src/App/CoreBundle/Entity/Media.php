<?php
namespace App\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use MediaMonks\SonataMediaBundle\Entity\Media as BaseMedia;

/**
 * @ORM\Entity(repositoryClass="MediaMonks\SonataMediaBundle\Repository\MediaRepository")
 * @ORM\Table
 */
class Media extends BaseMedia
{
    /**
     * @ORM\Column(type="string",length=191, nullable=true)
     */
    protected $folder;

    /**
     * @ORM\Column(type="string",length=191, nullable=true)
     */
    protected $originalUrl;

    /**
     * @return mixed
     */
    public function getFolder()
    {
        return $this->folder;
    }
    /**
     * @param mixed $folder
     */
    public function setFolder($folder)
    {
        $this->folder = $folder;
    }

    /**
     * @return mixed
     */
    public function getOriginalUrl()
    {
        return $this->originalUrl;
    }

    /**
     * @param mixed $originalUrl
     */
    public function setOriginalUrl($originalUrl)
    {
        $this->originalUrl = $originalUrl;
    }

}
