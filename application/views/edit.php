<!DOCTYPE html>
<html>

<head>
    <title>Edit User</title>
</head>

<body>
    <h2>Update User</h2>

    <?php echo form_open_multipart('register/update/' . $user['id']); ?>

    <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>"
        value="<?= $this->security->get_csrf_hash(); ?>" />
    <label>First Name:</label><br>
    <input type="text" name="first_name" value="<?= $user['first_name'] ?? '' ?>" required><br><br>

    <label>Last Name:</label><br>
    <input type="text" name="last_name" value="<?= $user['last_name'] ?? '' ?>" required><br><br>

    <label>Address:</label><br>
    <input type="text" name="address" value="<?= $user['address'] ?? '' ?>" required><br><br>

    <label>Email:</label><br>
    <input type="email" name="email" value="<?= $user['email'] ?? '' ?>" required><br><br>
    <label>Password:</label><br>
    <input type="password" name="password" value="<?= $user['password'] ?? '' ?>" required><br><br>

    <label>Mobile:</label><br>
    <input type="text" name="mobile" value="<?= $user['mobile'] ?? '' ?>" required><br><br>

    <input type="hidden" name="existing_pic" value="<?= $user['profile_pic'] ?? '' ?>">

    <?php if (!empty($user['profile_pic'])) : ?>
        <img src="<?= base_url('uploads/' . $user['profile_pic']) ?>" width="100" alt="Profile Picture"><br><br>
    <?php endif; ?>

    <div class="form-group">
        <label for="profile_pic">Profile Picture</label>
        <input type="file" name="profile_pic" class="form-control">
    </div>

    <button type="submit">Update</button>
    <?php echo form_close(); ?>

</body>

</html>