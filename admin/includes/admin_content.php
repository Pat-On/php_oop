<div class="container-fluid">

    <!-- Page Heading -->
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">
                Blank Page
                <small>Subheading</small>



                <?php
                // $user = new User();

                // $user->username = "static_user";
                // $user->password = "static";
                // $user->firstname = "first_name";
                // $user->lastname = "last_name";

                // $user->create();


                $user = User::find_user_by_id(1);
                $user->lastname = "AfterUpdate";
                $user->update();


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