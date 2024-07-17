<?php 
/**
 * Clase para compilar plantillas
 * Compila las plantillas, transformando la sintaxis personalizada en PHP ejecutable
 */

namespace App\Templates\Compilers;

class JaguarCompiler {
  private string $cachePath;

  public function __construct(string $cachePath) {
    $this->cachePath = $cachePath;
  }

  /**
   * Método que compila una plantilla si no está en la caché o si ha sido modificada desde la última compilación.
   * Retorna la ruta del archivo compilado.
   */
  
  public function compile(string $templatePath) : string {
    $compiledPath = $this->cachePath . "/" . md5($templatePath) . ".php";

    //Verifica si el archivo compilado no existe
    //Verifica si el archivo compilado es más antiguo que el archivo de la plantilla original. 
    //Esto asegura que el archivo compilado se regenere si la plantilla original ha sido modificada.

    if(!file_exists($compiledPath) || filemtime($compiledPath) < filemtime($templatePath)) {
      $templateContent = file_get_contents($templatePath);
     
      //Llama a la función compileString, que transforma la sintaxis personalizada de la plantilla en PHP ejecutable. El contenido compilado se almacena en $compiledContent.
      $compiledContent = $this->compileString($templateContent);
      //Escribe el contenido compilado en el archivo de caché ubicado en $compiledPath.
      file_put_contents($compiledPath, $compiledContent);
    }
    return $compiledPath; //Devuelve la ruta del archivo compilado, que ahora contiene el código PHP transformado listo para ser ejecutado.
  }

  // Este método incluye toda la logica de compilacion
  protected function compileString(string $templateContent) : string {
    $templateContent = $this->compileSlots($templateContent);
    $templateContent = $this->compileFills($templateContent);
    $templateContent = preg_replace('/\{\{(.+?)\}\}/', '<?php echo $1; ?>', $templateContent);
    return $templateContent; 
  }

  protected function compileSlots(string $content) : string {
    $pattern = '/@slot\s*\(\'?(.*?)\'?\)(.*?)@endslot/s';
    return preg_replace_callback($pattern, function(array $matches) : string {
      $slotName = $matches[1];
      $slotContent = $matches[2];
      return "<?php \$__env->startSlot('{$slotName}'); ?>{$slotContent}<?php \$__env->endSlot(); ?>";
    }, $content);
  }

  protected function compileFills(string $content) : string {
    $pattern = '/@fill\s*\(\'?(.*?)\'?\)(.*?)@endfill/s';
    return preg_replace_callback($pattern, function(array $matches) : string {
      $slotName = $matches[1];
      $slotContent = $matches[2];
      return "<?php \$__env->fillSlot('{$slotName}'); ?>{$slotContent}<?php \$__env->endFillSlot(); ?>";
    }, $content);
  }

}