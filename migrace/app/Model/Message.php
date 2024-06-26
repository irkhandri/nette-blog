<?php
namespace App\Model;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="Message")
 */
class Message
{

    public function __construct()
    {
        $this->is_read = false;
        $this->created_at = new \DateTime();
    }



    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     */
    private $subject;

    /**
     * @ORM\Column(type="text")
     */
    private $content;

    
   /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    /**
     * @ORM\Column(type="boolean")
     */
    private $is_read;


    public function isIsRead()
    {
        return $this->is_read;
    }

    public function setIsRead(bool $is_read)
    {
        $this->is_read = $is_read;
    }





    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(): self
    {
        $this->created_at = new \DateTime();

        return $this;
    }


    public function getId()
    {
        return $this->id;
    }

    public function setSubject(string $subject)
    {
        $this->subject = $subject;
    }

    public function getSubject()
    {
        return $this->subject ;
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
     * @ORM\ManyToOne(targetEntity="User", inversedBy="inMessages")
     * @ORM\JoinColumn(name="reciever_id", referencedColumnName="id")
     */
    private $reciever;

    public function getReciever()
    {
        return $this->reciever;
    }

    public function setReciever(?User $user): self
    {
        $this->reciever = $user;
        return $this;
    }


    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="outMessages")
     * @ORM\JoinColumn(name="sender_id", referencedColumnName="id")
     */
    private $sender;

    public function getSender()
    {
        return $this->sender;
    }
    public function setSender(?User $user): self
    {
        $this->sender = $user;
        return $this;
    }




   
    
}
