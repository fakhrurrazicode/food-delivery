

<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Store</h1>
    </div>
    <!-- Content Row -->
    <div class="row justify-content-center">
        <div class="col-sm-6">
            <div class="card">
                <div class="card-header">
                <?php if(isset($store)): ?>
                    <h1 class="h5 mb-0 text-gray-800">Edit Store</h1>
                <?php else: ?>
                    <h1 class="h5 mb-0 text-gray-800">Add New Store</h1>
                <?php endif ?>
                </div>
                <div class="card-body">

                    <?php if(isset($store)): ?>
                        <form method="POST" action="<?php echo base_url('backend/owner/store/update/'. $store->id) ?>" enctype="multipart/form-data">
                    <?php else: ?>
                        <form method="POST" action="<?php echo base_url('backend/owner/store/store') ?>" enctype="multipart/form-data">
                    <?php endif ?>


                        <div class="form-group" style="display: none;">
                            <label for="user_id">User Owner</label>
                            <select class="form-control <?php echo form_error('user_id') ? 'is-invalid' : '' ?>" id="user_id" name="user_id" value="">
                                <?php 
                                $query = $this->db->query("SELECT * FROM users WHERE users.id");
                                $users = $query->result();
                                ?>

                                <option value=""></option>
                                <?php foreach($users as $user): ?>
                                    <option <?php echo set_value('user_id', isset($store) ? $store->user_id : null) == $user->id ? 'selected=""' : '' ?> value="<?php echo $user->id ?>"><?php echo $user->name ?></option>
                                <?php endforeach ?>
                            </select>
                            <?php echo form_error('user_id', '<div class="invalid-feedback">', '</div>'); ?>
                        </div>

                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" class="form-control <?php echo form_error('name') ? 'is-invalid' : '' ?>" id="name" name="name" value="<?php echo set_value('name', isset($store) ? $store->name : null) ?>">
                            <?php echo form_error('name', '<div class="invalid-feedback">', '</div>'); ?>
                        </div>
                        
                        <div class="row">
                            <div class="col-sm-8">
                                <div class="form-group">
                                    <label for="banner_image">Banner Image</label>
                                    <input type="file" class="form-control <?php echo form_error('banner_image') ? 'is-invalid' : '' ?>" id="banner_image" name="banner_image">
                                    <?php echo form_error('banner_image', '<div class="invalid-feedback">', '</div>'); ?>
                                    <?php if(isset($store)): ?>
                                        <p class="my-2">Abaikan jika tidak ingin mengganti gambar.</p>
                                    <?php endif ?>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <?php if(isset($store) and $store->banner_image): ?>
                                    <img src="<?php echo base_url($store->banner_image) ?>" style="width: 100%;" alt="">
                                <?php endif ?>
                            </div>
                        </div>
                        

                        <div class="form-group">
                            <label for="address">Address</label>
                            <textarea rows="5" class="form-control <?php echo form_error('address') ? 'is-invalid' : '' ?>" id="address" name="address"><?php echo set_value('address', isset($store) ? $store->address : null) ?></textarea>
                            <?php echo form_error('address', '<div class="invalid-feedback">', '</div>'); ?>
                        </div>

                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="lat">Lat</label>
                                    <input type="text" class="form-control <?php echo form_error('lat') ? 'is-invalid' : '' ?>" id="lat" name="lat" value="<?php echo set_value('lat', isset($store) ? $store->lat : null) ?>">
                                    <?php echo form_error('lat', '<div class="invalid-feedback">', '</div>'); ?>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="lng">Lng</label>
                                    <input type="text" class="form-control <?php echo form_error('lng') ? 'is-invalid' : '' ?>" id="lng" name="lng" value="<?php echo set_value('lng', isset($store) ? $store->lng : null) ?>">
                                    <?php echo form_error('lng', '<div class="invalid-feedback">', '</div>'); ?>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <div class="text-right">
                            <!-- <a href="<?php // echo base_url('backend/owner/store') ?>" class="btn btn-secondary"><i class="fa fa-times"></i> Cancel</a> -->
                            <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Update</button>
                        </div>
                        

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>