<?php 

    require_once 'Clientes.php';
    require_once 'Conexao.php';

    class ClientesController { 

        private $authToken;
        private $expiracaoToken;
     
        public function __construct() { 
            $this->authToken = bin2hex(random_bytes(32)); 
            $this->expiracaoToken = 3600; 
        } 

        public function gerarToken() {
            // Lógica para gerar o token
            $tokenData = [
                "exp" => strtotime('+1 day'), // Tempo de expiração (1 dia a partir de agora)
                "authToken" => bin2hex(random_bytes(32)) // Token de autenticação (gerado aleatoriamente)
            ];
        
            // Codificar o token em JSON e em base64
            $token = base64_encode(json_encode($tokenData));
        
            return json_encode(["token" => $token]);
        }
    
      
        public function criarCliente($dataCli, $token) {

            if (!$this->verificarToken($token)) {
                http_response_code(401);
                return json_encode(["error" => "Token inválido: ".$token]);
            }
                
            try {

                $conexao = Conexao::abreConexao();
                $cliente = $this->validarDadosCliente($dataCli);

                // var_dump($cliente); 
                // exit();

                $clienteObjeto = new Clientes(
                    $cliente['nome'],
                    $cliente['endereco'],
                    $cliente['genero'],
                    $cliente['data_nascimento'],
                    $cliente['numero'],
                    $cliente['complemento'] ?? null,
                    $cliente['cidade'],
                    $cliente['estado'],
                    $cliente['cep'],
                    $cliente['email'],
                    $cliente['telefone'],
                    $cliente['cpf_cnpj'],
                    $cliente['data_cadastro'],
                    $cliente['status'],
                    $cliente['observacao'] ?? null,
                    $cliente['login'],
                    $cliente['senha']
                );

                $sql = "INSERT INTO Clientes (nome, genero, data_nascimento, endereco, numero, complemento, cidade, estado, cep, email, telefone, cpf_cnpj, data_cadastro, status, observacao, login, senha) 
                VALUES (:nome,:genero,:data_nascimento, :endereco, :numero, :complemento, :cidade, :estado, :cep, :email, :telefone, :cpf_cnpj, :data_cadastro, :status, :observacao, :login, :senha)";
                $stmt = $conexao->prepare($sql);
                $stmt->execute([
                    'nome' => $clienteObjeto->getNome(),
                    'genero' => $clienteObjeto->getGenero(),
                    'data_nascimento' => $clienteObjeto->getDataNascimento(),
                    'endereco' => $clienteObjeto->getEndereco(),
                    'numero' => $clienteObjeto->getNumero(),
                    'complemento' => $clienteObjeto->getComplemento(),
                    'cidade' => $clienteObjeto->getCidade(),
                    'estado' => $clienteObjeto->getEstado(),
                    'cep' => $clienteObjeto->getCep(),
                    'email' => $clienteObjeto->getEmail(),
                    'telefone' => $clienteObjeto->getTelefone(),
                    'cpf_cnpj' => $clienteObjeto->getCpfCnpj(),
                    'data_cadastro' => $clienteObjeto->getDataCadastro(),
                    'status' => $clienteObjeto->getStatus(),
                    'observacao' => $clienteObjeto->getObservacao(),
                    'login' => $clienteObjeto->getLogin(),
                    'senha' => $clienteObjeto->getSenha()
                ]);

                return json_encode(["message" => "Cliente cadastrado com sucesso"]);

           } catch  (Exception $e) {
                http_response_code(400);
                return json_encode(["error" => $e->getMessage()]);
           }  
          
        } 

        private function validarDadosCliente($dataCli) { 

            //Validação do genero 
            $genero = strtolower($dataCli['genero']); 
            if(!in_array($genero,['masculino','feminino','não determinado'])) { 
                throw new Exception("Gênero Inexistente"); 
            } 

            //Validação da Data de Nascimento 
            $dataNascimento = DateTime::createFromFormat('d/m/Y',$dataCli['data_nascimento']);
            if(!$dataNascimento) { 
                throw new Exception("Data de nascimento inválida");    
            } 
            $dataNascimentoFormatada = $dataNascimento->format('Y-m-d');    

            //Validação do CEP 
            if (!preg_match('/^\d{5}-\d{3}$/', $dataCli['cep'])) {
                throw new Exception("CEP inválido");
            }

             //Validação de e-mail
             if (!filter_var($dataCli['email'], FILTER_VALIDATE_EMAIL)) {
                    throw new Exception("E-mail inválido");
             }

            //Validação de telefone
            if (!preg_match('/^\(\d{2}\) \d{1}\d{3,4}-\d{4}$/', $dataCli['telefone'])) {
                    throw new Exception("Telefone inválido");
            }


            // Identificar se é CPF ou CNPJ
            $cpfCnpj = preg_replace('/[^0-9]/', '', $dataCli['cpf_cnpj']);
            if (strlen($cpfCnpj) == 11) {
                $tipoDocumento = 'cpf';
            } elseif (strlen($cpfCnpj) == 14) {
                $tipoDocumento = 'cnpj';
            } else {
                throw new Exception("CPF/CNPJ inválido");
            }

            // Validar o status
            $status = strtolower($dataCli['status']);
            if (!in_array($status, ['ativo', 'inativo', 'pendente'])) {
                throw new Exception("Status inválido");
            }

            $dataCadastro = DateTime::createFromFormat('d/m/Y',$dataCli['data_cadastro']);
            if(!$dataCadastro) { 
                throw new Exception("Data de cadastro inválida");    
            } 
            $dataCadastroFormatada = $dataCadastro->format('Y-m-d');    


            // Validar o login
            if (!preg_match('/^[a-zA-Z0-9]{5,15}$/', $dataCli['login'])) {
                throw new Exception("Login inválido");
            }

            // Validar a senha
            if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{6,20}$/', $dataCli['senha'])) {
                throw new Exception("Senha inválida");
            } 


            $estadosBrasileiros = array(
                'AC', 'AL', 'AP', 'AM', 'BA', 'CE', 'DF', 'ES', 'GO', 'MA', 'MT', 'MS',
                'MG', 'PA', 'PB', 'PR', 'PE', 'PI', 'RJ', 'RN', 'RS', 'RO', 'RR', 'SC',
                'SP', 'SE', 'TO'
            );
        
            //Validar estado
            $estado = strtoupper($dataCli['estado']);
            if (strlen($estado) !== 2 || !in_array($estado, $estadosBrasileiros)) {
                throw new Exception("Estado inválido");
            }


            return [
                'nome' => $dataCli['nome'],
                'genero' => $genero,
                'data_nascimento' => $dataNascimentoFormatada,
                'endereco' => $dataCli['endereco'],
                'numero' => $dataCli['numero'],
                'complemento' => $dataCli['complemento'] ?? null,
                'cidade' => $dataCli['cidade'],
                'estado' => $estado,
                'cep' => $dataCli['cep'],
                'email' => $dataCli['email'],
                'telefone' => $dataCli['telefone'],
                'cpf_cnpj' => $cpfCnpj,
                'data_cadastro' => $dataCadastroFormatada,
                'status' => $status,
                'observacao' => $dataCli['observacao'] ?? null,
                'login' => $dataCli['login'],
                'senha' => $dataCli['senha']
            ];



        }
    
        public function buscarCliente($id, $token) { 

            if (!$this->verificarToken($token)) {
                http_response_code(401);
                return json_encode(["error" => "Token inválido"]);
            }

            try { 
                $conexao = Conexao::abreConexao();
                $sql = "SELECT * FROM Clientes WHERE id = :id";
                $stmt = $conexao->prepare($sql);
                $stmt->execute(['id' => $id]);
                $cliente = $stmt->fetch(PDO::FETCH_ASSOC);
                if(!$cliente) { 
                    throw new Exception("Cliente não localizado");
                }
                return json_encode($cliente);
            } catch(Exception $e) {
                http_response_code(400); 
                return json_encode(["error" => $e->getMessage()]);
            }
            
        }
    
        public function atualizarCliente($id,$dataCli, $token) {

            if (!$this->verificarToken($token)) {
                http_response_code(401);
                return json_encode(["error" => "Token inválido"]);
            }

            try {
                $conexao = Conexao::abreConexao();
                $cliente = $this->validarDadosCliente($dataCli);

                $clienteObjeto = new Clientes(
                    $cliente['nome'],
                    $cliente['endereco'],
                    $cliente['genero'],
                    $cliente['data_nascimento'],
                    $cliente['numero'],
                    $cliente['complemento'] ?? null,
                    $cliente['cidade'],
                    $cliente['estado'],
                    $cliente['cep'],
                    $cliente['email'],
                    $cliente['telefone'],
                    $cliente['cpf_cnpj'],
                    $cliente['data_cadastro'],
                    $cliente['status'],
                    $cliente['observacao'] ?? null,
                    $cliente['login'],
                    $cliente['senha']
                );

                // var_dump($clienteObjeto);
                // exit();

                $sql = "UPDATE Clientes SET nome = :nome, genero = :genero, data_nascimento = :data_nascimento, endereco = :endereco, numero = :numero, complemento = :complemento, cidade = :cidade, estado = :estado, cep = :cep, email = :email, telefone = :telefone, cpf_cnpj = :cpf_cnpj, data_cadastro = :data_cadastro, status = :status, observacao = :observacao, login = :login, senha = :senha WHERE id = :id";
                // echo $sql; 
                // exit();
                $stmt = $conexao->prepare($sql);
                $stmt->execute([
                    'id' => $id,
                    'nome' => $clienteObjeto->getNome(),
                    'genero' =>$clienteObjeto->getGenero(),
                    'data_nascimento' =>$clienteObjeto->getDataNascimento(),
                    'endereco' => $clienteObjeto->getEndereco(),
                    'numero' => $clienteObjeto->getNumero(),
                    'complemento' => $clienteObjeto->getComplemento(),
                    'cidade' => $clienteObjeto->getCidade(),
                    'estado' => $clienteObjeto->getEstado(),
                    'cep' => $clienteObjeto->getCep(),
                    'email' => $clienteObjeto->getEmail(),
                    'telefone' => $clienteObjeto->getTelefone(),
                    'cpf_cnpj' => $clienteObjeto->getCpfCnpj(),
                    'data_cadastro' => $clienteObjeto->getDataCadastro(),
                    'status' => $clienteObjeto->getStatus(),
                    'observacao' => $clienteObjeto->getObservacao(),
                    'login' => $clienteObjeto->getLogin(),
                    'senha' => $clienteObjeto->getSenha()
                ]);
                return json_encode(["message" => "Cliente atualizado com sucesso"]);
            } catch (Exception $e) {
                http_response_code(400);
                return json_encode(["error" => $e->getMessage()]);
            }

            
        }
    
        public function excluirCliente($id, $token) {
            
            if (!$this->verificarToken($token)) {
                http_response_code(401);
                return json_encode(["error" => "Token inválido"]);
            }
            
            try {
                $conexao = Conexao::abreConexao(); 

                $sql_verificar = "SELECT COUNT(*) FROM Clientes WHERE id = :id";
                $stmt_verificar = $conexao->prepare($sql_verificar);
                $stmt_verificar->execute(['id' => $id]);
                $existe_cliente = $stmt_verificar->fetchColumn();
        
                if($existe_cliente) { 
                    $sql = "DELETE FROM Clientes WHERE id = :id";
                    $stmt = $conexao->prepare($sql);
                    $stmt->execute(['id' => $id]);
    
                    return json_encode(["message" => "Cliente excluido com sucesso"]);
                } else { 
                    http_response_code(404); 
                    return json_encode(["error" => "Exclusão não realizada. Cliente não existe!"]);
                }
                
               
            } catch (Exception $e) {
                http_response_code(400);
                return json_encode(["error" => $e->getMessage()]);
            }

            
        }    

        public function listarClientes($token) {

            if (!$this->verificarToken($token)) {
                http_response_code(401);
                return json_encode(["error" => "Token inválido"]);
            }

            try {
                $conexao = Conexao::abreConexao(); 
                $sql = "SELECT * FROM Clientes";
                $stmt = $conexao->prepare($sql);
                $stmt->execute();
                $clientes = $stmt->fetchAll(PDO::FETCH_ASSOC); 

                return json_encode($clientes);
            } catch (\Exception $e) {
                http_response_code(400);
                return json_encode(["error" => $e->getMessage()]);
            }
          
        }

        // private function verAuth($token) {
        //    if ($token !== $this->authToken) {
        //           throw new Exception("Token de autenticação inválido");
        //     }
        // } 

        private function verificarToken($token) {
           
            $decodedToken = json_decode(base64_decode($token), true);
            
          
            // Verificar se o token foi decodificado com sucesso e se o tempo de expiração é válido
            if ($decodedToken === null || !isset($decodedToken['exp']) || $decodedToken['exp'] < time()) {
                var_dump($decodedToken);
                return false; // Token inválido ou expirado
            } else {
                return true; // Token válido
            }
        }

    }

?>