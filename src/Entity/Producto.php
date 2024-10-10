<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'producto')]
class Producto
{
    #[ORM\Id]
    #[ORM\Column(type: 'integer', name: 'sn')]
    private $sn;

    #[ORM\Column(type: 'string', name: 'nombre')]
    private $nombre;

    #[ORM\Column(type: 'date', name: 'fecha_compra')]
    private $fechaCompra;

    #[ORM\Column(type: 'float', name: 'precio')]
    private $precio;

    #[ORM\Column(type: 'string', name: 'categoria')]
    private $categoria;

    #[ORM\Column(type: 'integer', name: 'cantidad')]
    private $cantidad;

    #[ORM\Column(type: 'integer', name: 'stock')]
    private $stock;

    #[ORM\Column(type: 'integer', name: 'prestado')]
    private $prestado;

    #[ORM\Column(type: 'integer', name: 'malgastado')]
    private $malgastado;

    // Getter y Setter para sn
    public function getSn(): ?int
    {
        return $this->sn;
    }

    public function setSn(int $sn): self
    {
        $this->sn = $sn;
        return $this;
    }

    // Getter y Setter para nombre
    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): self
    {
        $this->nombre = $nombre;
        return $this;
    }

    // Getter y Setter para fechaCompra
    public function getFechaCompra(): ?\DateTimeInterface
    {
        return $this->fechaCompra;
    }

    public function setFechaCompra(\DateTimeInterface $fechaCompra): self
    {
        $this->fechaCompra = $fechaCompra;
        return $this;
    }

    // Getter y Setter para precio
    public function getPrecio(): ?float
    {
        return $this->precio;
    }

    public function setPrecio(float $precio): self
    {
        $this->precio = $precio;
        return $this;
    }

    // Getter y Setter para categoria
    public function getCategoria(): ?string
    {
        return $this->categoria;
    }

    public function setCategoria(string $categoria): self
    {
        $this->categoria = $categoria;
        return $this;
    }

    // Getter y Setter para cantidad
    public function getCantidad(): ?int
    {
        return $this->cantidad;
    }

    public function setCantidad(int $cantidad): self
    {
        $this->cantidad = $cantidad;
        return $this;
    }

    // Getter y Setter para stock
    public function getStock(): ?int
    {
        return $this->stock;
    }

    public function setStock(int $stock): self
    {
        $this->stock = $stock;
        return $this;
    }

    // Getter y Setter para prestado
    public function getPrestado(): ?int
    {
        return $this->prestado;
    }

    public function setPrestado(int $prestado): self
    {
        $this->prestado = $prestado;
        return $this;
    }

    // Getter y Setter para malgastado
    public function getMalgastado(): ?int
    {
        return $this->malgastado;
    }

    public function setMalgastado(int $malgastado): self
    {
        $this->malgastado = $malgastado;
        return $this;
    }
}