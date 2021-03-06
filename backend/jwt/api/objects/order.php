<?php
// объект 'Order' 
class Order {
 
    // подключение к БД таблице "orders" 
    private $conn;
    private $table_name = "orders";
 
    // свойства объекта 
    public $order_id;
    public $order_form_type;
    public $order_typeOfAct;
    public $order_name;
    public $order_phone;
    public $order_email;
    public $order_promo;
    public $order_text;  
    public $order_created;   
 
    // конструктор класса Order 
    public function __construct($db) {
        $this->conn = $db;
    }
  
    // Создание нового заказа для create_order.php
    function createOrder() {
        
        // Вставляем запрос 
        $query = "INSERT INTO " . $this->table_name . "
                SET
                    order_name = :order_name,
                    order_phone = :order_phone,
                    order_typeofact = :order_typeOfAct,
                    order_email = :order_email,
                    order_text = :order_text,
                    order_promo = :order_promo,
                    order_form_type = :order_form_type";       

        // подготовка запроса в БД и обработка ошибок
        try {
            $stmt = $this->conn->prepare($query);
        } catch (Exception $exception) {
            error_log("Unable to prepare statement: " . $exception->getMessage());
            return false;
        }
    
        // инъекция 
        $this->order_name=htmlspecialchars(strip_tags($this->order_name));
        $this->order_phone=htmlspecialchars(strip_tags($this->order_phone));
        $this->order_typeOfAct=htmlspecialchars(strip_tags($this->order_typeOfAct));
        $this->order_email=htmlspecialchars(strip_tags($this->order_email));
        $this->order_promo=htmlspecialchars(strip_tags($this->order_promo));
        $this->order_form_type=htmlspecialchars(strip_tags($this->order_form_type));
        // из order_text убрано удаление html тегов и преобразование символов
        //$this->order_text=htmlspecialchars(strip_tags($this->order_text));
        

        // привязываем значения 
        $stmt->bindParam(':order_name', $this->order_name);
        $stmt->bindParam(':order_phone', $this->order_phone);
        $stmt->bindParam(':order_typeOfAct', $this->order_typeOfAct);
        $stmt->bindParam(':order_email', $this->order_email);
        $stmt->bindParam(':order_text', $this->order_text);
        $stmt->bindParam(':order_promo', $this->order_promo);   
        $stmt->bindParam(':order_form_type', $this->order_form_type);  
    
        // Выполняем запрос и ловим возможные ошибки
        // Если выполнение успешно, то информация о новом заказе будет сохранена в базе данных
        try {
            $stmt->execute();
            $stmt = null;
            $this->conn = null;
        } catch (Exception $exception) {
            error_log("Unable to execute statement: " . $exception->getMessage());
            return false;
        }

        return true;
    }

    // Получение нескольких заказов для get_orders.php 
    function read() {

        // выбираем все записи 
        $query = "SELECT
                    order_id,
                    order_form_type,
                    order_typeofact,  
                    order_created                      
                FROM
                    " . $this->table_name . " 
                ORDER BY
                    order_id DESC";

        // подготовка запроса в БД и обработка ошибок
        try {
            $stmt = $this->conn->prepare($query);
        } catch (Exception $exception) {
            error_log("Unable to prepare statement: " . $exception->getMessage());
            return false;
        }        
        
        // Выполняем запрос и ловим возможные ошибки
        // Если выполнение успешно, то отправляем информацию о всех заказах
        try {
            $stmt->execute();
        } catch (Exception $exception) {
            error_log("Unable to execute statement: " . $exception->getMessage());
            return false;
        }

        return $stmt;
    }

    // метод delete - удаление заказа для delete_order.php
    function delete() {

        // запрос для удаления заказа 
        $query = "DELETE FROM " . $this->table_name . " WHERE order_id = ?";

        // подготовка запроса в БД и обработка ошибок
        try {
            $stmt = $this->conn->prepare($query);
        } catch (Exception $exception) {
            error_log("Unable to prepare statement: " . $exception->getMessage());
            return false;
        }   
        
        // очистка id
        $this->order_id = htmlspecialchars(strip_tags($this->order_id));

        // привязываем id записи для удаления 
        $stmt->bindParam(1, $this->order_id);

        // Выполняем запрос и ловим возможные ошибки
        // Если выполнение успешно, то отправляем информацию о всех заказах
        try {
            $stmt->execute();
            $stmt = null;
            $this->conn = null;
        } catch (Exception $exception) {
            error_log("Unable to execute statement: " . $exception->getMessage());
            return false;
        }

        return true;

    }

    // Получение нескольких заказов для update_order.php 
    function getOrder() {

        // выбираем все записи 
        $query = "SELECT
                    order_typeofact,  
                    order_name,
                    order_phone,
                    order_email,
                    order_promo,
                    order_text                     
                FROM
                    " . $this->table_name . " 
                WHERE 
                    order_id = ?";

        // подготовка запроса в БД и обработка ошибок
        try {
            $stmt = $this->conn->prepare($query);
        } catch (Exception $exception) {
            error_log("Unable to prepare statement: " . $exception->getMessage());
            return false;
        }        
        
        // очистка id
        $this->order_id = htmlspecialchars(strip_tags($this->order_id));

        // привязываем id записи для изменения заказа 
        $stmt->bindParam(1, $this->order_id);

        // Выполняем запрос и ловим возможные ошибки
        // Если выполнение успешно, то отправляем информацию о выбранном заказе
        try {
            $stmt->execute();
        } catch (Exception $exception) {
            error_log("Unable to execute statement: " . $exception->getMessage());
            return false;
        }

        return $stmt;
    }

    // Изменение заказа для update_order.php
    function updateOrder() {
        
        // Вставляем запрос 
        $query = "UPDATE " . $this->table_name . "
                SET
                    order_name = :order_name,
                    order_phone = :order_phone,
                    order_typeofact = :order_typeOfAct,
                    order_email = :order_email,
                    order_text = :order_text,
                    order_promo = :order_promo
                WHERE
                    order_id = :order_id";       

        // подготовка запроса в БД и обработка ошибок
        try {
            $stmt = $this->conn->prepare($query);
        } catch (Exception $exception) {
            error_log("Unable to prepare statement: " . $exception->getMessage());
            return false;
        }
    
        // инъекция 
        $this->order_name=htmlspecialchars(strip_tags($this->order_name));
        $this->order_phone=htmlspecialchars(strip_tags($this->order_phone));
        $this->order_typeOfAct=htmlspecialchars(strip_tags($this->order_typeOfAct));
        $this->order_email=htmlspecialchars(strip_tags($this->order_email));
        $this->order_promo=htmlspecialchars(strip_tags($this->order_promo));
        $this->order_form_type=htmlspecialchars(strip_tags($this->order_form_type));
        // из order_text убарно удаление html тегов и преобразование символов
        //$this->order_text=htmlspecialchars(strip_tags($this->order_text));
        

        // привязываем значения 
        $stmt->bindParam(':order_id', $this->order_id);
        $stmt->bindParam(':order_name', $this->order_name);
        $stmt->bindParam(':order_phone', $this->order_phone);
        $stmt->bindParam(':order_typeOfAct', $this->order_typeOfAct);
        $stmt->bindParam(':order_email', $this->order_email);
        $stmt->bindParam(':order_text', $this->order_text);
        $stmt->bindParam(':order_promo', $this->order_promo);   
 
    
        // Выполняем запрос и ловим возможные ошибки
        // Если выполнение успешно, то информация о новом заказе будет сохранена в базе данных
        try {
            $stmt->execute();
            $stmt = null;
            $this->conn = null;
        } catch (Exception $exception) {
            error_log("Unable to execute statement: " . $exception->getMessage());
            return false;
        }

        return true;
    }

    // Создание нового заказа для requests.add.php
    function createOrderFront() {
        
        // Вставляем запрос 
        $query = "INSERT INTO " . $this->table_name . "
                SET
                    order_name = :order_name,
                    order_phone = :order_phone,
                    order_typeofact = :order_typeOfAct,
                    order_email = :order_email,
                    order_text = :order_text,
                    order_promo = :order_promo,
                    order_form_type = :order_form_type";       

        // подготовка запроса в БД и обработка ошибок
        try {
            $stmt = $this->conn->prepare($query);
        } catch (Exception $exception) {
            error_log("Unable to prepare statement: " . $exception->getMessage());
            return false;
        }
    
        // инъекция 
        $this->order_name=htmlspecialchars(strip_tags($this->order_name));
        $this->order_phone=htmlspecialchars(strip_tags($this->order_phone));
        $this->order_typeOfAct=htmlspecialchars(strip_tags($this->order_typeOfAct));
        $this->order_email=htmlspecialchars(strip_tags($this->order_email));
        $this->order_promo=htmlspecialchars(strip_tags($this->order_promo));
        $this->order_form_type=htmlspecialchars(strip_tags($this->order_form_type));
        // из order_text убрано удаление html тегов и преобразование символов
        //$this->order_text=htmlspecialchars(strip_tags($this->order_text));
        

        // привязываем значения 
        $stmt->bindParam(':order_name', $this->order_name);
        $stmt->bindParam(':order_phone', $this->order_phone);
        $stmt->bindParam(':order_typeOfAct', $this->order_typeOfAct);
        $stmt->bindParam(':order_email', $this->order_email);
        $stmt->bindParam(':order_text', $this->order_text);
        $stmt->bindParam(':order_promo', $this->order_promo);   
        $stmt->bindParam(':order_form_type', $this->order_form_type);  
    
        // Выполняем запрос и ловим возможные ошибки
        // Если выполнение успешно, то информация о новом заказе будет сохранена в базе данных
        try {
            $stmt->execute();
            $stmt = null;
            $this->conn = null;
        } catch (Exception $exception) {
            error_log("Unable to execute statement: " . $exception->getMessage());
            return false;
        }

        return true;
    }    

}
