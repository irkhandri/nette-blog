<?php
namespace App\Model;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;


/**
 * @ORM\Entity
 * @ORM\Table(name="Comment")
 */
class Comment
{

    public function __construct()
    {
        $this->created_at = new \DateTime();
    }



    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Serializer\Groups({"default", "comment"})

     */
    private $id;


    /**
     * @ORM\Column(type="text")
     * @Serializer\Groups({"default", "comment"})
     */
    private $content;

    
   /**
     * @ORM\Column(type="datetime")
     * @Serializer\Groups({"default", "comment"})
     */
    private $created_at;

    /**
     * @ORM\Column(type="boolean")
     * @Serializer\Groups({"default", "comment"})
     */
    private $is_liked;


    public function isIsLiked()
    {
        return $this->is_liked;
    }

    public function setIsLiked(bool $is_liked)
    {
        $this->is_liked = $is_liked;
    }





    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    // public function setCreatedAt(): self
    // {
    //     $this->created_at = new \DateTime();

    //     return $this;
    // }


    public function getId()
    {
        return $this->id;
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
     * @ORM\ManyToOne(targetEntity="User", inversedBy="comments")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * @Serializer\Groups({"default", "comment"})

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


    /**
     * @ORM\ManyToOne(targetEntity="Blog", inversedBy="comments")
     * @ORM\JoinColumn(name="blog_id", referencedColumnName="id")
     */
    private $blog;

    public function getBlog()
    {
        return $this->blog;
    }
    public function setBlog(?Blog $blog): self
    {        
        // $blog->addComments($this);
        $this->blog = $blog;
        return $this;
    }




   
    
}
