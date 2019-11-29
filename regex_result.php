<?php

error_reporting(E_ALL);
foreach (
    [
        'display_errors'            => 1,
        'display_startup_errors'    => 1,
        'upload_max_filesize'       => '10M',
        'post_max_size'             => '10M',
        'max_input_time'            => 300,
        'max_execution_time'        => 300,
    ] as $var => $val
) {
    ini_set($var, $val);
}
$regexPattern = htmlspecialchars($_POST['regex-pattern']);
$outputPattern = htmlspecialchars($_POST['output-pattern']);
$directData = htmlspecialchars($_POST['direct-data']);
$fileData = isset($_FILES['file']['tmp_name'])
    ? file_get_contents($_FILES['file']['tmp_name'])
    : '';

$data = $directData ?: $fileData;

echo "<pre>
$regexPattern

$outputPattern

$directData

$fileData
</pre>";
exit;

echo 'Pattern: ' . $_POST['pattern'] . "<br>" . $_FILES['contents-file']['name'] ?? '' . '<br>';
$regex = htmlspecialchars($_POST['pattern']);
$contents = htmlspecialchars($_POST['contents']);
if (empty($contents)) {
    $contents = htmlspecialchars(file_get_contents($_FILES['contents-file']['tmp_name']));
}

preg_match_all($regex, $contents, $matches, PREG_SET_ORDER);

$result = [];

foreach ($matches as $m) {
    if (!isset($result[$m[1]][$m[3]])) {
        $result[$m[1]][$m[3]] = ['Retry' => 0, 'Load' => 0];
    }
    $result[$m[1]][$m[3]][$m[2]]++;
}

$response = [];
echo "<pre>
\tip\t\t\tsuccess\tfailure\tsuccess%
";

$lastTime = '';
foreach ($result as $time => $r) {
    foreach ($r as $ip => $data) {
        $add = '';
        if ($lastTime != $time) {
            $add = '<br>';
            $lastTime = $time;
        }
        $success = round($data['Load'] / ($data['Load'] + $data['Retry']) * 100, 2);
        $response[$time . $success . str_replace('.', '', $ip)] = "$time\t\t$ip\t\t{$data['Load']}\t{$data['Retry']}\t$success%";
    }
}
asort($response);
echo implode('<br>', $response);
echo "</pre>";