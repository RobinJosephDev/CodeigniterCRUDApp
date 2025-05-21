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
        <div class="success">
            <?= $this->session->flashdata('success'); ?>
        </div>
    <?php endif; ?>

    <?php if (validation_errors()) : ?>
        <div class="error">
            <?= validation_errors(); ?>
        </div>
    <?php endif; ?>

    <?php if (isset($upload_error)) : ?>
        <div class="error">
            <?= $upload_error; ?>
        </div>
    <?php endif; ?>

    <?= form_open_multipart('register/update/' . $user['id']); ?>

    <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>"
        value="<?= $this->security->get_csrf_hash(); ?>" />

    <label>First Name:</label><br>
    <input type="text" name="first_name" value="<?= set_value('first_name', $user['first_name']) ?>" required><br><br>

    <label>Last Name:</label><br>
    <input type="text" name="last_name" value="<?= set_value('last_name', $user['last_name']) ?>" required><br><br>

    <label>Address:</label><br>
    <input type="text" name="address" value="<?= set_value('address', $user['address']) ?>" required><br><br>

    <label>Email:</label><br>
    <input type="email" name="email" value="<?= set_value('email', $user['email']) ?>" required><br><br>

    <label>Password:</label><br>
    <input type="password" name="password" required><br><br>

    <label>Mobile:</label><br>
    <input type="text" name="mobile" value="<?= set_value('mobile', $user['mobile']) ?>" required><br><br>

    <label>Current Picture:</label><br>
    <?php if (!empty($user['profile_pic'])) : ?>
        <img src="<?= base_url('uploads/' . $user['profile_pic']) ?>" width="100"><br>
    <?php endif; ?>
    <input type="file" name="profile_pic"><br><br>

    <input type="hidden" name="existing_pic" value="<?= $user['profile_pic'] ?>">

    <button type="submit">Update</button>
    </form>
</body>

</html>