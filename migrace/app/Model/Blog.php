<?php
namespace App\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;


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
     * @Serializer\Groups({"default", "blog"})
     */
    private $id;

    /**
     * @ORM\Column(type="string")
    * @Serializer\Groups({"default", "blog"})       */
    private $imageUrl;

    /**
     * @ORM\Column(type="string")
    * @Serializer\Groups({"default", "blog"})       */
    private $title;

    /**
     * @ORM\Column(type="text")
    * @Serializer\Groups({"default", "blog"})       */
    private $content;


    /**
     * @ORM\Column(type="datetime")
     * @Serializer\Groups({"default", "blog"})    
    */
    private $created_at;



    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="blogs")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * @Serializer\Groups({"default", "blog"})
     */
    private  $user;



    /**
     * @ORM\OneToMany(targetEntity="Comment",mappedBy="blog" )
     */
    private $comments;


    /**
     * @return Collection|Comment[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComments(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
        }
        return $this;
    }




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
        $this->comments = new ArrayCollection();

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

   


    public function getUser()
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }

    // /**
    //  * @Serializer\VirtualProperty()
    //  * @Serializer\SerializedName("user")
    //  * @Serializer\Groups({"default"})
    //  */
    // public function getAuthorName()
    // {
    //     return $this->user ? $this->user->getUsername() : null;
    // }

}
