<?php
declare(strict_types=1);

namespace App\Model\ORMEntityManager;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="product")
 */
class Product
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     * @ORM\ManyToOne(targetEntity="CategoryProduct", inversedBy="productId")
     */
    private int $id;

    /**
     * @ORM\Column(type="string")
     * @ORM\Column(unique=true)
     * @ORM\Column(nullable=false)
     */
    private string $name;

    /**
     * @ORM\Column(type="float")
     */
    private float $price;

    /**
     * @ORM\Column(type="string")
     */
    private string $description;
}