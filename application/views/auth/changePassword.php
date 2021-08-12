<div class="container">

    <!-- Outer Row -->
    <div class="row justify-content-center">

        <div class="col-lg-7">

            <div class="card o-hidden border-0 shadow-lg my-5">
                <div class="card-body p-0">
                    <!-- Nested Row within Card Body -->
                    <div class="row">
                        <!-- <div class="col-lg-6 d-none d-lg-block bg-login-image"></div> -->
                        <div class="col-lg">
                            <div class="p-5">
                                <div class="text-center">
                                    <h1 class="h4 text-gray-900">Change password for</h1>
                                    <h6 class="mb-4"><?= $this->session->userdata("reset_email"); ?></h6>
                                </div>

                                <!-- flashdata berhasil daftar -->
                                <?= $this->session->flashdata("message"); ?>

                                <form class="user" action="<?= base_url("auth/changePassword"); ?>" method="post">
                                    <div class="form-group">
                                        <input type="password" class="form-control form-control-user"
                                            id="password1"
                                            placeholder="Enter new password" name="password1">
                                        <?= form_error("password1", '<small class="text-danger pl-2">', '</small>'); ?>
                                    </div>
                                    <div class="form-group">
                                        <input type="password" class="form-control form-control-user"
                                            id="password2"
                                            placeholder="Repeat password" name="password2">
                                        <?= form_error("password2", '<small class="text-danger pl-2">', '</small>'); ?>
                                    </div>
                                    
                                    <button type="submit" class="btn btn-primary btn-user btn-block">
                                        Change Password
                                    </button>
                            
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>

</div>

    