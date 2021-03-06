<?php

class Category
{
    private $name;
    private $id;

    function __construct($name, $id = null)
    {
        $this->name = $name;
        $this->id = $id;
    }

    function setName($new_name)
    {
        $this->name = (string) $new_name;
    }

    function getName()
    {
        return $this->name;
    }

    function getId()
    {
        return $this->id;
    }

    function save()
    {
        $GLOBALS['DB']->exec("INSERT INTO categories (name) VALUES ('{$this->getName()}')");
        $this->id= $GLOBALS['DB']->lastInsertId();
    }

    function getTasks()
    {
        $tasks = Array();
        $returned_tasks = $GLOBALS['DB']->query("SELECT * FROM tasks WHERE category_id = {$this->getId()} ORDER BY task_date ASC;");
        foreach($returned_tasks as $task) {
            $description = $task['description'];
            $category_id = $task['category_id'];
            $id = $task['id'];
            $task_date = $task['task_date'];
            $new_task = new Task($description, $category_id, $id, $task_date);
            array_push($tasks, $new_task);
        }
        return $tasks;
    }

    static function getAll()
    {
        $returned_categories = $GLOBALS['DB']->query("SELECT * FROM categories;");
        $categories = array();
        foreach($returned_categories as $category) {
            $name = $category['name'];
            $id = $category['id'];
            $new_category = new Category($name, $id);
            array_push($categories, $new_category);
        }
        return $categories;
    }

    static function deleteAll()
    {
        $GLOBALS['DB']->exec("DELETE FROM categories;");
        $GLOBALS['DB']->exec("DELETE FROM tasks;");
    }

    static function find($search_id)
    {
        $found_category = null;
        $categories = Category::getAll();
        foreach($categories as $category) {
            $category_id = $category->getId();
            if ($category_id == $search_id) {
               $found_category = $category;
            }
        }
        return $found_category;
    }

}

?>
