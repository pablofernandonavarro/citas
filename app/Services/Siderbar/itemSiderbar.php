<?php

namespace App\Services\Siderbar;

interface itemSiderbar
{
    public function render() :string;
    public function authorize() :bool;

    
}