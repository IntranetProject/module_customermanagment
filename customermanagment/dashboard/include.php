<div class="col-lg-3 col-md-6 col-sm-6 mb-3">
    <div class="card card-stats bg-light">
        <div class="card-body">
            <p class="category card-title">Customers</p>
            <?php
            $res = $db->simpleQuery("SELECT id FROM users");
            $accounts = $res->num_rows;
            ?>
            <h3 class="title"><?= $accounts ?></h3>
        </div>
        <div class="card-footer">
            <div class="stats">
                <a href="module.php?module=customermanagment/list.php"><i class="mdi mdi-calendar-range"></i> Overall</a>
            </div>
        </div>
    </div>
</div>
<div class="col-lg-3 col-md-6 col-sm-6 mb-3">
    <div class="card card-stats bg-light">
        <div class="card-body">
            <p class="category card-title">Customers</p>
            <?php
            $res = $db->simpleQuery("SELECT id FROM users WHERE registered_at >= now() - INTERVAL 30 DAY");
            $accounts = $res->num_rows;
            ?>
            <h3 class="title"><?= $accounts ?></h3>
        </div>
        <div class="card-footer">
            <div class="stats">
                <a href="module.php?module=customermanagment/list.php" ><i class="mdi mdi-calendar-range"></i> last 30 Days</a>
            </div>
        </div>
    </div>
</div>
<div class="col-lg-3 col-md-6 col-sm-6 mb-3">
    <div class="card card-stats bg-light">
        <div class="card-body">
            <p class="category card-title">Customers</p>
            <?php
            $res = $db->simpleQuery("SELECT id FROM users WHERE registered_at >= now() - INTERVAL 7 DAY");
            $accounts = $res->num_rows;
            ?>
            <h3 class="title"><?= $accounts ?></h3>
        </div>
        <div class="card-footer">
            <div class="stats">
                <a href="module.php?module=customermanagment/list.php"><i class="mdi mdi-calendar-range"></i> last 7 Days</a>
            </div>
        </div>
    </div>
</div>
<div class="col-lg-3 col-md-6 col-sm-6 mb-3">
        <div class="card card-stats bg-light">
            <div class="card-body">
                <p class="category card-title">Customers</p>
                <?php
$res = $db->simpleQuery("SELECT id FROM users WHERE registered_at >= now() - INTERVAL 1 DAY");
$accounts = $res->num_rows;
?>
                <h3 class="title"><?= $accounts ?></h3>
            </div>
            <div class="card-footer">
                <div class="stats">
                    <a href="module.php?module=customermanagment/list.php"><i class="mdi mdi-calendar-range"></i> last Day</a>
                </div>
            </div>
        </div>
    </div>