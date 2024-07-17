<?php 
/**
 * Clase para gestionar la caché
 * Se encarga de manejar la caché y renderizar las plantillas compiladas
 */

namespace App\Templates\Cache;

class CacheManager {
  protected string $cachePath;

  public function __construct(string $cachePath) {
    $this->cachePath = $cachePath;
  }

  /**
   * Método que incluye el archivo de plantilla compilada, 
   * extrae las variables de datos y captura la salida en un buffer, que luego se retorna como una cadena de texto.
   */
  
  public function fetch(string $compiledTemplatePath, array $data) : string {
    extract($data);
    ob_start();
    include $compiledTemplatePath;
    return ob_get_clean();
  }
}