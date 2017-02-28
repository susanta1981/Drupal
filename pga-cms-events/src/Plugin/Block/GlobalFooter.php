<?php
namespace Drupal\events\Plugin\Block;

use Drupal\Core\Block\BlockBase;


/**
* Provides a 'Global footer' block.
*
* @Block(
*  id = "global_footer",
*  admin_label = @Translation("Global Footer"),
* )
*/
class GlobalFooter extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {

    $menu = $this->get_menu_tree('main');
    
    $footer_link = $this->get_menu_tree('footer');

    return array(
      '#theme' => 'global_footer',
      '#main_menu' => $menu,
      '#footer_link' => $footer_link,
    );
  }
  
  /**
   * List menu items by menu name.
   * 
   */

  protected function get_menu_tree($menu_name) {
    $tree = \Drupal::menuTree()->load($menu_name, new \Drupal\Core\Menu\MenuTreeParameters());
    $menu = [];
    foreach ($tree as $menu_item) {
      $url = $menu_item->link->getUrlObject()->toString();
      $menu[] = ['title' => $menu_item->link->getTitle(), 'url' => $url];
    }
    return $menu;
  }

}