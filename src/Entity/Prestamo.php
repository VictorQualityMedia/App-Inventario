<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\PrestamoRepository;

#[ORM\Entity(repositoryClass: PrestamoRepository::class)]
#[ORM\Table(name: 'prestamo')]
class Prestamo
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $idPrestamo;

    #[ORM\ManyToOne(targetEntity: Usuario::class)]
    #[ORM\JoinColumn(name: 'email_prestamista', referencedColumnName: 'email', nullable: false)]
    private Usuario $emailPrestamista;

    #[ORM\ManyToOne(targetEntity: Producto::class)]
    #[ORM\JoinColumn(name: 'cod_producto', referencedColumnName: 'idProducto', nullable: false)]
    private Producto $codProducto;

    #[ORM\Column(type: 'integer')]
    private int $cantidad;

    #[ORM\Column(type: 'date')]
    private \DateTimeInterface $fechaPrestamo;

    #[ORM\Column(type: 'date', nullable: true)]
    private ?\DateTimeInterface $fechaDevolucion;

    #[ORM\ManyToOne(targetEntity: Usuario::class)]
    #[ORM\JoinColumn(name: 'usuario_prestamo', referencedColumnName: 'email', nullable: false)]
    private Usuario $usuarioPrestamo;

    #[ORM\Column(type: 'string', columnDefinition: "ENUM('EN ESPERA', 'ACEPTADO', 'DENEGADO')")]
    private string $estado;

    public function getIdPrestamo(): ?int
    {
        return $this->idPrestamo;
    }

    // Getters y Setters para los demás campos
}