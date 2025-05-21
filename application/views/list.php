<html>
<head>
    <title>codeigniter Tutorial</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col-md-2"></div>
            <div class="col-md-8">

                <h2>List of Registered users</h2>
                <table class="table">
                    <tr>
                        <td colspan="5" align="right"><a href="<?php echo base_url(); ?>register/add">Add</a></td>
                        <td colspan="5" align="right"><a href="<?= site_url('login/logout') ?>">Logout</a></td>
                    </tr>
                    <tr>
                        <td>Username</td>
                        <td>Email</td>
                        <td>Mobile</td>
                        <td>Profile Pic</td>
                        <td>Action</td>
                    </tr>
                    <?php
                    foreach ($register_detail as $rg) {
                    ?>
                        <tr>
                            <td><?php echo $rg['username']; ?></td>
                            <td><?php echo $rg['email']; ?></td>
                            <td><?php echo $rg['mobile']; ?></td>
                            <td>
                                <img src="<?php echo base_url('uploads/' . $rg['profile_pic']); ?>" width="60" height="60" style="object-fit: cover;" alt="Profile Pic">
                            </td>
                            <td><a href="<?php echo base_url(); ?>register/edit/<?php echo $rg['id']; ?>">Edit</a> <a href="<?php echo base_url(); ?>register/delete/<?php echo $rg['id']; ?>">Delete</a></td>
                        </tr>
                    <?php
                    }
                    ?>
                </table>
                <!-- Pagination links -->
                <div class="text-center">
                    <?php echo $pagination_links; ?>
                </div>
            </div>


            <div class="col-md-2"></div>
        </div>
    </div>
</body>

</html>