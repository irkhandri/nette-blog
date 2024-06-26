<?php
namespace App\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="Blog")
 */
class Blog
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     */
    private $imageUrl;

    /**
     * @ORM\Column(type="string")
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     */
    private $content;


    public function getId()
    {
        return $this->id;
    }

    public function setImageUrl(string $imageUrl)
    {
        $this->imageUrl = $imageUrl;
    }

    public function getImageUrl()
    {
        return $this->imageUrl ;
    }



    public function setTitle(string $title)
    {
        $this->title = $title;
    }

    public function getTitle()
    {
        return $this->title ;
    }


    public function setContent(string $content)
    {
        $this->content = $content;
    }

    public function getContent()
    {
        return $this->content ;
    }
   

    public function __construct()
    {
        $this->created_at = new \DateTime();
        $this->imageUrl = 'https://www.shutterstock.com/image-vector/default-ui-image-placeholder-wireframes-600nw-1037719192.jpg';
    }

    
    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;


    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(): self
    {
        $this->created_at = new \DateTime();

        return $this;
    }

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="blogs")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private  $user;


    public function getUser()
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }

}
