<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="css/style.css">
    <title>regex-sql</title>
</head>
<body>

<div class="row">
    <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
        <a class="navbar-brand" href="/">indie.kiwi</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse"
                aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarCollapse">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="/regex-sql">regex-sql<span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="https://github.com/indiekiwi">Github</a>
                </li>
            </ul>
        </div>
    </nav>
</div>

<div class="row nav-space"></div>

<div class="container">
    <?php
    include 'php/Processor.php';
    $processor = new Processor();
    try {
    if ($output = $processor->generate()) {
        ?>
        <table id="outputTable" class="table table-bordered table-hover"">
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
        <a href="javascript:history.back()" class="btn btn-link" role="button">
            Go Back
        </a>
        <?php
    } else {
    ?>

    <form action="#" method="POST" role="form" enctype="multipart/form-data">
        <div class="row">
            <div class="col-md-6">
                <label for="sql">
                    <h3>Data</h3>
                </label>
                <div id="accordion-container" class="container">
                    <div id="accordion">
                        <div class="card">
                            <div class="card-header">
                                <a class="collapsed card-link" data-toggle="collapse" href="#collapseDataUpload">
                                    Upload File
                                </a>
                            </div>
                            <div id="collapseDataUpload" class="collapse show" data-parent="#accordion">
                                <div class="card-body">
                                    <div class="form-group">
                                        <input type="file" class="form-control-file text-center" name="file"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header">
                                <a class="card-link" data-toggle="collapse" href="#collapseDataString">
                                    Paste
                                </a>
                            </div>
                            <div id="collapseDataString" class="collapse" data-parent="#accordion">
                                <div class="card-body">
                                    <div class="form-group">
                                        <textarea class="form-control" name="direct-data" rows="5"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <br>
                <div class="form-group">
                    <label for="regex-pattern">
                        <h3>Regex</h3>
                    </label>
                    <input type="text" class="form-control" id="regex-pattern" placeholder="/regex/i"
                           name="regex-pattern"/>
                </div>
                <div class="form-group">
                    <label for="sql">
                        <h3>SQL Query</h3>
                    </label>
                    <textarea class="form-control" name="sql-query" placeholder="SELECT col_1, count(*) as count
FROM table_1
GROUP BY col_1;"
                              rows="5" name="sql"></textarea>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="table-name">
                        <h3>Table Name</h3>
                    </label>
                    <input type="text" class="form-control" id="table-name" name="table-name" value="table_1"/>
                </div>
                <label for="capture-as">
                    <h3>Capture Groups</h3>
                </label>
                <div class="container">

                    <div class="row clearfix">
                        <table class="table table-bordered table-hover" id="tab_logic" name="capture-as">
                            <thead>
                            <tr>
                                <th class="text-center">Capture Group</th>
                                <th class="text-center">As</th>
                                <th class="text-center">Definition</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr id='capture1'>
                                <td>$1</td>
                                <td><input type="text" name='col1' value="col_1" class="form-control"/></td>
                                <td><input type="text" name='type1' value='VARCHAR(255)' class="form-control"/></td>
                            </tr>
                            <tr id='capture2'>
                                <td>$2</td>
                                <td><input type="text" name='col2' value="col_2" class="form-control"/></td>
                                <td><input type="text" name='type2' value='VARCHAR(255)' class="form-control"/></td>
                            </tr>
                            <tr id='capture3'>
                                <td>$3</td>
                                <td><input type="text" name='col3' value="col_3" class="form-control"/></td>
                                <td><input type="text" name='type3' value='VARCHAR(255)' class="form-control"/></td>
                            </tr>
                            <tr id='capture4'></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <button type="button" id="add_row" class="btn btn-success btn-block">
                            Add Capture Group
                        </button>
                    </div>
                    <div class="col-md-6">
                        <button type="button" id="delete_row" class="btn btn-danger btn-block">
                            Remove Capture Group
                        </button>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary btn-block">
                            Submit
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<?php }
} catch (Exception $e) { ?>
    <div class="alert alert-danger" role="alert">
        <?= $e->getMessage() ?>
    </div>
    <a href="javascript:history.back()" class="btn btn-link" role="button">
        Go Back
    </a>
<?php } ?>

<div class="col-md-2"></div>

<script src="js/script.js"></script>
</body>
</html>
