<?php

namespace App\Service;

use App\Entity\Producto;
use Symfony\Component\HttpFoundation\RequestStack;

class CestaCompra{
    protected $requestStack;
    
    public function __construct(RequestStack $requestStack){
        $this->requestStack = $requestStack;
    }
    
    public function cargar_producto($producto, $unidades){
        $sesion = $this->requestStack->getSession();
        $cesta = $sesion->get('cesta', []);
        
        $codigo = $producto->getCodigo();
        
        if(!array_key_exists($codigo, $cesta)){
            $cesta[$codigo] = [
                'producto' => $producto,
                'unidades' => 0
            ];
        }
        
        $cesta[$codigo]['unidades'] += $unidades;
        
        $sesion->set('cesta', $cesta);
    }
    
    public function cargar_productos($productos, $unidades){
        for($i=0; $i < count($productos); $i++){
            
            if($unidades[$i] != 0){
                $this->cargar_producto($productos[i], $unidades[$i]);
            }
        }
    }
}