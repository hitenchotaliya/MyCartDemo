<?php

class category
{
    private $db;

    public function __construct(Database $db)
    {
        $this->db = $db;
    }

    public function getAllCategories($page, $setLimit)
    {
        $this->db->select("categories", "*", null, null, $page, $setLimit);
        return $this->db->getResult();
    }
    public function searchCategories($search)
    {
        $search = $this->db->escapeString($search);
        $this->db->sql("SELECT * FROM categories WHERE `title` LIKE '%$search%' ");
        return $this->db->getResult();
    }
    public function sortCategories($orderby, $setLimit)
    {
        $this->db->select("categories", "*", null, null, $orderby, $setLimit);
        return $this->db->getResult();
    }
    public function sortCategoriesByDate($datesort, $setLimit)
    {
        $this->db->select("categories", "*", null, null, $datesort, $setLimit);
        return $this->db->getResult();
    }
    public function getActiveCategories($activecategory, $setLimit)
    {
        $this->db->select("categories", "*", null, $activecategory, null, $setLimit);
        return $this->db->getResult();
    }
}
