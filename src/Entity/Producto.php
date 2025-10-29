<?php

namespace App\Entity;

use App\Repository\ProductoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductoRepository::class)]
class Producto
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 6)]
    private ?string $codigo = null;

    #[ORM\Column(type: Types::FLOAT, precision: 10, scale: 2)]
    private ?float $precio = null;

    #[ORM\Column(length: 255)]
    private ?string $nombre = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $nombre_corto = null;

    #[ORM\Column(type: Types::TEXT, length: 6144)]
    private ?string $descripcion = null;

    #[ORM\ManyToOne(inversedBy: 'productos')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Categoria $categoria = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCodigo(): ?string
    {
        return $this->codigo;
    }

    public function setCodigo(string $codigo): static
    {
        $this->codigo = $codigo;

        return $this;
    }

    public function getPrecio(): ?float
    {
        return $this->precio;
    }

    public function setPrecio(float $precio): static
    {
        $this->precio = $precio;

        return $this;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): static
    {
        $this->nombre = $nombre;

        return $this;
    }

    public function getNombreCorto(): ?string
    {
        return $this->nombre_corto;
    }

    public function setNombreCorto(?string $nombre_corto): static
    {
        $this->nombre_corto = $nombre_corto;

        return $this;
    }

    public function getDescripcion(): ?string
    {
        return $this->descripcion;
    }

    public function setDescripcion(string $descripcion): static
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    public function getCategoria(): ?self
    {
        return $this->categoria;
    }

    public function setCategoria(?self $categoria): static
    {
        $this->categoria = $categoria;

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getProductos(): Collection
    {
        return $this->productos;
    }

    public function addProducto(self $producto): static
    {
        if (!$this->productos->contains($producto)) {
            $this->productos->add($producto);
            $producto->setCategoria($this);
        }

        return $this;
    }

    public function removeProducto(self $producto): static
    {
        if ($this->productos->removeElement($producto)) {
            // set the owning side to null (unless already changed)
            if ($producto->getCategoria() === $this) {
                $producto->setCategoria(null);
            }
        }

        return $this;
    }
}
