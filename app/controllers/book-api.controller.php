<?php
require_once './app/models/book.model.php';
require_once './app/views/api.view.php';

class BookApiController {
    private $model;
    private $view;

    private $data;

    public function __construct() {
        $this->model = new BookModel();
        $this->view = new ApiView();
        
        // lee el body del request
        $this->data = file_get_contents("php://input");
    }

    private function getData() {
        return json_decode($this->data);
    }

    function getBooks($params = null) {
        try {

            $input = $_GET;
            $page = $_GET['page'] ?? null;
            $search = $_GET['search'] ?? null;
            $sort = $_GET['sort'] ?? null;

            //$sort = strtolower($sort);
           

            foreach ($input as $key => $value) {
                if ($key != 'page' && $key != 'sort' && $key != 'search' && $key != 'resource') {
                    //var_dump($key);
                    $this->view->response("Se ingresaron parametros incorrectos, ingrese sort, page, search o cualquier combinacion posible", 400);
                    die();
                }
            }

            //SI NO ESTA NINGUNO MUESTRO TODO
            if (!isset($search) && !isset($page) && !isset($sort)) {
                $this->getAllBooks();
                die();
            }
             //ORDENADO POR COLUMNA, PAGINADO Y CON FILTRADO
             else if (isset($search) && isset($page) && isset($sort)) {
                $this->getBooksOrderedPaginatedAndFiltered($sort, $page, $search);
                die();
            }
             //PAGINADO Y FILTRADO
             else if (isset($search) && isset($page) && !isset($sort)) {
                $this->getBooksPaginatedAndFiltered($search, $page);
                die();
            }
            //ORDENADO POR COLUMNA Y PAGINADO
            else if (isset($sort) && isset($page) && !isset($search)) {
                $this->getBooksOrderedAndPaginated($sort, $page);
                die();
            }
            //FILTRADO Y ORDENADO POR COLUMNA
            else if (isset($search) && isset($sort) && !isset($page)) {
                $this->getBooksOrderedAndFiltered($sort, $search);
                die();
            }
            //ORDENADO POR COLUMNA ASCENDENTEMENTE
            else if (isset($sort) && !isset($page) && !isset($search)) {
                $this->getBooksOrdered($sort);
                die();
            }

            //PAGINADO
            else if (isset($page) && !isset($sort) && !isset($search)) {
                $this->getBooksPaginated($page);
                die();
            }

            //FILTRADO
            if (isset($search) && !isset($sort) && !isset($page)) {
                $this->getBooksFiltered($search);
                die();
            }
        } catch (Exception $error) {
            $this->view->response("Error del servidor", 500);
        }
    }
    //Busca ordenado acendente
    function getAllBooks()
    {
        $books = $this->model->getAll();
        if ($books) {
            $this->view->response($books);
        } else {
            $this->view->response("No existen libros", 404);//o no hay
        }
    }
    //Ordenado, paginado y filtrado
    function getBooksOrderedPaginatedAndFiltered($sort, $page, $search)
    {
        if ($this->isAFieldOfTable($sort) && $sort != null && (is_numeric($page) && $page > 0) && $page != null && ($search != null)) {
            $books = $this->model->getOrderedPaginatedAndFiltered($sort, $search, $page);
            if ($books) {
                $this->view->response($books);
            } else {
                $this->view->response("No es posible encontrar libros segun $search en la pagina $page ordenados por $sort", 404);
            }
        } else {
            $this->showErrorParams();
        }
    }
    //paginado y filtrado
    function getBooksPaginatedAndFiltered($search = null, $page = null)
    {
        if (($search != null) && ($page != null) && (is_numeric($page) && $page > 0)) {
            $books = $this->model->getByTitlePaginated($search, $page);
            if ($books) {
                $this->view->response($books);
            } else {
                $this->view->response("No es posible encontrar libros que incluyan la palabra $search en la pagina $page ", 404);
            }
        } else {
            $this->showErrorParams();
        }
    }
    //ordenado por columna y paginado
    function getBooksOrderedAndPaginated($sort = null, $page = null)
    {
        if ($this->isAFieldOfTable($sort) && $sort != null && $page != null && (is_numeric($page) && $page > 0)) {
            $books = $this->model->getOrderedAndPaginated($sort, $page);
            if ($books) {
                $this->view->response($books);
            } else {
                $this->view->response("No es posible encontrar libros en la pagina $page ordenados por $sort", 404);
            }
        } else {
            $this->showErrorParams();
        }
    }
    //filtrado y ordenado por columna
    function getBooksOrderedAndFiltered($sort = null, $search = null)
    {
        if ($this->isAFieldOfTable($sort) && $sort != null && $search != null) {

            $books = $this->model->getOrderedAndFiltered($sort, $search);
            if ($books) {
                $this->view->response($books);
            } else {
                $this->view->response("No es posible encontrar libros que contengan $search ordenados por $sort", 404);
            }
        } else {
            $this->showErrorParams();
        }
    }
    //ordenado segun sort
    function getBooksOrdered($sort = null)
    {
        if ($this->isAFieldOfTable($sort) && $sort != null) {
            $books = $this->model->getAllBySort($sort);
            if ($books) {
                $this->view->response($books);
            } else {
                $this->view->response("No fue posible encontrar libros", 404); //si la tabla esta vacia
            }
        } else {
            $this->showErrorParam();
        }
    }
    //paginado
    function getBooksPaginated($page = null)
    {
        if (is_numeric($page) && $page > 0 && $page != null) {
                $books = $this->model->getAllByPagination($page);
                if ($books) {
                    $this->view->response($books);
                } else {
                    $this->view->response("No hay libros en esta pagina", 404);
                }
            
        } else {
            $this->showErrorParam();
        }
    }
    //filtrado
    function getBooksFiltered($search = null)
    {
        if ($search != null) {
            $books = $this->model->getByTitle($search);
            if ($books == null) {
                $this->view->response("No existen libros con el titulo $search", 404);
            } else {
                $this->view->response($books);
            }
        } else {
            $this->showErrorParam();
        }
    }

    /*function getQuantity()
    {
        $quantity = $this->model->getRegisters(); //esto me trae un obj, no el int con la cantidad
        foreach ($quantity as $q) { //por eso hago este foreach y devuelvo el int
            return $q;
        }
    }*/
       
    
    function isAFieldOfTable($sort){ // controla lo que se ingresa en column
        //var_dump($sort);
        $fields=$this->model->getAllColumns();//accedes a los titulos de las columnas
        
        foreach($fields as $field){
            foreach($field as $fieldName){

                if($fieldName==$sort){
                    //var_dump($fieldName);
                    return true;
                    
                }
            }

        }
        //var_dump("entro");
        return false;
        
    }
    //obtener un libro
    public function getBook($params = null) {
        // obtengo el id del arreglo de params
        $id = $params[':ID'];
        $book = $this->model->getBook($id);

        // si no existe devuelvo 404
        if ($book)
            $this->view->response($book);
        else 
            $this->view->response("La tarea con el id=$id no existe", 404);
    }
    //eliminar un libro
    public function deleteBook($params = null) {
        $id = $params[':ID'];

        $book = $this->model->getBook($id);
        if ($book) {
            $this->model->deleteBook($id);
            $this->view->response($book);
        } else 
            $this->view->response("La tarea con el id=$id no existe", 404);
    }
    // insertar un libro
    public function insertBook($params = null) {
        $book = $this->getData();

        if (empty($book->Titulo) || empty($book->Genero) || empty($book->Fecha_de_Publicacion) || empty($book->Editorial) || 
            empty($book->ISBN) || empty($book->Sinopsis) || empty($book->Imagen) ||empty($book->ID_autor_FK)){
            $this->view->response("Complete los datos", 400);
        }
        else {
            $id = $this->model->insertBook($book->Titulo, $book->Genero, $book->Fecha_de_Publicacion, $book->Editorial, $book->ISBN, $book->Sinopsis, $book->Imagen, $book->ID_autor_FK );
            $book = $this->model->getBook($id);
            $this->view->response($book, 201);
        }
    }

      //FUNCIONES DE ERROR
 
      public function showErrorParams()
      {
          $this->view->response("Alguno de los parametros es erroneo o esta vacio", 400);
          die();
      }
  
      public function showErrorParam()
      {
          $this->view->response("El parametro es erroneo o esta vacio", 400);
          die();
      }
      public function pageNotFound() {
        $this->view->response("Page not found", 404);
        die();

    }
}