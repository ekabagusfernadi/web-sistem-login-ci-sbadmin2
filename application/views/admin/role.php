<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800"><?= $page_name; ?></h1>

    <div class="row">
        <div class="col-lg-6">

        <a href="" class="btn btn-primary mb-3" data-toggle="modal" data-target="#newRoleModal" id="addRoleButton">Add New Role</a>

        <!-- pesan -->
        <?= form_error("role", '<div class="alert alert-danger alert-dismissible fade show" role="alert">',
        '<button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>') ?>
        <?= $this->session->flashdata("message"); ?>
            
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th scope="col">No.</th>
                        <th scope="col">Role</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 1; ?>
                    <?php foreach( $role as $r ) : ?>
                        <tr>
                            <th scope="row"><?= $i++; ?>.</th>
                            <td><?= $r["role"]; ?></td>
                            <td>
                                <a href="<?= base_url("admin/roleaccess/") . $r['role_id']; ?>" class="badge badge-warning" >Access</a>
                                <a href="" data-id="<?= $r["role_id"]; ?>" class="badge badge-success edit-role-button" data-toggle="modal" data-target="#newRoleModal">Edit</a>
                                <a href="<?= base_url("admin/deleteRole/"). $r["role_id"]; ?>" class="badge badge-danger delete-button">Delete</a>
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
<div class="modal fade" id="newRoleModal" tabindex="-1" aria-labelledby="newRoleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="newRoleModalLabel">Add New Role</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="<?= base_url("admin/role"); ?>" method="POST">
        
            <div class="form-group">
                <input type="text" class="form-control" id="role_id" name="role_id">
            </div>
            <div class="form-group">
                <input type="text" class="form-control" id="role" name="role" placeholder="Role name...">
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