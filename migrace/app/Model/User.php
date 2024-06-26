<?php
namespace App\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="User")
 */
class User
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @ORM\Column(type="string")
     */
    private $username;


    /**
     * @ORM\Column(type="string")
     */
    private $passwordHash;


    /**
     * @ORM\Column(type="string")
     */
    private $email;




    /**
     * @ORM\Column(type="string")
     */
    private $imageUrl;

    public function getId()
    {
        return $this->id;

    }
    public function getRoles(): array
    {
        return $this->roles;
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;
        return $this;
    }

    public function setUsername(string $username)
    {
        $this->username = $username;
    }

    public function getUsername()
    {
        return $this->username ;
    }


    public function setEmail(string $email)
    {
        $this->email = $email;
    }

    public function getEmail()
    {
        return $this->email ;
    }



    public function setImageUrl(string $imageUrl)
    {
        $this->imageUrl = $imageUrl;
    }

    public function getImageUrl()
    {
        return $this->imageUrl ;
    }

    public function __construct()
    {
        $this->interests = new ArrayCollection();
        $this->blogs = new ArrayCollection();
        $this->roles[] = 'user';
        $this->created_at = new \DateTime();
        $this->imageUrl = 'https://static.vecteezy.com/system/resources/thumbnails/020/765/399/small/default-profile-account-unknown-icon-black-silhouette-free-vector.jpg';
    }

    /**
     * @ORM\OneToMany(targetEntity="Interest", mappedBy="user")
     */
    private $interests;


     /**
     * @return Collection|Interest[]
     */
    public function getInterests(): Collection
    {
        return $this->interests;
    }

    public function addInterest(Interest $interest): self
    {
        if (!$this->interests->contains($interest)) {
            $this->interests[] = $interest;
            $interest->setUser($this);
        }

        return $this;
    }

    public function removeInterest(Interest $interest): self
    {
        if ($this->interests->contains($interest)) {
            $this->interests->removeElement($interest);
            // set the owning side to null (unless already changed)
            if ($interest->getUser() === $this) {
                $interest->setUser(null);
            }
        }

        return $this;
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
     * @ORM\OneToMany(targetEntity="Blog", mappedBy="user")
     */
    private $blogs;
 /**
     * @return Collection|Blog[]
     */
    public function getBlogs(): Collection
    {
        return $this->blogs;
    }

    public function addBlogs(Blog $blog): self
    {
        if (!$this->blogs->contains($blog)) {
            $this->blogs[] = $blog;
            $blog->setUser($this);
        }

        return $this;
    }

    // public function removeBlog(Blog $blog): self
    // {
    //     if ($this->blogs->contains($blog)) {
    //         $this->blogs->removeElement($blog);
    //         if ($blog->getUser() === $this) {
    //             $blog->setUser(null);
    //         }
    //     }

    //     return $this;
    // }


    /**
     * @ORM\OneToMany(targetEntity="Message",mappedBy="reciever" )
     */
    private $inMessages;
 /**
     * @return Collection|Message[]
     */
    public function getInMessages(): Collection
    {
        return $this->inMessages;
    }

    public function addInMessages(Message $message): self
    {
        if (!$this->inMessages->contains($message)) {
            $this->inMessages[] = $message;
            // $message->setUser($this);
        }
        return $this;
    }

    

    /**
     * @ORM\OneToMany(targetEntity="Message", mappedBy="sender")
     */
    private $outMessages;
 /**
     * @return Collection|Message[]
     */
    public function getOutMessages(): Collection
    {
        return $this->outMessages;
    }

    public function addOutMessages(Message $message): self
    {
        if (!$this->outMessages->contains($message)) {
            $this->outMessages[] = $message;
            // $message->setUser($this);
        }
        return $this;
    }



    
}
