<?php
// namespace App\Model;

// use Doctrine\ORM\EntityRepository;


// class BlogRepository extends EntityRepository
// {

//     public function counLikesForBlog ($blogId)
//     {
//         $qb = $this->createQueryBuilder('b')
//             ->select('COUNT(c.id) as liked')
//             ->innerJoin('b:comments', 'c')
//             ->where('b.id = :blogId')
//             ->andWhere('c.is_liked = :isLiked')
//             ->setParameter('blogId', $blogId)
//             ->setParameter('isLiked', true);

//         return $qb->getQuery()->getSingleScalarResult();

        
//     }




// }