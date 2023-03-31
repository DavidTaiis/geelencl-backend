<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravelista\Ekko\Ekko;

class Menu extends Model
{
    protected $table = 'menu'; //Database table used by the model

    public function buildMenu($user)
    {
        $ekko = new Ekko();
        $menu_parents = self::whereNull('parent_id')->orderBy("weight", 'ASC')->get();

        $result = '<div class="header-menu-wrapper header-menu-wrapper-left" id="kt_header_menu_wrapper">';
        $result .= '<div id="kt_header_menu" class="header-menu header-menu-left header-menu-mobile header-menu-layout-default">';
        $result .= '<ul class="menu-nav">';
        foreach ($menu_parents as $menu_parent) {
            $link = $menu_parent->link;
            $render_item = true;
            if ($link !== '#') {
                $link = url($menu_parent->link);
                $render_item = $user->can('GET ' . $menu_parent->link);
            }

            if ($user->id == 1 || $render_item && $this->isVisibleAllChild($user, $menu_parent->id)) {
                $activeClass = $ekko->isActive('/' . $menu_parent->link) != null || $this->isActiveChild($menu_parent->id) ? 'menu-item-open menu-item-here' : '';
                $sub_menu = $this->buildSubMenu($user, $menu_parent->id);
                $result .= "<li class='menu-item menu-item-submenu menu-item-rel {$activeClass}' data-menu-toggle='hover' aria-haspopup='true'>";
                $result .= "<a href='{$link}' class='menu-link'>";
                $result .= "<span class='menu-text'>{$menu_parent->name}</span>";
                if ($sub_menu != '') {
                    $result .= ' <i class="arrow"></i>';
                }
                $result .= "</a>";
                $result .= $sub_menu;
                $result .= '</li>';
            }
        }
        $result .= '</ul>';
        $result .= '</div>';
        $result .= '</div>';
        return $result;

    }

    public function buildSubMenu($user, $parent_id)
    {
        $actions = self::where('parent_id', '=', $parent_id)->orderBy("weight", 'ASC')->get();

        if (count($actions) == 0)
            return '';

        $result = '';
        $isActiveChild = false;
        $ekko = new Ekko();


        foreach ($actions as $action) {
            $r = $user->can('GET ' . $action->link);
            if ($user->id == 1 || $r) {
                $link = url($action->link);
                $activeClass = '';
                if ($ekko->isActive('/' . $action->link)) {
                    $isActiveChild = true;
                    $activeClass = 'menu-item-active';
                }
                $result .= "<li class='menu-item {$activeClass}'>";
                $result .= "<a href='{$link}' class='menu-link'>";
                $result .= "<span class='menu-text'>{$action->name}</span>";
                $result .= "<span class='menu-desc'></span>";
                $result .= '</a>';
                $result .= '</li>';
            }
        }
        if (count($actions) > 0) {
//            $classIn = $isActiveChild ? 'in' : '';
            $result = '<div class="menu-submenu menu-submenu-classic menu-submenu-left">
                                <ul class="menu-subnav">' . $result;
            $result .= '</ul>';
            $result .= '</div>';
        }
        return $result;
    }

    public function isActiveChild($parent_id)
    {
        $actions = self::where('parent_id', '=', $parent_id)->get();
        $ekko = new Ekko();

        if (count($actions) == 0)
            return false;
        foreach ($actions as $action) {
            if ($ekko->isActive('/' . $action->link)) {
                return true;
            }
        }
        return false;
    }

    public function isVisibleAllChild($user, $parent_id)
    {
        $actions = self::where('parent_id', '=', $parent_id)->get();

        if (count($actions) == 0)
            return true;
        foreach ($actions as $action) {
            $r = $user->can('GET ' . $action->link);
            if ($r) {
                return true;
            }
        }
        return false;
    }

}