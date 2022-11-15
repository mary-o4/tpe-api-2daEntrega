<?php

class BookModel{

    private $db;

    function __construct()
    {
        $this->db = $this ->getDB();
    }

    private function getDB() {
        $db = new PDO('mysql:host=localhost;'.'dbname=db_tpe;charset=utf8', 'root', '');
        return $db;
    }
   
    function getAll()
    {

        $query = $this->db->prepare("SELECT * FROM libro");
        $query->execute();
        $books = $query->fetchAll(PDO::FETCH_OBJ);

        return $books;
    }

     //PAGINADO, FILTRADO Y ORDENADO
     function getOrderedPaginatedAndFiltered($sort, $search, $page)
     {
         $limit = 5;
         $offset = $page * $limit - $limit;
         $query = $this->db->prepare("SELECT * FROM libro  WHERE Titulo LIKE ? ORDER BY $sort LIMIT $limit OFFSET $offset");
         $query->execute(["%$search%"]);
         $books = $query->fetchAll(PDO::FETCH_OBJ);
         return $books;
     }
     //PAGINADO Y FILTRADO
     function getByTitlePaginated($search, $page)
     {
         $limit = 5;
         $offset = $page * $limit - $limit;
         $query = $this->db->prepare("SELECT * FROM libro WHERE Titulo LIKE ? LIMIT $limit OFFSET $offset");
         $query->execute(["%$search%"]);
         $books = $query->fetchAll(PDO::FETCH_OBJ);
         return $books;
     }

     //PAGINADO Y ORDENADO
     function getOrderedAndPaginated($sort, $page)
     {
         $limit = 5;
         $offset = $page * $limit - $limit;
         $query = $this->db->prepare("SELECT * FROM libro ORDER BY $sort LIMIT $limit OFFSET $offset ");
         $query->execute();
         $books = $query->fetchAll(PDO::FETCH_OBJ);
         return $books;
     } 

     //FILTRADO Y ORDENADO
    function getOrderedAndFiltered($sort = null, $search)
    {
        $query = $this->db->prepare("SELECT * FROM libro WHERE Titulo LIKE ? ORDER BY $sort");

        $query->execute(["%$search%"]);
        $books = $query->fetchAll(PDO::FETCH_OBJ);
        return $books;
    }
    //ORDENAR POR CUALQUIER CAMPO
    function getAllBySort($sort = null){
        $query = $this->db->prepare("SELECT * FROM libro ORDER BY $sort");
        $query->execute();

        $books = $query->fetchAll(PDO::FETCH_OBJ); 
        
        return $books;
    }

    // funcion para paginacion
    function getAllByPagination($page = null){
        $limit = 5;
        $offset = $page *$limit - $limit;
        $query = $this->db->prepare("SELECT * FROM libro LIMIT $limit OFFSET $offset");
        $query->execute();
        $books = $query->fetchAll(PDO::FETCH_OBJ); 
        
        return $books;


    }

     //para filtrar por titulo
     function getByTitle($search){
        $query = $this->db->prepare("SELECT * FROM libro WHERE Titulo LIKE ?");
        $query->execute(["%$search%"]);
        $books = $query->fetchAll(PDO::FETCH_OBJ); 
        
        return $books;
    }

    function getAllColumns()
    { //se trae toda la tabla 
        $query = $this->db->prepare("DESCRIBE libro");//descripcion toda la tabla
        $query->execute();
        $fields = $query->fetchAll(PDO::FETCH_OBJ);
        return $fields;
    }

    //funcion para saber si existe la columna en mi tabla no la estoy usando
    function getColumn($sort = null){
        $query = $this->db->prepare("SHOW COLUMNS FROM libro LIKE ?");
        $query->execute([$sort]);
        $result = $query->fetch(PDO::FETCH_OBJ);
        return $result;
    }

    //funcion para que me traiga la cantidad de registros de la tabla para al paginacion
   /* function getRegisters(){
        $query = $this->db->prepare("SELECT count(*) FROM libro");
        $query->execute();
        //PDO::FETCH_NUM??
        $quantity = $query->fetch(PDO::FETCH_OBJ);
        return $quantity;
    }*/

    
   
    function getBook($id){
        // o especificar las columnas `Genero`, `Fecha_de_Publicacion`, `Editorial`,`ISBN`, `Sinopsis`, `Imagen`
        $query = $this->db->prepare("SELECT libro.*, autor.Nombre, autor.Id FROM libro INNER JOIN autor ON libro.ID_autor_FK=autor.Id WHERE libro.ID=?");
        $query->execute([$id]);

        $book = $query->fetch(PDO::FETCH_OBJ);

        return $book;
    }

    function deleteBook($id){
        $query = $this->db->prepare('DELETE FROM libro WHERE ID = ?');
        $query->execute([$id]);
    }

    
    function insertBook($title, $genre, $date, $editorial, $isbn, $sinopsis, $imagen, $author) {

        $query = $this->db->prepare("INSERT INTO libro (Titulo, Genero, Fecha_de_Publicacion, Editorial, ISBN, Sinopsis, Imagen, ID_autor_FK ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $query->execute([$title, $genre, $date, $editorial, $isbn, $sinopsis, $imagen, $author]);

        return $this->db->lastInsertId();
    }
}