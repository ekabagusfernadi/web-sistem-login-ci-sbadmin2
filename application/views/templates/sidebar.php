<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

<!-- Sidebar - Brand -->
<a class="sidebar-brand d-flex align-items-center justify-content-center" href="<?= base_url("user"); ?>">
    <div class="sidebar-brand-icon rotate-n-15">
        <!-- <i class="fas fa-laugh-wink"></i> -->
        <i class="fas fa-code"></i>
    </div>
    <div class="sidebar-brand-text mx-3">Kija Admin</div>
</a>

<!-- Divider -->
<hr class="sidebar-divider">


<!-- Query menu -->
<?php
    $role_id = $this->session->userdata("role_id");
    $queryMenu = "SELECT user_menu.id_user_menu, menu FROM user_access_menu JOIN user_menu ON(user_access_menu.id_user_menu = user_menu.id_user_menu) WHERE role_id = $role_id ORDER BY user_access_menu.id_user_menu ASC";

    $menu = $this->db->query($queryMenu)->result_array();

?>

<!-- Looping Menu -->
<?php foreach( $menu as $men ) : ?>
    <!-- Heading -->
    <div class="sidebar-heading">
        <?= $men["menu"]; ?>
    </div>

    <!-- Siapkan Sub-Menu Sesuai Menu -->
    <?php
        $id_user_menu = $men["id_user_menu"];
        $querySubMenu = "SELECT * FROM user_sub_menu WHERE id_user_menu = $id_user_menu AND is_active = 1";
    
        $subMenu = $this->db->query($querySubMenu)->result_array();
    ?>

    <?php foreach( $subMenu as $sb ) : ?>
        <li class="nav-item <?= ($sb['title'] == $page_name)? 'active' : ''; ?>">
            <a class="py-2 nav-link" href="<?= base_url($sb['url']); ?>">
                <i class="<?= $sb["icon"]; ?>"></i>
                <!-- fa-fw = supaya fix width -->
                <span><?= $sb["title"]; ?></span></a>
        </li>
    <?php endforeach; ?>
    

    <!-- Divider -->
    <hr class="sidebar-divider">
<?php endforeach; ?>


<li class="nav-item">
    <a class="nav-link" href="<?= base_url("auth/logout"); ?>">
        <i class="fas fa-fw fa-sign-out-alt"></i>
        <!-- fa-fw = supaya fix width -->
        <span>Logout</span></a>
</li>

<!-- Divider -->
<hr class="sidebar-divider d-none d-md-block">

<!-- Sidebar Toggler (Sidebar) -->
<div class="text-center d-none d-md-inline">
    <button class="rounded-circle border-0" id="sidebarToggle"></button>
</div>

</ul>
<!-- End of Sidebar -->