<?php 

class Souvenir
{
    private $table = 'souvenirs';

    public $id;
    public $title;
    public $price;
    public $desc;
    public $image;
    public $stock;
    public $category;
    public $supplier;
    
    public $amount;

    // $this->title = $title;
    // $this->price = $price;
    // $this->desc = $desc;
    // $this->image = $image;
    // $this->stock = $stock;
    // $this->category = $category;
    // $this->supplier = $supplier;

    public function __construct($db, $auth)
    {
        $this->conn = $db;
        $this->auth = $auth;
    }

    function create()
    {
        if($this->auth->access('admin'))
        {
            $query = "INSERT INTO " .$this->table. " SET
                title = :title,
                price = :price,
                description = :desc,
                image = :image,
                stock = :stock,
                category_id = :category,
                supplier_id = :supplier";

            $stmt = $this->conn->prepare($query);
           
            $stmt->bindParam(':title', $this->title);
            $stmt->bindParam(':price', $this->price);
            $stmt->bindParam(':desc', $this->desc);
            $stmt->bindParam(':image', $this->image);
            $stmt->bindParam(':stock', $this->stock);
            $stmt->bindParam(':category', $this->category);
            $stmt->bindParam(':supplier', $this->supplier);
            
            if($stmt->execute()){
                return true;
            }

            return false;
        }
        else
        {
            return 'access';
        }
    }

    function upload_image()
    {
        $errors= array();

        $file_ext = strtolower(end(explode('.',$this->image['name'])));
        $extensions = array("jpeg", "jpg", "png");
        
        if(in_array($file_ext, $extensions) === false)
        {
            return 2;
        }
         
         if(empty($errors))
         {
            move_uploaded_file($this->image['tmp_name'], "./../../../app/src/imgs/souvenirs/".$this->image['name']);
            return true;
         }
         else
         {
            return false;
         }
    }

    function read()
    {
        $query = "SELECT * FROM " .$this->table. "";

        $stmt = $this->conn->prepare($query);
        
        
        if($stmt->execute()){
            $response = $stmt->fetchAll();
            return $response;
        }

        return "Unexpected error occured, try again later.";
    }

    function readByCategory(string $category_id = null)
    {
        if($category_id != null)
        {
            $query = "SELECT * FROM " .$this->table. " WHERE category_id=".$category_id."";

            $stmt = $this->conn->prepare($query);
            
            if($stmt->execute()){
                $response = $stmt->fetchAll();
                return $response;
            }
        }
    
        return "Unexpected error occured, try again later.";
    }

    function readBySupplier(string $supplier_id = null)
    {
        if($supplier_id != null)
        {
            $query = "SELECT * FROM " .$this->table. " WHERE supplier_id=".$supplier_id."";

            $stmt = $this->conn->prepare($query);
            
            if($stmt->execute()){
                $response = $stmt->fetchAll();
                return $response;
            }
        }
    
        return "Unexpected error occured, try again later.";
    }

    function readByCategoryAndSupplier(string $category_id = null, string $supplier_id = null)
    {
        if($category_id != null && $supplier_id != null)
        {
            $query = "SELECT * FROM " .$this->table. " WHERE supplier_id=".$supplier_id." and category_id=".$category_id."";

            $stmt = $this->conn->prepare($query);
            
            if($stmt->execute()){
                $response = $stmt->fetchAll();
                return $response;
            }
        }
    
        return "Unexpected error occured, try again later.";
    }

    function read_single()
    {
        $query = "SELECT * FROM " .$this->table. " WHERE id='".$this->id."'";

        $stmt = $this->conn->prepare($query);
        
        
        if($stmt->execute()){
            $response = $stmt->fetch(PDO::FETCH_ASSOC);
            return $response;
        }

        return "Unexpected error occured, try again later.";
    }

    function update()
    {
        if($this->auth->access('admin'))
        {
            $query = "UPDATE " .$this->table. " SET
            title = :title,
            price = :price,
            description = :desc,
            image = :image,
            stock = :stock,
            category_id = :category,
            supplier_id = :supplier
            WHERE id=".$this->id."";

            $stmt = $this->conn->prepare($query);
           
            $stmt->bindParam(':title', $this->title);
            $stmt->bindParam(':price', $this->price);
            $stmt->bindParam(':desc', $this->desc);
            $stmt->bindParam(':image', $this->image);
            $stmt->bindParam(':stock', $this->stock);
            $stmt->bindParam(':category', $this->category);
            $stmt->bindParam(':supplier', $this->supplier);

            if($stmt->execute()){
                return true;
            }

            return false;
        }
        else
        {
            return 'access';
        }
    }

    // function purchase()
    // {
    //     if($this->auth->access('purchase'))
    //     {
    //         $this->stock -= $this->amount;
    //         $query = "UPDATE " .$this->table. " SET stock='".$this->stock."' WHERE id=".$this->id."";

    //         $stmt = $this->conn->prepare($query);
            
    //         if($stmt->execute()){
    //             return true;
    //         }

    //         return false;
    //     }
    //     else
    //     {
    //         return 'access';
    //     }
    // }

    function delete()
    {
        if($this->auth->access('admin'))
        {
            $query = "DELETE FROM " .$this->table. " WHERE id=".$this->id."";
            $stmt = $this->conn->prepare($query);
            
            if($stmt->execute()){
                return true;
            }

            return false;
        }
        else
        {
            return 'access';
        }
    }

    function _exists()
    {
        $query = "SELECT id FROM " .$this->table. " WHERE title='" .$this->title. "'";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        $response = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if(isset($response['id']) && !empty($response['id']))
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    function cute_price(string $price = null)
    {
        if($price != null)
        {
            return "$ ".number_format($price,2)." NZD";
        }
        else
        {
            return "Don't know";
        }
    }

    function validate_price()
    {
        if(is_numeric($this->price))
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    function validate_stock()
    {
        if(is_numeric($this->stock))
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    function validate_category()
    {
        $query = "SELECT id FROM categories WHERE id='" .$this->category. "'";

        $stmt = $this->conn->prepare($query);
        if($stmt->execute())
        {
            if($stmt->rowCount() > 0)
            {
                return 'true';
            }
        }

        return false;
    }

    function validate_supplier()
    {
        $query = "SELECT id FROM suppliers WHERE id='" .$this->supplier. "'";

        $stmt = $this->conn->prepare($query);
        if($stmt->execute())
        {
            if($stmt->rowCount() > 0)
            {
                return true;
            }
        }

        return false;
    }
}