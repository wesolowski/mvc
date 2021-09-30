<?php
declare(strict_types=1);

namespace App\Model\ORMEntityManager;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="categoryProduct")
 */
class CategoryProduct
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private int $id;

    /**
     * @ORM\OneToMany(targetEntity="Category", mappedBy="id")
     */
    private int $categoryId;

    /**
     * @ORM\OneToMany(targetEntity="Product", mappedBy="id")
     */
    private int $productId;
}