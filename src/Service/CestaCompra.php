<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\RequestStack;

class CestaCompra
{
    protected $requestStack;
    protected $productos;
    protected $unidades;

    public function __construct(RequestStack $requestStack)
    {
        // Inyecta el stack de peticiones para acceder a la sesión
        $this->requestStack = $requestStack;
    }

    protected function carga_cesta(): void
    {
        // Recupera los datos de la cesta almacenados en la sesión actual
        $sesion = $this->requestStack->getSession();
        $this->productos = $sesion->get('productos', []);
        $this->unidades = $sesion->get('unidades', []);
    }

    public function cargar_productos($productos, $unidades): void
    {
        // Recorre y añade una lista de productos a la cesta
        $this->carga_cesta();
        for ($i = 0; $i < count($productos); $i++) {
            if ($unidades[$i] != 0) {
                $this->cargar_producto($productos[$i], $unidades[$i]);
            }
        }
        $this->guardar_cesta();
    }

    public function cargar_producto($producto, $unidades): void
    {
        // Añade un producto individual o incrementa sus unidades si ya existe
        $codigo = $producto->getCodigo();
        if (!array_key_exists($codigo, $this->productos)) {
            $this->productos[$codigo] = $producto;
            $this->unidades[$codigo] = 0;
        }
        $this->unidades[$codigo] += $unidades;
    }

    public function actualizar_unidades($codigo, $cantidad): void
    {
        // Cambia la cantidad exacta o elimina el producto si la cantidad es <= 0
        $this->carga_cesta();
        if (array_key_exists($codigo, $this->productos)) {
            if ($cantidad <= 0) {
                unset($this->productos[$codigo]);
                unset($this->unidades[$codigo]);
            } else {
                $this->unidades[$codigo] = $cantidad;
            }
            $this->guardar_cesta();
        }
    }

    protected function guardar_cesta(): void
    {
        // Persiste los cambios de la cesta en la sesión
        $sesion = $this->requestStack->getSession();
        $sesion->set('productos', $this->productos);
        $sesion->set('unidades', $this->unidades);
    }

    public function get_productos()
    {
        // Devuelve el array de objetos de productos en la cesta
        $this->carga_cesta();
        return $this->productos;
    }

    public function get_unidades()
    {
        // Devuelve el array de cantidades asociadas a los productos
        $this->carga_cesta();
        return $this->unidades;
    }
}