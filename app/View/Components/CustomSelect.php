<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class CustomSelect extends Component
{
    public $name;
    public $id;
    public $options;
    public $placeholder;
    public $selected;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($name, $id, $options, $placeholder = 'Pilih...', $selected = null)
    {
        $this->name = $name;
        $this->id = $id;
        $this->options = $options;
        $this->placeholder = $placeholder;
        $this->selected = $selected;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.custom-select');
    }
}
