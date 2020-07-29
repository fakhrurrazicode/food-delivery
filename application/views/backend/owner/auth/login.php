<div class="card o-hidden border-0 shadow-lg my-5">
    <div class="card-body p-0">
        <!-- Nested Row within Card Body -->
        <div class="row">


            <div class="col-lg-6 d-none d-lg-block bg-login-image"></div>
            <div class="col-lg-6">
                <div class="p-5">
                    <div class="text-center">
                        <h1 class="h4 text-gray-900 mb-4">Onwner Login</h1>
                    </div>
                    

                    <form method="POST" action="<?php echo base_url('backend/owner/auth/submit_login') ?>">

                        <div class="form-group">
                            <label for="email">Email address</label>
                            <input type="email" class="form-control <?php echo form_error('email') ? 'is-invalid' : '' ?>" id="email" name="email" value="<?php echo set_value('email') ?>">
                            <?php echo form_error('email', '<div class="invalid-feedback">', '</div>'); ?>
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" class="form-control <?php echo form_error('password') ? 'is-invalid' : '' ?>" id="password" name="password">
                            <?php echo form_error('password', '<div class="invalid-feedback">', '</div>'); ?>
                        </div>

                        <button type="submit" class="btn btn-primary btn-block btn-lg">Login</button>

                    </form>


                </div>
            </div>
        </div>
    </div>
</div>

