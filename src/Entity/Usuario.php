<?php

namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[ORM\Entity]
#[ORM\Table(name: 'usuario')]
class Usuario implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\Column(type: 'string', name: 'email')]
    private $email;

    #[ORM\Column(type: 'string', name: 'contrasena')]
    private $contrasena;

    #[ORM\Column(type: 'boolean', name: 'rol')]
    private $rol;

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }

    public function getContrasena(): ?string
    {
        return $this->contrasena;
    }

    public function setContrasena(string $contrasena): self
    {
        $this->contrasena = $contrasena;
        return $this;
    }

    public function getRol(): ?bool
    {
        return $this->rol;
    }

    // public function setRol(bool $rol): self
    // {
    //     $this->rol = $rol;
    //     return $this;
    // }

    // Implementación de métodos obligatorios para UserInterface
    public function getRoles(): array {
        if ($this->rol == 1) {
            return ['ROLE_USER', 'ROLE_ADMIN'];
        }
        return ['ROLE_USER']; // Usuario normal por defecto
    }

    public function setRol($rol): void {
        $this->rol = $rol;
    }

    public function getUserIdentifier(): string {
        return $this->getEmail();
    }

    public function getPassword(): ?string {
        return $this->getContrasena();
    }

    public function getSalt(): ?string {
        // Si usas bcrypt o sodium, puedes devolver null
        return null;
    }
    
    public function eraseCredentials(): void {
        
    }
}