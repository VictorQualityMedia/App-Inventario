<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'prestamo')]
class Prestamo
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer', name:'id_prestamo')]
    private $idPrestamo;

    #[ORM\ManyToOne(targetEntity: Usuario::class)]
    #[ORM\JoinColumn(name: 'email_prestamista', referencedColumnName: 'email', nullable: false)]
    private $emailPrestamista;

    #[ORM\ManyToOne(targetEntity: Producto::class)]
    #[ORM\JoinColumn(name: 'cod_producto', referencedColumnName: 'idProducto', nullable: false)]
    private $codProducto;

    #[ORM\Column(type: 'integer', name:'cantidad')]
    private $cantidad;

    #[ORM\Column(type: 'date', name:'fecha_prestamo')]
    private $fechaPrestamo;

    #[ORM\Column(type: 'date', name:'fecha_devolucion')]
    private $fechaDevolucion;

    #[ORM\ManyToOne(targetEntity: Usuario::class)]
    #[ORM\JoinColumn(name: 'usuario_prestamo', referencedColumnName: 'email', nullable: false)]
    private $usuarioPrestamo;

    #[ORM\Column(type: 'string', name:'estado')]
    private $estado;

    // Getters y Setters para los demás campos
    public function getIdPrestamo(): ?int
    {
        return $this->idPrestamo;
    }

    public function getEmailPrestamista(): ?Usuario
    {
        return $this->emailPrestamista;
    }

    public function setEmailPrestamista(?Usuario $emailPrestamista): self
    {
        $this->emailPrestamista = $emailPrestamista;
        return $this;
    }

    public function getCodProducto(): ?Producto
    {
        return $this->codProducto;
    }

    public function setCodProducto(?Producto $codProducto): self
    {
        $this->codProducto = $codProducto;
        return $this;
    }

    public function getCantidad(): ?int
    {
        return $this->cantidad;
    }

    public function setCantidad(int $cantidad): self
    {
        $this->cantidad = $cantidad;
        return $this;
    }

    public function getFechaPrestamo(): ?\DateTimeInterface
    {
        return $this->fechaPrestamo;
    }

    public function setFechaPrestamo(\DateTimeInterface $fechaPrestamo): self
    {
        $this->fechaPrestamo = $fechaPrestamo;
        return $this;
    }

    public function getFechaDevolucion(): ?\DateTimeInterface
    {
        return $this->fechaDevolucion;
    }

    public function setFechaDevolucion(\DateTimeInterface $fechaDevolucion): self
    {
        $this->fechaDevolucion = $fechaDevolucion;
        return $this;
    }

    public function getUsuarioPrestamo(): ?Usuario
    {
        return $this->usuarioPrestamo;
    }

    public function setUsuarioPrestamo(?Usuario $usuarioPrestamo): self
    {
        $this->usuarioPrestamo = $usuarioPrestamo;
        return $this;
    }

    public function getEstado(): ?string
    {
        return $this->estado;
    }

    public function setEstado(string $estado): self
    {
        $this->estado = $estado;
        return $this;
    }
}