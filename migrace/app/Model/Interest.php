<?php
namespace App\Model;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="Interest")
 */
class Interest
{



    public function __construct()
    {
        $this->created_at = new \DateTime();
    }



    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    public function getId()
    {
        return $this->id;
    }

    /**
     * @ORM\Column(type="string")
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     */
    private $content;

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
     * @ORM\ManyToOne(targetEntity="User", inversedBy="interests")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;


    public function getUser()
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }


}
