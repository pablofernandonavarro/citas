<?php

namespace  App\Services\Siderbar;

use App\Services\Siderbar\itemSiderbar;

class itemGroup implements itemSiderbar
{    
   
    protected string $title;
    protected string $icon;
    protected string $active;
    protected array $items = []; 

 
    public function __construct(string $title,string $icon,bool $active)
    {
        $this->title = $title;
        $this->icon  = $icon;
        $this->active = $active;
       
    }

    public function add(itemLink $item): self
    {
       $this->items[]= $item;
       return $this;

    }

     public function render() :string
     {
       return view('siderbar.item-group',[
        'title'=>$this->title,
        'icon'=>$this->icon,
        'active'=>$this->active,
        'items'=>$this->items,
       ])->render();
     }


      public function authorize() :bool
      {
       return true;
      }

}