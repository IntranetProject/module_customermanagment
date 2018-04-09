<?php

/**
 * Created by PhpStorm.
 * User: Bennet
 * Date: 1/16/2018
 * Time: 9:45 PM
 */
class Paginator
{

    private $_conn;
    private $_limit;
    private $_page;
    private $_query;
    private $_total;

    public function __construct($conn, $query)
    {

        $this->_conn = $conn;
        $this->_query = $query;

        $rs = $this->_conn->query($this->_query);
        $this->_total = $rs->num_rows;

    }

    public function getData($limit = 10, $page = 1)
    {

        $this->_limit = $limit;
        $this->_page = $page;

        if ($this->_limit == 'all') {
            $query = $this->_query;
        } else {
            $query = $this->_query . " LIMIT " . (($this->_page - 1) * $this->_limit) . " , " . $this->_limit;
        }
        $rs = $this->_conn->query($query);
        $results = [];
        while ($row = $rs->fetch_assoc()) {
            $results[] = $row;
        }

        $result = new stdClass();
        $result->page = $this->_page;
        $result->limit = $this->_limit;
        $result->total = $this->_total;
        $result->data = $results;

        return $result;
    }

    public function createLinks($links, $list_class)
    {
        if ($this->_limit == 'all') {
            return '';
        }

        $last = ceil($this->_total / $this->_limit);

        $start = (($this->_page - $links) > 0) ? $this->_page - $links : 1;
        $end = (($this->_page + $links) < $last) ? $this->_page + $links : $last;

        $html = '<ul class="' . $list_class . '">';

        $class = ($this->_page == 1) ? "disabled" : "";
        $html .= '<li class="' . $class . '"><a style="pointer-events: none; cursor: default;" href="module.php?module=customermanagment/list.php&limit=' . $this->_limit . '&page=' . ($this->_page - 1) . '">&laquo;</a></li>';

        if ($start > 1) {
            $html .= '<li><a href="module.php?module=customermanagment/list.php&limit=' . $this->_limit . '&page=1">1</a></li>';
            $html .= '<li class="disabled"><span>...</span></li>';
        }

        for ($i = $start; $i <= $end; $i++) {
            $class = ($this->_page == $i) ? "active" : "";
            $html .= '<li class="' . $class . '"><a href="module.php?module=customermanagment/list.php&limit=' . $this->_limit . '&page=' . $i . '">' . $i . '</a></li>';
        }

        if ($end < $last) {
            $html .= '<li class="disabled"><span>...</span></li>';
            $html .= '<li><a href="module.php?module=customermanagment/list.php&limit=' . $this->_limit . '&page=' . $last . '">' . $last . '</a></li>';
        }

        $class = ($this->_page == $last) ? "disabled" : "";
        $html .= '<li class="' . $class . '"><a style="pointer-events: none; cursor: default;" href="module.php?module=customermanagment/list.php&limit=' . $this->_limit . '&page=' . ($this->_page + 1) . '">&raquo;</a></li>';

        $html .= '</ul>';

        return $html;
    }
}

$limit = (isset($_GET['limit'])) ? $_GET['limit'] : 25;
$page = (isset($_GET['page'])) ? $_GET['page'] : 1;
$links = (isset($_GET['links'])) ? $_GET['links'] : 7;

if (isset($params->method)) {
    if ($params->method == "search" && isset($_POST['userid'])) {
        $res = $db->simpleQuery("SELECT _id FROM users WHERE id='" . $db->getConnection()->escape_string($_POST['userid']) . "'");
        if ($res->num_rows >= 1) {
            $db->redirect("module.php?module=customermanagment/profile.php&params=user|" . $_POST['userid']);
            die();
        } else {
            echo "User not found!";
        }
    }
}

$query = "SELECT _id, id, email, permissions, firstname, lastname, registered_at FROM users";

$db->connect();
$Paginator = new Paginator($db->getConnection(), $query);
$results = $Paginator->getData($limit, $page);
?>
<style>
    .d-inline * {
        width: calc(100% / 3);
        float: left;
        text-align: center !important;
        margin-left: 25%;
    }
</style>
<div class="inner">
    <div class="row col-md-10 offset-md-1 mt-3 bg-light">

        <div class="card col-md-12 bg-light mt-3">
            <div class="card-body text-center">
                <!--<form action="module.php?module=customermanagment/list.php&params=method|search" method="post">
                    <div class="col-md-4">
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="<?= $module->getMessage("list_search_field") ?>"name="userid">
                            <span class="input-group-btn">
                                <button class="btn btn-default" type="submit" style="border-radius: 5px;"><?= $module->getMessage("list_search_submit") ?></button>
                            </span>
                        </div>
                    </div>
                </form>-->
                    <table class="table">
                        <thead class="text-primary">
                        <tr>
                            <th><?= $module->getMessage("list_id") ?></th>
                            <th><?= $module->getMessage("list_realid") ?></th>
                            <th><?= $module->getMessage("list_email") ?></th>
                            <th><?= $module->getMessage("list_permission") ?></th>
                            <th><?= $module->getMessage("list_firstname") ?></th>
                            <th><?= $module->getMessage("list_lastname") ?></th>
                            <th><?= $module->getMessage("list_registerdate") ?></th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php for ($i = 0; $i < count($results->data); $i++) : ?>
                            <tr>
                                <td><?= $results->data[$i]['_id'] ?></td>
                                <td><?= $results->data[$i]['id'] ?></td>
                                <td><?= $results->data[$i]['email'] ?></td>
                                <td><?= $results->data[$i]['permissions'] ?></td>
                                <td><?= $results->data[$i]['firstname'] ?></td>
                                <td><?= $results->data[$i]['lastname'] ?></td>
                                <td><?= $results->data[$i]['registered_at'] ?></td>
                                <td>
                                    <a href="module.php?module=customermanagment/profile.php&params=user|<?= $results->data[$i]['id'] ?>"><i
                                                class="mdi mdi-account-box mdi-24px"></i></a>
                                </td>
                            </tr>
                        <?php endfor; ?>
                        </tbody>
                    </table>
                <div class="col-md-2 offset-md-5" style="text-align: center;">
                    <?php echo $Paginator->createLinks($links, 'pagination pagination-sm text-center d-inline'); ?>
                </div>
            </div>
        </div>
    </div>
</div>
