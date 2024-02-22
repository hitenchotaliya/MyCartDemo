<?php

class product
{
    private $db;

    public function __construct(Database $db)
    {
        $this->db = $db;
    }
    public function getAllProducts($page, $limit)
    {
        $offset = ($page - 1) * $limit;
        $this->db->select(
            "products",
            "DISTINCT products.product_id, products.title, products.description, products.is_active, products.category_id, categories.title AS category_title, product_images.image_path, product_images.is_primary",
            "categories LEFT JOIN product_images ON products.product_id = product_images.product_id  AND product_images.is_primary = 1",
            "categories.is_active = 1 AND categories.category_id = products.category_id",
            null,
            $limit,
            $offset
        );
        return $this->db->getResult();
    }
}
