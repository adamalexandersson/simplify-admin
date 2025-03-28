<?php
/**
 * Settings page template
 * 
 * @package SimplifyAdmin
 */

namespace SimplifyAdmin;

if (!defined('ABSPATH')) {
    exit;
}

use function admin_url;
use function checked;
use function esc_attr;
use function esc_html;
use function esc_html__;
use function esc_html_e;
use function esc_url;
use function get_admin_page_title;
use function selected;
use function submit_button;
use function wp_nonce_field;
use function __;
use function _e;
use function printf;

?>

<div class="wrap">
    <h1><?php echo esc_html(get_admin_page_title()); ?></h1>

    <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" id="simplify-admin-form" data-current-tab="<?php echo esc_attr($currentTab); ?>">
        <input type="hidden" name="action" value="save_sa_settings">
        <input type="hidden" name="tab" value="<?php echo esc_attr($currentTab); ?>">
        <?php wp_nonce_field('simplify-admin-options'); ?>
        <div class="sa-container">
            <div class="sa-roles-column">
                <h2><?php esc_html_e('User Roles', 'simplify-admin'); ?></h2>
                <ul class="sa-roles-list">
                    <?php foreach ($roles as $role_slug => $role_name) : ?>
                        <li>
                            <label>
                                <input type="radio" name="selected_role" value="<?php echo esc_attr($role_slug); ?>" <?php checked($role_slug === 'administrator'); ?>>
                                <span><?php echo esc_html($role_name); ?></span>
                            </label>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <div class="sa-content-wrapper">
                <div class="sa-settings-column">
                    <nav class="nav-tab-wrapper">
                        <a href="?page=simplify-admin&tab=menu-items" class="nav-tab <?php echo $currentTab === 'menu-items' ? 'nav-tab-active' : ''; ?>">
                            <span class="dashicons dashicons-menu-alt"></span>
                            <?php esc_html_e('Menu Items', 'simplify-admin'); ?>
                        </a>
                        <a href="?page=simplify-admin&tab=admin-bar" class="nav-tab <?php echo $currentTab === 'admin-bar' ? 'nav-tab-active' : ''; ?>">
                            <span class="dashicons dashicons-admin-tools"></span>
                            <?php esc_html_e('Admin Bar', 'simplify-admin'); ?>
                        </a>
                    </nav>
                    <div class="sa-settings-content">
                        <div class="sa-content-header">
                            <div class="sa-content-header-top">
                                <h2 class="sa-content-header-title">
                                    <?php 
                                        if ($currentTab === 'menu-items') {
                                            esc_html_e('Menu Items', 'simplify-admin');
                                        } else {
                                            esc_html_e('Admin Bar', 'simplify-admin');
                                        }
                                    ?>
                                </h2>
                                <span class="sa-current-role">
                                    <?php 
                                        /* translators: %s: User role name */
                                        printf(esc_html__('Editing: %s', 'simplify-admin'), esc_html($roles[$currentRole])); 
                                    ?>
                                </span>
                            </div>

                            <p class="sa-content-header-description"><?php esc_html_e('Choose which items to hide', 'simplify-admin'); ?></p>
                        </div>

                        <?php if ($currentTab === 'menu-items'): ?>
                            <div class="sa-menu-items-list">
                                <?php foreach ($menuItems as $menu_item) : ?>
                                    <?php if(isset($menu_item['title']) && $menu_item['title']): ?>
                                        <div class="sa-menu-item">
                                            <label>
                                                <input type="checkbox" 
                                                       name="sa_settings[<?php echo esc_attr($menu_item['id']); ?>]" 
                                                       <?php checked(isset($settings[$menu_item['id']])); ?>>
                                                <?php echo esc_html($menu_item['title']); ?>
                                            </label>
                                            
                                            <?php if (!empty($menu_item['submenu'])) : ?>
                                                <div class="sa-submenu-items">
                                                    <?php foreach ($menu_item['submenu'] as $submenu_item) : ?>
                                                        <label>
                                                            <input type="checkbox" 
                                                                   name="sa_settings[<?php echo esc_attr($submenu_item['id']); ?>]" 
                                                                   <?php checked(isset($settings[$submenu_item['id']])); ?>>
                                                            <?php echo esc_html($submenu_item['title']); ?>
                                                        </label>
                                                    <?php endforeach; ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <div class="sa-admin-bar-items-list">
                                <?php 
                                function renderAdminBarItem($item, $settings) {
                                    ?>
                                    <div class="sa-menu-item">
                                        <label>
                                            <input type="checkbox" 
                                                   name="sa_settings[<?php echo esc_attr($item['id']); ?>]" 
                                                   <?php checked(isset($settings[$item['id']])); ?>>
                                            <?php echo wp_kses_post($item['title']); ?>
                                        </label>
                                        <?php if (!empty($item['children'])): ?>
                                            <div class="sa-submenu-items">
                                                <?php foreach ($item['children'] as $child): ?>
                                                    <?php renderAdminBarItem($child, $settings); ?>
                                                <?php endforeach; ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <?php
                                }
                                
                                if (empty($adminBarItems)): ?>
                                    <p><?php esc_html_e('No admin bar items found.', 'simplify-admin'); ?></p>
                                <?php else: 
                                    foreach ($adminBarItems as $item): 
                                        renderAdminBarItem($item, $settings);
                                    endforeach;
                                endif; 
                                ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="sa-save-box">
                    <?php submit_button(esc_html__('Save Settings', 'simplify-admin'), 'primary', 'submit', false); ?>
                </div>
            </div>
        </div>
    </form>
</div> 