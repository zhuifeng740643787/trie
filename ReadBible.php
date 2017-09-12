<?php
require 'Trie.php';

class ReadBible
{
    private $trie;//字典树
    private $filename;//读取的文件

    public function __construct()
    {
        $this->trie = new Trie();
    }

    public function setFile($filename)
    {
        if (!file_exists($filename) || !is_readable($filename)) {
            die($filename . ': 文件无法读取');
        }
        $this->filename = $filename;
    }

    public function read()
    {
        $f = fopen($this->filename, 'r');
        $line_num = 0;
        while (!feof($f)) {
            $line_num++;
            $line = fgets($f);
            if ($this->isBlankLine($line)) {
                continue;
            }
            $this->processLine($line_num, $line);
        }
        fclose($f);
    }

    public function searchWord($word, $return = false) {
        $search = $this->trie->searchWord($word);
        if(!$search) {
            echo '未找到该单词';
            return false;
        }
        if($return) {
            return $search;
        }
        echo "单词[{$word}], 个数：{$search['word_count']}";br();
        $i = 10;
        foreach ($search['positions'] as $line_num => $column_nums) {
            if($i<=0) {
                break;
            }
            printf("第% 5s行：%s列", $line_num, implode(',', $column_nums));br();
            $i--;
        }
        if ($search['word_count'] > 10) {
            print_r('...');br();
        }
    }

    public function printTrie() {
        return $this->trie->printTrie();
    }
    //是否为空行
    function isBlankLine($str)
    {
        if (trim($str) == '') {
            return true;
        }
        return false;
    }

    //判断是否为词间分隔符
    function isDivideChar($char)
    {
        $ord = ord($char);
        //0-9
        if ($ord >= 48 && $ord <= 57) {
            return false;
        }
        //A-Z
        if ($ord >= 65 && $ord <= 90) {
            return false;
        }
        //a-z
        if ($ord >= 97 && $ord <= 122) {
            return false;
        }
        return true;
    }

    function processLine($line_num, $line)
    {
        $length = strlen($line);
        $index = 0;
        $word = '';
        $word_begin_index = $index;
        while ($index < $length) {
            if ($this->isDivideChar($line[$index])) {
                $this->processWord($word, $line_num, $word_begin_index + 1);
                $word = '';
                $word_begin_index = $index + 1;
            } else {
                $word .= $line[$index];
            }
            ++$index;
        }
    }

    function processWord($word, $line_num, $column_num)
    {
        if (!$word) {
            return;
        }
        $this->trie->addWord($word, $line_num, $column_num);
    }

}
