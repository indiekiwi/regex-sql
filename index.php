<?php
include 'php/Processor.php';
$processor = new Processor();
$hasResult = false;
$error = '';
try {
    if ($output = $processor->generate()) {
        $hasResult = true;
    }
} catch (Exception $e) {
    $error = $e->getMessage();
}
$c1 = 4;
$c2 = 12 - $c1;
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <link href="https://fonts.googleapis.com/css?family=PT+Mono&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <title>regex-sql</title>
</head>
<body>
<!--
    Navigation Bar
-->
<div class="row">
    <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
        <a class="navbar-brand" href="/">indie.kiwi</a>
        <button class="navbar-toggler"
                type="button"
                data-toggle="collapse"
                data-target="#navbarCollapse"
                aria-controls="navbarCollapse"
                aria-expanded="false"
                aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarCollapse">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="/regex-sql">
                        regex-sql<span class="sr-only">(current)</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="https://github.com/indiekiwi">Github</a>
                </li>
            </ul>
        </div>
    </nav>
</div>
<div class="row nav-space"></div>
<!--
    Container
-->
<div class="container">
    <!--
        Messages
    -->
    <?php
    if ($error) {
        ?>
        <div class="alert alert-danger alert-dismissible">
            <a href="#"
               class="close"
               data-dismiss="alert"
               aria-label="close">
                &times;
            </a>
            <?= $error ?>
        </div>
        <?php
    }
    ?>
    <!--
         Tab Buttons
    -->
    <ul class="nav nav-tabs" role="tablist">
        <li class="nav-item">
            <a class="nav-link <?= $hasResult ? '' : 'active' ?>" data-toggle="tab" href="#tab1">Tool</a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= $hasResult ? 'active' : '' ?>" data-toggle="tab" href="#tab2">Result</a>
        </li>
    </ul>
    <div class="tab-content">
        <!--
            Tab: Tool
        -->
        <div id="tab1" class="container tab-pane <?= $hasResult ? 'fade' : 'active' ?>"><br>
            <div class="row">
                <div class="col-md-12">
                    <form action="#" method="POST" role="form" enctype="multipart/form-data">
                        <div class="row">
                            <!--
                                DATA
                            -->
                            <div class="col-md-<?= $c1 ?>">
                                DATA
                                <div class="desc">
                                    <p>Each line in the <i>DATA</i> is parsed using the <i>REGEX</i> pattern</p>
                                    <p>Only <i>DATA</i> less
                                        than <?= number_format(Processor::RETAIN_DATA_MAX_LENGTH) ?>
                                        characters will be reloaded for reuse</p>
                                    <p>There is a character limit of <?= number_format(Processor::CHAR_LIMIT) ?>
                                        characters</p>
                                </div>
                            </div>
                            <div class="col-md-<?= $c2 ?>">
                                <div id="accordion-container" class="container">
                                    <div id="accordion">
                                        <div class="card">
                                            <div class="card-header">
                                                <a class="card-link" data-toggle="collapse"
                                                   href="#collapseDataString">
                                                    Raw Text
                                                </a>
                                            </div>
                                            <div id="collapseDataString" class="collapse show"
                                                 data-parent="#accordion">
                                                <div class="card-body">
                                                    <div class="form-group">
                                        <textarea class="form-control" name="direct-data"
                                                  rows="3"><?= $processor->retrieveData() ?>
                                        </textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card">
                                            <div class="card-header">
                                                <a class="collapsed card-link" data-toggle="collapse"
                                                   href="#collapseDataUpload">
                                                    Upload File
                                                </a>
                                            </div>
                                            <div id="collapseDataUpload" class="collapse" data-parent="#accordion">
                                                <div class="card-body">
                                                    <div class="form-group">
                                                        <input type="file" class="form-control-file text-center"
                                                               name="file"/>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row"><div class="col-md-12"><hr></div></div>
                        <div class="row">
                            <!--
                                TABLE NAME
                            -->
                            <div class="col-md-<?= $c1 ?>">
                                TABLE NAME
                                <div class="desc">
                                    <p>Custom table name for use in <i>MYSQL QUERY</i></p>
                                </div>
                            </div>
                            <div class="col-md-<?= $c2 ?>">
                                <div class="form-group">
                                    <input type="text"
                                           class="form-control"
                                           id="table-name"
                                           name="table-name"
                                           value="<?= $processor->retrieveTableName() ?: 'tb' ?>"
                                           placeholder="table_name"/>
                                </div>
                            </div>
                        </div>
                        <div class="row"><div class="col-md-12"><hr></div></div>
                        <div class="row">
                            <!--
                                REGEX
                            -->
                            <div class="col-md-<?= $c1 ?>">
                                REGEX
                                <div class="desc">
                                    <p>Regular expression to define the <i>DATA</i> structure</p>
                                    <p>Include any flags and the delimiters</p>
                                    <p>Use (round brackets) for creating <i>CAPTURE GROUPS</i></p>
                                </div>
                            </div>
                            <div class="col-md-<?= $c2 ?>">
                                <div class="form-group">
                                    <input type="text"
                                           class="form-control"
                                           id="regex-pattern"
                                           placeholder="/regex/i"
                                           name="regex-pattern"
                                           value="<?= $processor->retrieveRegexPattern() ?>"/>
                                </div>
                            </div>
                        </div>
                        <div class="row"><div class="col-md-12"><hr></div></div>
                        <div class="row">
                            <!--
                                MYSQL QUERY
                            -->
                            <div class="col-md-<?= $c1 ?>">
                                MYSQL QUERY
                                <div class="desc">
                                    <p>SELECT query to retrieve the result set</p>
                                </div>
                            </div>
                            <div class="col-md-<?= $c2 ?>">
                                <div class="form-group">
                    <textarea class="form-control"
                              name="sql-query"
                              placeholder="SELECT * FROM tb;"
                              rows="3"><?= $processor->retrieveSqlQuery() ?></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row"><div class="col-md-12"><hr></div></div>
                        <div class="row">
                            <!--
                                CAPTURE GROUPS
                            -->
                            <div class="col-md-<?= $c1 ?>">
                                CAPTURE GROUPS
                                <div class="desc">
                                    <p>Capture groups are AUTOMATICALLY added based on the <i>REGEX</i></p>
                                    <p>Defines MYSQL Columns for the <i>MYSQL QUERY</i></p>
                                </div>
                            </div>
                            <div class="col-md-<?= $c2 ?>">
                                <div class="container">
                                    <div class="row clearfix">
                                        <table class="table table-bordered table-hover"
                                               id="tab_logic"
                                               name="capture-as">
                                            <thead>
                                            <tr>
                                                <th class="text-center">Capture Group</th>
                                                <th class="text-center">AS</th>
                                                <th class="text-center">Definition</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php
                                            $columns = $processor->retrieveColumns();
                                            for ($i = 1; $i <= 100; $i++) { ?>
                                                <tr id='capture<?= $i ?>'>
                                                    <td>
                                                        $<?= $i ?>
                                                    </td>
                                                    <td>
                                                        <input type="text"
                                                               name='col<?= $i ?>'
                                                               value="<?= $columns[$i]['name'] ?? "col_$i" ?>"
                                                               class="form-control"/>
                                                    </td>
                                                    <td>
                                                        <input type="text"
                                                               name='type<?= $i ?>'
                                                               value='<?= $columns[$i]['type'] ?? "TEXT" ?>'
                                                               class="form-control"/>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                            <tr id='capture<?= $i ?>'></tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row"><div class="col-md-12"><hr></div></div>
                        <div class="row">
                            <!--
                                DESCRIPTION
                            -->
                            <div class="col-md-<?= $c1 ?>">
                                DESCRIPTION
                                <div class="desc">
                                    <p>Title for the result set</p>
                                </div>
                            </div>
                            <div class="col-md-<?= $c2 ?>">
                                <div class="form-group">
                                    <input type="text"
                                           class="form-control"
                                           id="description"
                                           name="description"
                                           value="<?= $processor->retrieveDescription() ?>"/>
                                </div>
                            </div>
                        </div>
                        <div class="row"><div class="col-md-12"><hr></div></div>
                        <div class="row">
                            <!--
                                SUBMIT
                            -->
                            <div class="col-md-<?= $c1 ?>"></div>
                            <div class="col-md-<?= $c2 ?>">
                                <button type="submit" class="btn btn-primary btn-block">
                                    Submit
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!--
            Tab: Results
        -->
        <div id="tab2" class="container tab-pane <?= $hasResult ? 'active' : 'fade' ?>">
            <br>
            <?php if ($hasResult) { ?>
                <div class="row">
                    <div class="col-md-12">
                        <h3><?= $processor->retrieveDescription() ?></h3>
                        <table id="outputTable" class="table table-bordered table-hover table-sm">
                            <thead>
                            <tr>
                                <?php foreach ($output as $row) {
                                    foreach ($row as $header => $v) { ?>
                                        <th><?= $header ?></th>
                                        <?php
                                    }
                                    break;
                                } ?>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($output as $row) { ?>
                                <tr>
                                    <?php foreach ($row as $v) { ?>
                                        <td>
                                            <?= $v ?>
                                        </td>
                                    <?php } ?>
                                </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</div>
<br>

<script src="js/script.js"></script>
</body>
</html>
