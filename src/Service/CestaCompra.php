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
    
    // recuperamos el array de prodcutos y unidades de la sesión
    protected function carga_cesta(){
        // Recuperar la sesión
        $sesion = $this->requestStack->getSession();
        
        //Si hay productos en la sesión, los cargamos en los productos del objeto cesta
        $this->productos = $sesion->get('productos', []);
        $this->unidades  = $sesion->get('unidades', []);
    }
    
    // Recibe como parametros los productos y las unidades del formulario
    public function cargar_productos($productos, $unidades){
        $this->carga_cesta();
        
        
        for($i=0; $i < count($productos); $i++){
            
            if($unidades[$i] != 0){
                // carga un producto a la sesión
                $this->cargar_producto($productos[$i], $unidades[$i]);
            }
        }
        
        $this->guardar_cesta();
    }
    
    // recibe como parametro un objeto Producto con sus unidades
    public function cargar_producto($producto, $unidades){        
        // ahora podemos utilizar los productos y las unidades
        // cojo el código para buscar en la cesta
        $codigo = $producto->getCodigo();
        
        if(!array_key_exists($codigo, $this->productos)){
            $this->productos[$codigo] = $producto;
            $this->unidades[$codigo] = 0;
        }
        
        $this->unidades[$codigo] += $unidades;
    }
    
    protected function guardar_cesta(){
        $sesion = $this->requestStack->getSession();
        
        $sesion->set('productos', $this->productos);
        $sesion->set('unidades', $this->unidades);
    }
    
    public function get_productos(){
        $this->carga_cesta(); // cargamos los productos de la sesión
        return $this->productos;
    }

    public function get_unidades(){
        $this->carga_cesta(); // cargamos las unidades de la sesión
        return $this->unidades;
    }
    
    public function eliminar_producto($codigo_producto, $unidades){
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