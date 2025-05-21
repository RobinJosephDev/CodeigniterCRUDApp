<!DOCTYPE html>
<html>

<head>
    <title>Add User - CodeIgniter Tutorial</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
</head>

<body>
    <div class="container">
        <div class="col-md-6 col-md-offset-3">

            <h3 class="text-center">Add User</h3>

            <?php if ($this->session->flashdata('success')) : ?>
                <div class="alert alert-success"><?= $this->session->flashdata('success'); ?></div>
            <?php endif; ?>

            <?= validation_errors('<div class="alert alert-danger">', '</div>'); ?>
            <?php if (isset($upload_error)) : ?>
                <div class="alert alert-danger"><?= $upload_error; ?></div>
            <?php endif; ?>

            <?= form_open_multipart('register/add'); ?>

            <?php
            $fields = [
                'username' => 'Username',
                'email'      => 'Email',
                'password'   => 'Password',
                'mobile'     => 'Mobile'
            ];

            foreach ($fields as $name => $label) :
                $type = ($name === 'password') ? 'password' : (($name === 'email') ? 'email' : 'text');
            ?>
                <div class="form-group">
                    <label><?= $label ?></label>
                    <input type="<?= $type ?>" class="form-control" name="<?= $name ?>" value="<?= set_value($name) ?>">
                </div>
            <?php endforeach; ?>

            <div class="form-group">
                <label>Profile Picture</label>
                <input type="file" name="profile_pic" class="form-control">
            </div>

            <div class="form-group text-center">
                <button type="submit" class="btn btn-primary btn-lg">Add User</button>
                <a href="<?= base_url('register') ?>" class="btn btn-default btn-lg">Back</a>
            </div>

            <?= form_close(); ?>

        </div>
    </div>
</body>

</html>