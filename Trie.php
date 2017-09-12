<?php

class Node
{
    public $char;//字符
    public $is_word_end = false;//是否为一个单词的结尾字符
    public $word_count = 0;//统计以该字符为结尾的单词个数
    public $positions = [];//出现的位置['行号' => ['列号1', '列号2',...], ...]
    public $child_nodes = [];//子节点
}

class Trie
{
    private $root;//根节点

    //添加一个单词
    private function _addNode(Node &$root, $char, $line_num, $column_num, $is_word_end = false)
    {
        //判断有没有该字符节点
        if (!isset($root->child_nodes[$char])) {
            $node = new Node();
            $node->char = $char;
            if ($is_word_end) {
                $node->is_word_end = $is_word_end;
                $node->positions[$line_num][] = $column_num;
                $node->word_count++;
            }
            //放入上层节点的子节点集合中
            $root->child_nodes[$char] = $node;
            return $node;
        }

        $root = $root->child_nodes[$char];
        if ($is_word_end) {
            $root->is_word_end = $is_word_end;
            $root->positions[$line_num][] = $column_num;
            $root->word_count++;
        }
        return $root;
    }

    public function __construct()
    {
        $this->root = new Node();
    }

    public function addWord($word, $line_num, $column_num)
    {
        $len = strlen($word);
        $root = $this->root;
        for ($i = 0; $i < $len; $i++) {
            $root = $this->_addNode($root, $word[$i], $line_num, $column_num, $i == $len - 1);
        }
    }

    private function _searchNode(Node $root, $word, $index = 0)
    {
        //判断有没有定义该节点
        if (!isset($root->child_nodes[$word[$index]])) {
            return false;
        }
        $root = $root->child_nodes[$word[$index]];
        //是结尾
        if ($index == strlen($word) - 1 && $root->is_word_end) {
            return $root;
        }
        return $this->_searchNode($root, $word, $index + 1);
    }

    public function searchWord($word)
    {
        if (!$word) {
            return false;
        }
        $node = $this->_searchNode($this->root, $word, 0);
        if(!$node) {
            return false;
        }
        return [
            'positions' => $node->positions,
            'word_count' => $node->word_count
        ];
    }

    //打印trie
    public function printTrie()
    {
        $root = $this->root;
        foreach ($root->child_nodes as $node) {
            $this->_dfsNode($node);
            echo "\r\n";
        }
    }

    //深度优先遍历
    private function _dfsNode(Node $node)
    {
        echo $node->char;
        foreach ($node->child_nodes as $n) {
            $this->_dfsNode($n);
            if ($n->is_word_end) echo "\r\n";
        }
    }


}
