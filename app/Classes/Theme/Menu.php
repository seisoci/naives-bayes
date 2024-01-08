<?php

namespace App\Classes\Theme;

use App\Models\MenuManager;
use App\Models\Setting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;

class Menu
{

  public static function sidebar()
  {
    $menuManager = new MenuManager();
    $roleId = isset(Auth::user()->role_id) ? Auth::user()->role_id : NULL;
    $menu_list = $menuManager->get_menu_role((isset(Auth::user()->role_id) ? Auth::user()->role_id : 0));
    $roots = [];
    foreach ($menu_list as $v) :
      $v->parent_id == 0 ? array_push($roots, $v->id) : array_push($roots, $v->parent_id);
    endforeach;
    $roots = array_unique($roots);
    $roots = MenuManager::whereIn('id', $roots)
      ->orderBy('sort', 'asc')
      ->get();
    Log::error($roleId);
    return self::tree($roots, $menu_list, $roleId);
  }

  public static function tree($roots, $menu_list, $roleId, $parentId = 0, $endChild = 0)
  {
    $html = '';
    foreach ($roots as $v):
      if ($v->type == 'module') {
        $html .= '
         </li>
           <li class="nav-item">
            <a class="nav-link menu-link" href="'. $v->path_url .'">
              <i class="' . ($v->icon ?? '') . '"></i> <span>' . $v->title . '</span>
            </a>
        </li>
     ';
      } elseif ($v->type == 'static') {
        $list_menu = $menu_list->where('parent_id', $v->id)->sortBy('sort');

        $html .= '
              <li class="nav-item">
                <a class="nav-link menu-link" href="#' . (!empty($v->title) ? str_replace(' ', '-', $v->title) : '') . '" data-bs-toggle="collapse" role="button"
                   aria-expanded="false" aria-controls="'.(!empty($v->title) ? str_replace(' ', '-', $v->title) : '') .'">
                  <i class="' . ($v->icon ?? '') . '"></i> <span>' . $v->title . '</span>
                </a>
                <div class="collapse '.(self::activeMenu($list_menu) ? 'show' : '').' menu-dropdown" id="' . (!empty($v->title) ? str_replace(' ', '-', $v->title) : '') . '">
                <ul class="nav nav-sm flex-column">
               ';

        foreach ($list_menu as $item):
          $html .= '
            </li>
             <li class="nav-item">
              <a href="' . URL::to($item->path_url) . '" class="nav-link ' . ($item->path_url == request()->getPathInfo() ? 'active' : '') . '">' . $item->title . '</a>
            </li>
          ';
        endforeach;
        $html .= '</ul></div></li>';
      } elseif ($v->type == 'header') {
        $html .= '
          <li class="menu-title"><span>' . $v->title . '</span></li>
        ';
      } else {
        $html .= '';
      }
    endforeach;
    return $html;
  }

  public static function settings()
  {
    $settings = Setting::all();
    $collection = $settings->mapWithKeys(function ($item, $key) {
      return [$item['name'] => $item['value']];
    })->toArray();
    return (object)$collection;
  }

  private static function activeMenu($list_menu)
  {
    $active = FALSE;
    foreach ($list_menu ?? [] as $item):
      $active = request()->segment(2) == str_replace('/panel/', '', (!$item->path_url ? '' : $item->path_url)) ? TRUE : FALSE;
      if($active){
        break;
      }
    endforeach;
    return $active;
  }
}
