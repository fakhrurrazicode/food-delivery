

<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">User</h1>
    </div>
    <!-- Content Row -->
    <div class="row justify-content-center">
        <div class="col-sm-6">
            <div class="card">
                <div class="card-header">
                    <?php if(isset($user)): ?>
                        <h1 class="h5 mb-0 text-gray-800">Edit User</h1>
                    <?php else: ?>
                        <h1 class="h5 mb-0 text-gray-800">Add New User</h1>
                    <?php endif ?>
                </div>
                <div class="card-body">

                    <?php if(isset($user)): ?>
                        <form method="POST" action="<?php echo base_url('backend/admin/user/update/'. $user->id) ?>" enctype="multipart/form-data">
                    <?php else: ?>
                        <form method="POST" action="<?php echo base_url('backend/admin/user/store') ?>" enctype="multipart/form-data">
                    <?php endif ?>

                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" class="form-control <?php echo form_error('name') ? 'is-invalid' : '' ?>" id="name" name="name" value="<?php echo set_value('name', isset($user) ? $user->name : null) ?>">
                            <?php echo form_error('name', '<div class="invalid-feedback">', '</div>'); ?>
                        </div>

                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control <?php echo form_error('email') ? 'is-invalid' : '' ?>" id="email" name="email" value="<?php echo set_value('email', isset($user) ? $user->email : null) ?>">
                            <?php echo form_error('email', '<div class="invalid-feedback">', '</div>'); ?>
                        </div>

                        <?php if(!isset($user)): ?>

                            <div class="form-group">
                                <label for="password">Password</label>
                                <input type="password" class="form-control <?php echo form_error('password') ? 'is-invalid' : '' ?>" id="password" name="password" value="">
                                <?php echo form_error('password', '<div class="invalid-feedback">', '</div>'); ?>
                            </div>

                        <?php endif ?>

                        <hr>

                        <div class="text-right">
                            <a href="<?php echo base_url('backend/admin/user') ?>" class="btn btn-secondary"><i class="fa fa-times"></i> Cancel</a>
                            <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Save</button>
                        </div>
                        

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>