<?php

namespace  App\Services\Siderbar;
use Illuminate\Support\Facades\Gate;

class itemHeader implements itemSiderbar
{

    private string $title;
    private array $can ;


    public function __construct($title,$can)
    {
        $this->title = $title;
        $this->can   = $can;
    }

     public function render() :string
     {
          return <<<HTML
    <h1 class="px-3 mt-4 mb-2 text-gray-500 uppercase dark:text-gray-400 text-sm">
            {$this->title}</h1>
    HTML;
     }


      public function authorize() :bool
      {


       return count($this->can)
          ? Gate::any($this->can)
          : true;
      }

}
