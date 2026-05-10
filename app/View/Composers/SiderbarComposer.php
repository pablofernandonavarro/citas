<?php

namespace App\View\Composers;

use App\Services\Siderbar\itemGroup;
use App\Services\Siderbar\itemHeader;
use App\Services\Siderbar\itemLink;
use Illuminate\View\View;

class SiderbarComposer
{
    public function compose(View $view)
    {
        $items = collect(config('sidebar'))
            ->map(function ($item) {
                return $this->parseItem($item);
            });

        $items = $items->filter(function ($item) {
            return $item->authorize();
        });

        $view->with('siderbarItems', $items);

    }

    public function parseItem($item)
    {
        switch ($item['type']) {
            case 'header':
                return new itemHeader(
                    title: $item['title'],
                    can: $item['can'] ?? []
                );
                break;
            case 'link':
                if ($item['route'] ?? null) {
                    $href = route($item['route']);
                } elseif ($item['url'] ?? null) {
                    $href = url($item['url']);
                } else {
                    $href = '#';
                }
                $active = ($item['active'] ?? null) ? request()->routeIs($item['active']) : false;

                return new itemLink(
                    title: $item['title'],
                    icon: $item['icon'] ?? 'fa-regular fa-circule',
                    href: $href,
                    active: $active,
                    can: $item['can'] ?? [],
                    target: $item['target'] ?? '_self',
                );

                break;
            case 'group':

                $group = new itemGroup(
                    title: $item['title'],
                    icon: $item['icon'] ?? 'fa-regular fa-circule',
                    active: $item['active'] ? request()->routeIs($item['active']) : false,
                );
                foreach ($item['item'] as $subItem) {
                    $group->add(
                        $this->parseItem($subItem)
                    );
                }

                return $group;
                break;
            default:
                throw new \InvalidArgumentException("desconocido tipo fr item '{$item['type']}");
        }
    }
}
