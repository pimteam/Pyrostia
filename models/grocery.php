<?php
class Grocery extends Basic
{
    function __construct()
    {
        global $prefix;
        $this->prefix=$prefix;
        parent::__construct();
        $this->tablename=$this->prefix."groceries";
    }
}
?>