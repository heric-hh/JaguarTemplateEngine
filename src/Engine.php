<?php 
/**
 * Clase principal del motor
 * Actúa como el punto de entrada principal para el motor de plantillas
 * Coordina la carga, compilación y renderizado de las plantillas.
 */
namespace App;

use App\Templates\Loaders\FileLoader;
use App\Templates\Compilers\JaguarCompiler;
use App\Templates\Cache\CacheManager;
use App\Templates\Slots\SlotManager;

class Engine {
  private FileLoader $loader;
  private JaguarCompiler $compiler;
  private CacheManager $cache;
  private SlotManager $slotManager;

  public function __construct(array $config) {
    $this->loader = new FileLoader($config['template_path']);
    $this->compiler = new JaguarCompiler($config['cache_path']);
    $this->cache = new CacheManager($config['cache_path']);
    $this->slotManager = new SlotManager();
  }

  /**
   * Método principal que maneja el proceso de carga, compilación y renderizado de la plantilla.
   */
  public function render(string $template, array $data = []) : string {
    $templatePath = $this->loader->load($template);
    $compiledTemplatePath = $this->compiler->compile($templatePath);
    $data['_env'] = $this->slotManager;
    return $this->cache->fetch($compiledTemplatePath, $data);
  }
}