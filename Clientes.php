<?php 

    class Clientes { 

        private $id; //Id da Classe 
        private $nome; //Nome do Cliente
        private $genero; //Genero do Cliente
        private $data_nascimento; //Data de Nascimento
        private $endereco; //Endereço do Cliente 
        private $numero; //Numero do endereço 
        private $complemento; //Complemento
        private $cidade; //Cidade do endereço 
        private $estado; //Estado do endereço 
        private $cep; //Cep do endereço
        private $email; //E-mail do cliente 
        private $telefone; //Telefone do Cliente 
        private $cpf_cnpj; //CPF ou CNPJ do Cliente 
        private $data_cadastro; //Data do Cadastro 
        private $status; //Status do Cliente 
        private $observacao; //Observação se Necessário 
        private $login; //Login do Cliente 
        private $senha; //Senha do Cliente 

        public function __construct($nome, $endereco, $genero, $data_nascimento, $numero, $complemento = null, $cidade, $estado, $cep,
            $email, $telefone, $cpf_cnpj, $data_cadastro, $status, $observacao = null, 
            $login, $senha) { 
            $this->nome = $nome; 
            $this->genero = $genero;
            $this->data_nascimento = $data_nascimento;
            $this->endereco = $endereco;
            $this->numero = $numero;
            $this->complemento = $complemento;
            $this->cidade = $cidade;
            $this->estado = $estado;
            $this->cep = $cep;    
            $this->email = $email;
            $this->telefone = $telefone;
            $this->cpf_cnpj = $cpf_cnpj;
            $this->data_cadastro = $data_cadastro;
            $this->status = $status;
            $this->observacao = $observacao;
            $this->login = $login;
            $this->senha = $senha;
        }

        //Getters 
        public function getId() { 
            return $this->id;
        }

        public function getNome() { 
            return $this->nome;
        }

        public function getGenero() { 
            return $this->genero;
        }

        public function getDataNascimento() { 
            return $this->data_nascimento;
        }

        public function getEndereco() { 
            return $this->endereco;            
        }

        public function getNumero() { 
            return $this->numero;            
        }

        public function getComplemento() { 
            return $this->complemento;            
        }

        public function getCidade() { 
            return $this->cidade;            
        }

        public function getEstado() { 
            return $this->estado;            
        }

        public function getCep() { 
            return $this->cep;            
        }

        public function getEmail() { 
            return $this->email;
        }

        public function getTelefone() { 
            return $this->telefone;
        }

        public function getCpfCnpj() { 
            return $this->cpf_cnpj;
        }

        public function getDataCadastro() { 
            return $this->data_cadastro;
        }

        public function getStatus() { 
            return $this->status;
        }

        public function getObservacao() { 
            return $this->observacao;
        }

        public function getLogin() { 
            return $this->login;
        }

        public function getSenha() { 
            return $this->senha;
        }


        //Setters
        public function setId($id) { 
            $this->id = $id;
        }

        public function setNome($nome) { 
            $this->nome = $nome;
        }

        public function setGenero($genero) { 
            $this->genero = $genero;
        }

        public function setEndereco($endereco) { 
            $this->endereco = $endereco;            
        }

        public function setNumero($numero) { 
            $this->numero = $numero;            
        }

        public function setComplemento($complemento) { 
            $this->numero = $numero;            
        }

        public function setCidade($cidade) { 
            $this->cidade = $cidade;            
        }

        public function setEstado() { 
            $this->estado = $estado;            
        }

        public function setCep() { 
            $this->cep = $cep;            
        }

        public function setEmail($email) { 
            $this->email = $email;
        }

        public function setTelefone($telefone) { 
            $this->telefone = $telefone;
        }

        public function setCpfCnpj($cpf_cnpj) { 
            $this->cpf_cnpj = $cpf_cnpj;
        }

        public function setDataCadastro() { 
            $this->data_cadastro = $data_cadastro;
        }

        public function setStatus() { 
            $this->status = $status;
        }

        public function setObservacao() { 
            $this->observacao = $observacao;
        }

        public function setLogin() { 
            $this->login = $login;
        }

        public function setSenha() { 
             $this->senha = $senha;
        } 
    
    }
?> 