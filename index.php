<?php
require "ReadBible.php";
$search_word = isset($argv[1]) ? $argv[1] : '';
if (!$search_word) {
    die('未输入要查询的单词');
}
if (!function_exists('br')) {
    function br()
    {
        echo "\r\n";
    }
}
$filename = __DIR__ . DIRECTORY_SEPARATOR . 'bible.txt';
print_r('初始化内存使用:'); showMemUse();
$read_bible = new ReadBible();
$read_bible->setFile($filename);
$start_time = microtime(true);
print_r('文件读取中...'); br();
$read_bible->read();
print_r('字典树使用内存:'); showMemUse();
print_r('文件读取完成...'); showTimeUse($start_time, microtime(true)); br();
$st = microtime(true);
print_r('单词查找中...');br();
$search = $read_bible->searchWord($search_word);
print_r('单词查找时间:'); showTimeUse($st, microtime(true));br();
$et = microtime(true);
print_r($search);


function showMemUse() {
    printf('%0.6fMB', memory_get_usage() / (1<<20));
    br();
}
function showTimeUse($st, $et) {
    printf('%0.6fs',  $et - $st);
    br();
}