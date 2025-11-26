<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\RequestStack;

class CestaCompra{
    protected $requestStack;
    
    protected $productos;
    
    protected $unidades;
    
    public function __construct(RequestStack $requestStack){
        $this->requestStack = $requestStack;
    }
    
    protected function carga_cesta(){
        $sesion = $this->requestStack->getSession();
        
        $this->productos = $sesion->get('productos', []);
        $this->unidades  = $sesion->get('unidades', []);
    }
    
    protected function guardar_cesta(){
        $sesion = $this->requestStack->getSession();
        
        $sesion->set('productos', $this->productos);
        $sesion->set('unidades', $this->unidades);
    }
    
    // recibe como parametro un objeto Producto con sus unidades
    public function cargar_producto($producto, $unidades){
        $this->carga_cesta();
        
        // ahora podemos utilizar los productos y las unidades
        $codigo = $producto->getCodigo();
        
        if(!array_key_exists($codigo, $this->productos)){
            $this->productos[$codigo] = $producto;
            $this->unidades[$codigo] = 0;
        }
        
        $this->unidades[$codigo] += $unidades;
        
        $this->guardar_cesta();
    }
    
    public function cargar_productos($productos, $unidades){
        $this->carga_cesta();
        
        for($i=0; $i < count($productos); $i++){
            
            if($unidades[$i] != 0){
                // carga un producto a la sesión
                $this->cargar_producto($productos[$i], $unidades[$i]);
            }
        }
    }
    
    public function get_productos(){
    $this->carga_cesta(); // cargamos los productos de la sesión
    return $this->productos;
}

    public function get_unidades(){
        $this->carga_cesta(); // cargamos las unidades de la sesión
        return $this->unidades;
    }
    
}