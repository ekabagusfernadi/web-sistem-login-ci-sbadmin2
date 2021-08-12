<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800"><?= $page_name; ?></h1>

    <div class="row">
        <div class="col-lg-6">

        <a href="" class="btn btn-primary mb-3" data-toggle="modal" data-target="#newMenuModal" id="addMenuButton">Add New Menu</a>

        <!-- pesan -->
        <?= form_error("menu", '<div class="alert alert-danger alert-dismissible fade show" role="alert">',
        '<button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>') ?>
        <?= $this->session->flashdata("message"); ?>
            
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th scope="col">No.</th>
                        <th scope="col">Menu</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 1; ?>
                    <?php foreach( $menu as $m ) : ?>
                        <tr>
                            <th scope="row"><?= $i++; ?>.</th>
                            <td><?= $m["menu"]; ?></td>
                            <td>
                                <a href="" data-id="<?= $m["id_user_menu"]; ?>" class="badge badge-success edit-menu-button" data-toggle="modal" data-target="#newMenuModal">Edit</a>
                                <a href="<?= base_url("menu/delete/"). $m["id_user_menu"]; ?>" class="badge badge-danger delete-button">Delete</a>
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
<div class="modal fade" id="newMenuModal" tabindex="-1" aria-labelledby="newMenuModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="newMenuModalLabel">Add New Menu</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="<?= base_url("menu"); ?>" method="POST">
        
            <div class="form-group">
                <input type="hidden" class="form-control" id="id-user-menu" name="id-user-menu">
            </div>
            <div class="form-group">
                <input type="text" class="form-control" id="menu" name="menu" placeholder="Menu name...">
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