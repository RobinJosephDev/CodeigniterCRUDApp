<!DOCTYPE html>
<html>

<head>
    <title>Edit User</title>
    <style>
        .error {
            color: red;
        }

        .success {
            color: green;
        }
    </style>
</head>

<body>
    <h2>Update User</h2>

    <?php if ($this->session->flashdata('success')) : ?>
        <div class="success"><?= $this->session->flashdata('success'); ?></div>
    <?php endif; ?>

    <?= validation_errors('<div class="error">', '</div>'); ?>

    <?php if (isset($upload_error)) : ?>
        <div class="error"><?= $upload_error; ?></div>
    <?php endif; ?>

    <?= form_open_multipart('register/update/' . $user['id']); ?>

    <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>"
        value="<?= $this->security->get_csrf_hash(); ?>" />

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
        <label><?= $label ?>:</label><br>
        <input type="<?= $type ?>" name="<?= $name ?>"
            value="<?= $name === 'password' ? '' : set_value($name, $user[$name]) ?>" required><br><br>
    <?php endforeach; ?>

    <label>Current Picture:</label><br>
    <?php if (!empty($user['profile_pic'])) : ?>
        <img src="<?= base_url('uploads/' . $user['profile_pic']) ?>" width="100"><br>
    <?php endif; ?>
    <input type="file" name="profile_pic"><br><br>
    <input type="hidden" name="existing_pic" value="<?= $user['profile_pic'] ?>">

    <button type="submit">Update</button>

    <?= form_close(); ?>
</body>

</html>