<?php

namespace AppBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Filesystem\Filesystem;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user", indexes={
 * @ORM\Index(name="wechatId", columns={"wechatId"})
 * })
 * @ORM\HasLifecycleCallbacks
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="createdAt", type="datetime")
     */
    protected $createdAt;

    /**
     * @var string
     *
     * @ORM\Column(name="picture", type="string", length=255, nullable=true)
     */
    private $picture;

    /**
     * @var string
     *
     * @ORM\Column(name="wechatId", type="string", length=255, nullable=true)
     */
    private $wechatId;

    /**
     * @var boolean
     *
     * @ORM\Column(name="wechatStatus", type="boolean")
     */
    private $wechatStatus;

    /**
     * @var boolean
     *
     * @ORM\Column(name="wechatAt", type="datetime", nullable=true)
     */
    private $wechatAt;


    /**
     * @Assert\Image(
     *     maxSize = "200k",
     *     mimeTypes = {"image/png", "image/jpeg", "image/jpg"},
     *     mimeTypesMessage = "Please upload a valid PNG or JPEG image"
     * )
     */
    private $file;

    private $filename;

    private $filepath;

    public function __construct()
    {
        parent::__construct();
        // your own logic
        $this->createdAt = new \DateTime('now');
        $this->wechatStatus = false;
    }


    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return User
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set picture
     *
     * @param string $picture
     * @return User
     */
    public function setPicture($picture)
    {
        $this->picture = $picture;

        return $this;
    }

    /**
     * Get picture
     *
     * @return string
     */
    public function getPicture()
    {
        return $this->picture;
    }

    /**
     * Get picture
     *
     * @return string
     */
    public function getAvatar()
    {
        if($this->picture){
            return '/'.$this->getWebPath();
        }
        return 'http://www.gravatar.com/avatar/'.md5(trim($this->email));
    }


    /**
     * Sets file.
     *
     * @param UploadedFile $file
     */
    public function setFile($file = null)
    {
        $this->file = $file;
        // check if we have an old image path
        if (isset($this->picture)) {
            // store the old name to delete after the update
            $this->temp = $this->picture;
            $this->picture = null;
        } else {
            $this->picture = 'initial';
        }
    }

    /**
     * Get file.
     *
     * @return UploadedFile
     */
    public function getFile()
    {
        return $this->file;
    }


    public function getAbsolutePath()
    {
        return null === $this->picture
            ? null
            : $this->getUploadRootDir().'/'.$this->picture;
    }

    /**
     */
    public function getWebPath()
    {
        return null === $this->picture
            ? null
            : $this->getUploadDir().'/'.$this->picture;
    }

    protected function getUploadRootDir()
    {
        // the absolute directory path where uploaded
        // documents should be saved
        return __DIR__.'/../../../web/'.$this->getUploadDir();
    }

    protected function getUploadDir()
    {
        return 'files/picture';
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload()
    {
        if (null !== $this->getFile()) {
            //$this->name = $this->getFile()->getClientOriginalName();
            $this->type = $this->getFile()->getMimeType();
            $this->filename = md5(uniqid(mt_rand(), true)).'.'.$this->getFile()->guessExtension();
            $this->filepath = date('Ym/d');
            $this->picture = $this->filepath . '/' .$this->filename;
        }
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload()
    {
        if (null === $this->getFile()) {
            return;
        }
        $this->getFile()->move($this->getUploadRootDir().'/'.$this->filepath, $this->filename);

        // check if we have an old image
        if (isset($this->temp)) {
            // delete the old image
            @unlink($this->getUploadRootDir().'/'.$this->temp);
            // clear the temp image path
            $this->temp = null;
        }
        $this->file = null;
    }

    /**
     * @ORM\PostRemove()
     */
    public function removeUpload()
    {
        $file = $this->getAbsolutePath();
        if ($file) {
            unlink($file);
        }
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
     * Set wechatId
     *
     * @param string $wechatId
     * @return User
     */
    public function setWechatId($wechatId)
    {
        $this->wechatId = $wechatId;

        return $this;
    }

    /**
     * Get wechatId
     *
     * @return string 
     */
    public function getWechatId()
    {
        return $this->wechatId;
    }

    /**
     * Set wechatStatus
     *
     * @param boolean $wechatStatus
     * @return User
     */
    public function setWechatStatus($wechatStatus)
    {
        $this->wechatStatus = $wechatStatus;

        return $this;
    }

    /**
     * Get wechatStatus
     *
     * @return boolean 
     */
    public function getWechatStatus()
    {
        return $this->wechatStatus;
    }

    /**
     * Set wechatAt
     *
     * @param \DateTime $wechatAt
     * @return User
     */
    public function setWechatAt($wechatAt)
    {
        $this->wechatAt = $wechatAt;

        return $this;
    }

    /**
     * Get wechatAt
     *
     * @return \DateTime 
     */
    public function getWechatAt()
    {
        return $this->wechatAt;
    }
}
