<ul id="<?php echo $namespace; ?>-primary-navigation">
    <?php foreach( $menu_hooks as $menu_key => $menu_hook ): ?>
        <li>
            <a id="<?php echo "{$namespace}-nav-{$menu_key}"; ?>" href="<?php echo admin_url( 'admin.php?page=' . $menu_hook['path'] ); ?>"<?php if( $screen->id == $menu_hook['hook'] ) echo ' class="active"'; ?>>
                <span><?php echo $menu_hook['label']; ?></span>
            </a>
        </li>
    <?php endforeach; ?>
</ul>