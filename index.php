<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"
          integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    <title>Aggregate Data</title>
</head>
<body class="text-center">
<div class="container-fluid">
    <div class="row">
        <div class="col-md-4">
        </div>
        <div class="col-md-4">
            <form action="regex_result.php" method="POST" role="form" enctype="multipart/form-data">

                <div class="form-group">

                    <label for="regex-pattern">
                        Regular Expression
                    </label>
                    <input type="text" class="form-control" id="regex-pattern" name="regex-pattern"/>
                </div>

                <div class="form-group">

                    <label for="output-pattern">
                        Output Pattern
                    </label>
                    <input type="text" class="form-control" id="output-pattern" name="output-pattern"/>
                </div>

                <div class="form-group">
                    <label for="direct-data">
                        Data
                    </label>
                    <textarea class="form-control" id="data" name="direct-data" rows="5"></textarea>
                </div>

                <div class="form-group">
                    <label for="file-data">
                        Upload
                    </label>
                    <input type="file" class="form-control-file text-center" id="file-data" name="file"/>
                </div>

                <button type="submit" class="btn btn-primary">
                    Submit
                </button>
            </form>
        </div>
        <div class="col-md-4">
        </div>
    </div>
</div>
<!--<form action="/regex_result.php" method="post" enctype="multipart/form-data">-->
<!--    Pattern<input type="text" name="pattern"><br>-->
<!--    Contents<textarea rows="4" cols="50" name="contents"></textarea><br>-->
<!--    or upload<input type="file" name="contents-file" id="up"><br>-->
<!--    <input type="submit" value="Submit">-->
<!--</form>-->
</body>
</html>

