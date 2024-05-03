<?php 

    require_once 'ClientesController.php'; 
   
    $clientesController = new ClientesController();

    $metodo = $_SERVER['REQUEST_METHOD']; 
    $uri = $_SERVER['REQUEST_URI'];
    // var_dump($metodo); 
    // var_dump($_SERVER['REQUEST_URI']);
    // exit();

    if ($metodo === 'POST' && $_SERVER['REQUEST_URI'] === '/MagnaClientes/RotaClientes.php/token') {
        // Gerar e retornar o token
        
        $clientesController = new ClientesController();
        echo $clientesController->gerarToken();
        exit();
    }

    $headers = apache_request_headers();   
    $token = isset($headers['Authorization']) ? $headers['Authorization'] : null; 
        
        //Caminho da uri 
        switch($metodo) { 
            case 'POST': 
                //Novo Cliente 
                if($uri === '/MagnaClientes/RotaClientes.php/cadastrar') { 
                    $post = file_get_contents('php://input'); 
                    $cliente = json_decode($post, true); 
                    echo $clientesController->criarCliente($cliente,$token);
                }
            break; 
            
            case 'GET' : 
                //Buscar um Cliente
                // echo $uri;
                if (preg_match('/^\/MagnaClientes\/RotaClientes.php\/buscarCliente\/(\d+)$/', $uri, $matches)) {
                    $id = $matches[1];
                    echo $clientesController->buscarCliente($id, $token);
                }

                //Listar todos clientes
                elseif ($_SERVER['REQUEST_URI'] === '/MagnaClientes/RotaClientes.php/listarClientes') {
                    echo $clientesController->listarClientes($token);
                }
            break;
            
            case 'PUT' : 
                //Atualizar um cliente
                if(preg_match('/^\/MagnaClientes\/RotaClientes.php\/atualizar\/(\d+)$/', $uri, $matches)) { 
                    $id = $matches[1];
                    $put = file_get_contents('php://input');
                    $cliente = json_decode($put, true);
                    echo $clientesController->atualizarCliente($id,$cliente, $token);
                }
            break;    
            
            case 'DELETE':
                //Excluir um cliente
                if (preg_match('/^\/MagnaClientes\/RotaClientes.php\/excluir\/(\d+)$/', $_SERVER['REQUEST_URI'], $matches)) {
                        $id = $matches[1];
                        echo $clientesController->excluirCliente($id, $token);
                }
            break; 
            
            default: 

                http_response_code(405); 
                echo json_encode(["error" => "Método não suportado"]); 
            break;     
        }
   

?>