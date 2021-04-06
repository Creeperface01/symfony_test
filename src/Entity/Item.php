<?php
declare(strict_types=1);


namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Item
 * @package App\Entity
 * @ORM\Table(name="items")
 * @ORM\Entity
 */
class Item
{

    public const STATE_NONE = 0;
    public const STATE_PURCHASED = 1;
    public const STATE_DENIED = 2;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @var int
     */
    public int $id;

    /**
     * @ORM\Column(type="string")
     * @var string
     */
    public string $name;

    /**
     * @ORM\Column(type="float")
     * @var float
     */
    public float $price;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @var string|null
     */
    public ?string $note = null;

    /**
     * @ORM\Column(type="integer")
     * @var int
     */
    public int $state = self::STATE_NONE;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string|null
     */
    public function getNote(): ?string
    {
        return $this->note;
    }

    /**
     * @param string|null $note
     */
    public function setNote(?string $note): void
    {
        $this->note = $note;
    }

    /**
     * @return int
     */
    public function getState(): int
    {
        return $this->state;
    }

    /**
     * @param int $state
     */
    public function setState(int $state): void
    {
        $this->state = $state;
    }

    /**
     * @return float
     */
    public function getPrice(): float
    {
        return $this->price;
    }

    /**
     * @param float $price
     */
    public function setPrice(float $price): void
    {
        $this->price = $price;
    }



}