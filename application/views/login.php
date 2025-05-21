<!DOCTYPE html>
<html>

<head>
    <title>Login</title>
</head>

<body>

    <h2>Login</h2>

    <?php if ($this->session->flashdata('error')): ?>
        <div style="color:red;">
            <?= $this->session->flashdata('error') ?>
        </div>
    <?php endif; ?>

    <?= form_open('login/process') ?>

    <p>
        <label for="username">Username</label><br>
        <input type="text" name="username" value="<?= set_value('username') ?>">
        <?= form_error('username') ?>
    </p>

    <p>
        <label for="password">Password</label><br>
        <input type="password" name="password">
        <?= form_error('password') ?>
    </p>

    <!-- CSRF token (CodeIgniter generates this automatically if enabled) -->
    <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>"
        value="<?= $this->security->get_csrf_hash(); ?>" />

    <p>
        <button type="submit">Login</button>
    </p>

    <?= form_close() ?>

</body>

</html>