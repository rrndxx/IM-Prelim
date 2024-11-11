<?php
$newConnection = new Connection();

class Connection
{
    private $server = "mysql:host=localhost;dbname=sampledatabase";
    private $user = "root";
    private $pass = "";
    private $options = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ);
    protected $con;

    public function openConnection()
    {
        try {
            $this->con = new PDO($this->server, $this->user, $this->pass, $this->options);
            return $this->con;
        } catch (PDOException $th) {
            echo "There is a problem in the connection: " . $th->getMessage();
        }
    }

    public function addProduct()
    {
        if (isset($_POST['addproduct'])) {
            $productname = $_POST['productname'];
            $cat = $_POST['category'];
            $quantity = $_POST['quantity'];
            $purchasedate = $_POST['purchasedate'];

            try {
                $connection = $this->openConnection();

                $query = "SELECT id FROM categories WHERE catname = ?";
                $stmnt = $connection->prepare($query);
                $stmnt->execute([$cat]);
                $category = $stmnt->fetch();

                $catid = $category->id;

                $query = "INSERT INTO products (prod_name, cat_id, cat, quan, date) VALUES (?, ?, ?, ?, ?)";
                $stmnt = $connection->prepare($query);
                $stmnt->execute([$productname, $catid, $cat, $quantity, $purchasedate]);

                header("Location: main.php");
                exit;
            } catch (PDOException $e) {
                echo "Error: " . $e->getMessage();
            }
        }
    }

    public function editProduct()
    {
        if (isset($_POST['editproduct'])) {
            $id = $_POST['edit_id'];
            $productname = $_POST['productname'];
            $cat = $_POST['category'];
            $quantity = $_POST['quantity'];
            $purchasedate = $_POST['purchasedate'];

            try {
                $connection = $this->openConnection();

                $query = "SELECT id FROM categories WHERE catname = ?";
                $stmnt = $connection->prepare($query);
                $stmnt->execute([$cat]);
                $category = $stmnt->fetch();

                $catid = $category->id;

                $query = "UPDATE products SET prod_name= :productname, cat_id= :catid, cat= :cat, quan= :quantity, date= :purchasedate WHERE id = :id";
                $stmnt = $connection->prepare($query);
                $stmnt->execute([
                    "id" => $id,
                    "productname" => $productname,
                    "catid" => $catid,
                    "cat" => $cat,
                    "quantity" => $quantity,
                    "purchasedate" => $purchasedate,
                ]);

                header("Location: main.php");
                exit;
            } catch (PDOException $e) {
                echo "Error: " . $e->getMessage();
            }
        }
    }

    public function addCategory()
    {
        if (isset($_POST['addcategory'])) {
            $catname = $_POST['catname'];

            try {
                $connection = $this->openConnection();
                $query = "INSERT INTO categories (catname) VALUES (?)";
                $stmnt = $connection->prepare($query);
                $stmnt->execute([$catname]);

                header("Location: main.php");
                exit;
            } catch (PDOException $e) {
                echo "Error: " . $e->getMessage();
            }
        }
    }


    public function deleteProduct()
    {
        if (isset($_POST['deletebutton'])) {
            $id = $_POST['deletebutton'];
            try {
                $connection = $this->openConnection();
                $query = "DELETE FROM products WHERE id = :id";
                $stmnt = $connection->prepare($query);
                $stmnt->execute(["id" => $id]);

                header("Location: main.php");
                exit;
            } catch (PDOException $e) {
                echo "Error: " . $e->getMessage();
            }
        }
    }

    public function searchProduct()
    {
        if (isset($_POST['searchbutton'])) {
            $search = $_POST['search'];
            try {
                $connection = $this->openConnection();
                $query = "SELECT * FROM products WHERE prod_name = ?";
                $stmnt = $connection->prepare($query);
                $stmnt->execute([$search]);
                return $stmnt->fetchAll();
            } catch (PDOException $e) {
                echo "Error: " . $e->getMessage();
            }
        }
        return [];
    }

    public function inStock()
    {
        if (isset($_POST['instock'])) {
            try {
                $connection = $this->openConnection();
                $query = "SELECT * FROM products WHERE quan > 0";
                $stmnt = $connection->prepare($query);
                $stmnt->execute();
                return $stmnt->fetchAll();
            } catch (PDOException $e) {
                echo "Error: " . $e->getMessage();
            }
        }
        return [];
    }

    public function outofStock()
    {
        if (isset($_POST['outofstock'])) {
            try {
                $connection = $this->openConnection();
                $query = "SELECT * FROM products WHERE quan <= 0";
                $stmnt = $connection->prepare($query);
                $stmnt->execute();
                return $stmnt->fetchAll();
            } catch (PDOException $e) {
                echo "Error: " . $e->getMessage();
            }
        }
        return [];
    }

    public function filterProducts($category, $startDate, $endDate)
    {
        $connection = $this->openConnection();
        $query = "SELECT * FROM products WHERE cat_id = :category"; // Use cat_id

        if (!empty($startDate) && !empty($endDate)) {
            $query .= " AND date BETWEEN :startDate AND :endDate";
        }

        $stmnt = $connection->prepare($query);
        $stmnt->bindParam(':category', $category);

        if (!empty($startDate) && !empty($endDate)) {
            $stmnt->bindParam(':startDate', $startDate);
            $stmnt->bindParam(':endDate', $endDate);
        }

        $stmnt->execute();
        return $stmnt->fetchAll();
    }

    public function getCategories()
    {
        try {
            $connection = $this->openConnection();
            $query = "SELECT * FROM categories";
            $stmnt = $connection->prepare($query);
            $stmnt->execute();
            return $stmnt->fetchAll();
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        return [];
    }

    public function removeFromCart()
    {
        if (isset($_POST['removeFromCart'])) {
            $id = $_POST['removeFromCart'];
            try {
                $connection = $this->openConnection();
                $query = "DELETE FROM cart WHERE id = :id";
                $stmnt = $connection->prepare($query);
                $stmnt->execute(["id" => $id]);

                header("Location: customerdashboard.php");
                exit;
            } catch (PDOException $e) {
                echo "Error: " . $e->getMessage();
            }
        }
    }
}
