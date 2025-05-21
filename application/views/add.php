<!DOCTYPE html>
<html>

<head>
    <title>Add User - CodeIgniter Tutorial</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
</head>

<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-md-offset-3">

                <h3 class="text-center">Add User</h3>
                <?php if ($this->session->flashdata('success')) : ?>
                    <div class="alert alert-success">
                        <?= $this->session->flashdata('success'); ?>
                    </div>
                <?php endif; ?>

                <!-- Show validation errors -->
                <?php if (validation_errors()) : ?>
                    <div class="alert alert-danger">
                        <?php echo validation_errors(); ?>
                    </div>
                <?php endif; ?>

                <?php if (isset($error)) : ?>
                    <div class="alert alert-danger">
                        <?php echo $error; ?>
                    </div>
                <?php endif; ?>

                <?php echo form_open_multipart('register/add'); ?>

                <div class="form-group">
                    <label for="txtFname">First Name</label>
                    <input type="text" class="form-control" name="txtFname" value="<?php echo set_value('txtFname'); ?>">
                </div>

                <div class="form-group">
                    <label for="txtLname">Last Name</label>
                    <input type="text" class="form-control" name="txtLname" value="<?php echo set_value('txtLname'); ?>">
                </div>

                <div class="form-group">
                    <label for="txtAddress">Address</label>
                    <input type="text" class="form-control" name="txtAddress" value="<?php echo set_value('txtAddress'); ?>">
                </div>

                <div class="form-group">
                    <label for="txtEmail">Email</label>
                    <input type="email" class="form-control" name="txtEmail" value="<?php echo set_value('txtEmail'); ?>">
                </div>

                <div class="form-group">
                    <label for="txtPassword">Password</label>
                    <input type="password" class="form-control" name="txtPassword">
                </div>

                <div class="form-group">
                    <label for="txtMobile">Mobile</label>
                    <input type="text" class="form-control" name="txtMobile" value="<?php echo set_value('txtMobile'); ?>">
                </div>

                <div class="form-group">
                    <label for="profile_pic">Profile Picture</label>
                    <input type="file" name="profile_pic" class="form-control">
                </div>

                <div class="form-group text-center">
                    <button type="submit" name="btnadd" class="btn btn-primary btn-lg">Add User</button>
                </div>

                <?php echo form_close(); ?>

                <div class="form-group text-center">
                    <a href="<?= base_url('register') ?>" class="btn btn-default btn-lg">Back to Users</a>
                </div>

            </div>
        </div>
    </div>
</body>

</html>