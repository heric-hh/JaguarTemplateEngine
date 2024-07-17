<?php

namespace App\Templates\Slots;

class SlotManager {
  private array $slots = [];
  private ?string $currentSlot = null;

  public function startSlot(string $name) : void {
    $this->currentSlot = $name;
    ob_start();
  }

  public function endSlot() : void {
    if($this->currentSlot !== null) {
      $this->slots[$this->currentSlot] = ob_get_clean();
      $this->currentSlot = null;
    }
  }

  public function fillSlot(string $name) : void {
    $this->currentSlot = $name;
    ob_start();
  }

  public function endFillSlot() : void {
    if($this->currentSlot !== null) {
      $this->slots[$this->currentSlot] = ob_get_clean();
      $this->currentSlot = null;
    }
  }

  public function hasSlot(string $name) : bool {
    return isset($this->slots[$name]);
  }

  public function renderSlot(string $name) : void {
    echo $this->slots[$name] ?? '';
  }
}