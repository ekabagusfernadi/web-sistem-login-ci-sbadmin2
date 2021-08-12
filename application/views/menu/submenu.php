<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800"><?= $page_name; ?></h1>

    <div class="row">
        <div class="col-lg">

        <a href="" class="btn btn-primary mb-3" data-toggle="modal" data-target="#newSubmenuModal" id="addButtonSubmenu">Add New Submenu</a>

        <!-- pesan -->
        <?= validation_errors('<div class="alert alert-danger alert-dismissible fade show" role="alert">',
        '<button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>'); ?>
        <?= $this->session->flashdata("message"); ?>
            
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th scope="col">No.</th>
                        <th scope="col">Title</th>
                        <th scope="col">Menu</th>
                        <th scope="col">Url</th>
                        <th scope="col">Icon</th>
                        <th scope="col">Active</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 1; ?>
                    <?php foreach( $submenu as $sm ) : ?>
                        <tr>
                            <th scope="row"><?= $i++; ?>.</th>
                            <td><?= $sm["title"]; ?></td>
                            <td><?= $sm["menu"]; ?></td>
                            <td><?= $sm["url"]; ?></td>
                            <td><?= $sm["icon"]; ?></td>
                            <td><?= $sm["is_active"]; ?></td>
                            <td>
                                <a href="" data-id="<?= $sm["id_user_sub_menu"]; ?>" class="badge badge-success edit-submenu-button" data-toggle="modal" data-target="#newSubmenuModal">Edit</a>
                                <a href="<?= base_url("menu/deleteSubmenu/"). $sm["id_user_sub_menu"]; ?>" class="badge badge-danger delete-button">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

        </div>
    </div>

    </div>
<!-- /.container-fluid -->

</div>
<!-- End of Main Content -->

<!-- Modal Box tambah -->
<!-- Button trigger modal -->
<!-- <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#newModal">
  Launch demo modal
</button> -->

<!-- Modal -->
<div class="modal fade" id="newSubmenuModal" tabindex="-1" aria-labelledby="newSubmenuModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="newSubmenuModalLabel">Add New Submenu</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="<?= base_url("menu/submenu"); ?>" method="POST">
        
            <div class="form-group">
                <input type="hidden" class="form-control" id="idUserSubmenu" name="id-user-sub-menu">
            </div>
            <div class="form-group">
                <input type="text" class="form-control" id="title" name="title" placeholder="Submenu title...">
            </div>
            <div class="form-group">
                <select id="idUserMenu" name="idUserMenu" class="form-control">
                    <option selected disabled value="placeholder">Select menu...</option>
                    <?php foreach( $menu as $m ) : ?>
                        <option value="<?= $m['id_user_menu']; ?>"><?= $m['menu']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <input type="text" class="form-control" id="url" name="url" placeholder="url submenu...">
            </div>
            <div class="form-group">
                <input type="text" class="form-control" id="icon" name="icon" placeholder="icon submenu...">
            </div>
            <div class="form-group">
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="isActive" name="isActive" value="1" checked>
                    <label class="custom-control-label" for="isActive">Active?</label>
                </div>
            </div>
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary" id="modalButton">Add</button>
      </div>

      </form>

    </div>
  </div>
</div>
<!-- End Modal Box -->