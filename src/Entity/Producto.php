<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ProductoRepository;

#[ORM\Entity(repositoryClass: ProductoRepository::class)]
#[ORM\Table(name: 'producto')]
class Producto
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $idProducto;

    #[ORM\Column(type: 'string', length: 200)]
    private string $nombre;

    #[ORM\Column(type: 'date')]
    private \DateTimeInterface $fechaCompra;

    #[ORM\Column(type: 'float')]
    private float $precio;

    #[ORM\Column(type: 'string', length: 200)]
    private string $categoria;

    #[ORM\Column(type: 'integer')]
    private int $cantidad;

    #[ORM\Column(type: 'integer')]
    private int $stock;

    #[ORM\Column(type: 'integer')]
    private int $prestado;

    #[ORM\Column(type: 'integer')]
    private int $malgastado;

    public function getIdProducto(): ?int
    {
        return $this->idProducto;
    }

    // Getters y Setters para los demás campos
}