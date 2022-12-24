<div class="container-fluid">

    <!-- Page Heading -->
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">
                Blank Page
                <small>Subheading</small>



                <?php
                // $user = new User();
                $result = User::find_all_users(); // better way is to use static :>

                while ($row = mysqli_fetch_array($result))
                    echo "<br>" . $row["username"];
                ?>

                <?php
                // $user = new User();

                $found_user = User::find_user_by_id(2); // better way is to use static :>

                echo "<br>" . $found_user['username']
                ?>

            </h1>
            <ol class="breadcrumb">
                <li>
                    <i class="fa fa-dashboard"></i> <a href="index.html">Dashboard</a>
                </li>
                <li class="active">
                    <i class="fa fa-file"></i> Blank Page
                </li>
            </ol>
        </div>
    </div>
    <!-- /.row -->

</div>