<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800"><?= $page_name; ?></h1>

    <div class="row">
        <div class="col-lg-6">

        <!-- pesan -->
        <?= $this->session->flashdata("message"); ?>
            
            <h5>Role : <?= $role["role"]; ?></h5>

            <table class="table table-hover">
                <thead>
                    <tr>
                        <th scope="col">No.</th>
                        <th scope="col">Menu</th>
                        <th scope="col">Access</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 1; ?>
                    <form action="<?= base_url("admin/accessMenu") ?>" method="POST">
                        <?php foreach( $menu as $m ) : ?>
                            <tr>
                                <th scope="row"><?= $i++; ?>.</th>
                                <td><?= $m["menu"]; ?></td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" <?= check_access($role["role_id"], $m["id_user_menu"]); ?> data-roleid="<?= $role['role_id']; ?>" data-idusermenu="<?= $m['id_user_menu']; ?>">
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </form>
                </tbody>
            </table>

        </div>
    </div>

    </div>
<!-- /.container-fluid -->

</div>
<!-- End of Main Content -->