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
        $this->requestStack = $requestStack;
    }

    protected function carga_cesta(): void
    {
        $sesion = $this->requestStack->getSession();
        $this->productos = $sesion->get('productos', []);
        $this->unidades = $sesion->get('unidades', []);
    }

    public function cargar_productos($productos, $unidades): void
    {
        $this->carga_cesta();
        for ($i = 0; $i < count($productos); $i++) {
            if ($unidades[$i] != 0) {
                $this->cargar_producto($productos[$i], $unidades[$i]);
            }
        }
        $this->guardar_cesta(); //
    }

    public function cargar_producto($producto, $unidades): void
    {
        $codigo = $producto->getCodigo();
        if (!array_key_exists($codigo, $this->productos)) {
            $this->productos[$codigo] = $producto;
            $this->unidades[$codigo] = 0;
        }
        $this->unidades[$codigo] += $unidades;
    }

    public function actualizar_unidades($codigo, $cantidad): void
    {
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
        $sesion = $this->requestStack->getSession();
        $sesion->set('productos', $this->productos);
        $sesion->set('unidades', $this->unidades);
    }

    public function get_productos()
    {
        $this->carga_cesta();
        return $this->productos;
    }

    public function get_unidades()
    {
        $this->carga_cesta();
        return $this->unidades;
    }

    public function eliminar_producto($codigo_producto, $unidades): void
    {
        $this->carga_cesta();
        if (array_key_exists($codigo_producto, $this->productos)) {
            $this->unidades[$codigo_producto] -= $unidades;
            if ($this->unidades[$codigo_producto] <= 0) {
                unset($this->unidades[$codigo_producto]);
                unset($this->productos[$codigo_producto]);
            }
            $this->guardar_cesta();
        }
    }
}