<?php 
/**
 * Clase para cargar plantillas
 */
namespace App\Templates\Loaders;

class FileLoader {
  protected string $path;

  // Constructor que inicializa la ruta base donde se almacenan las plantillas.
  public function __construct(string $path) {
    $this->path = $path;
  }

  /**
   * Método que recibe el nombre de la plantilla, 
   * construye la ruta completa del archivo y verifica si existe. 
   * Si el archivo existe, retorna la ruta completa; de lo contrario, lanza una excepción.
   */
  
  public function load(string $template) : string {
    $templatePath = $this->path . "/" . $template . ".jaguar.php";
    if(file_exists($templatePath)) {
      return $templatePath;
    }
    throw new \Exception("Template not found: " . $template);
  }
}