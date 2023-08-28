<?php require('../layout/header.php') ?>
<?php require('../layout/database.php') ?>

<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <h1 class="mt-4">Dashboard</h1>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item active">Dashboard</li>
            </ol>
            <?php require('../Otros/charts.php') ?>
        </div>
    </main>
</div>

<?php require('../layout/footer.php') ?>