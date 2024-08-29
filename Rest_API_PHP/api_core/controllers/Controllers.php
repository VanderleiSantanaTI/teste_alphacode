<?php

class Controllers{

    public static function getAllCotacts(){
        // o dataBD é a conexao com o banco de dado no xamp databasephp

        global $servername, $dbname, $username, $password;
        
        $conn = new \PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        // Consultar o banco de dados

        $sql = " SELECT 
            id,
            nome,
            nascimento, 
            email, 
            celular, 
            telefone, 
            profissao, 
            celular_whatsapp, 
            recebe_email, 
            recebe_sms
                    FROM contato"; 

        $stmt = $conn->prepare($sql);
        $stmt->execute();
        
        $data_list = array(); // Inicializar a lista
        
        // Definir o array resultante para o modo associativo
        $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $data_list = $stmt->fetchAll(); // Adiciona todos os resultados à lista
        $response = Response::transformar_json(200, 'success', $data_list);
        // Enviar a resposta JSON
        echo $response;

       // Fechar conexão
        $conn = null;  
    }

    public static function getContactById($id){
        //echo "GET User with ID: $id";
        
        global $servername, $dbname, $username, $password; // Torna as variáveis globais acessíveis

        try {
        // Conectar ao banco de dados
            $conn = new \PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
            $conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        
            // Preparar a declaração SQL para recuperar os dados do usuário
            $sql = "SELECT * FROM contato WHERE id = :id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
            $stmt->execute();
        
            // Verificar se o usuário existe
            $contato = $stmt->fetch(\PDO::FETCH_ASSOC);
        
            if ($contato) {
                $response = Response::transformar_json(200, 'success', $contato);
                
            } else {
                $response = Response::transformar_json(404, 'Not found', null);
                }
            } catch (\PDOException $e) {
                $response = Response::transformar_json(500, 'Error retrieving user:', $e->getMessage());
                    
                }
            // Enviar a resposta JSON
            echo $response;
        
            // Fechar conexão
            $conn = null;

    }
    
    public static function createContact(){
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Receber os dados do POST em formato JSON
            $input = file_get_contents("php://input");
            $data = json_decode($input, true);
    
            // Atribuir os dados a variáveis, ou null se não estiverem definidos
            $nome = $data['nome'] ?? null;
            $nascimento = $data['nascimento'] ?? null;
            $email = $data['email'] ?? null;
            $celular = $data['celular'] ?? null;
            $telefone = $data['telefone'] ?? null;
            $profissao = $data['profissao'] ?? null;
            $celular_whatsapp = $data['celular_whatsapp'] ?? null;
            $recebe_email = $data['recebe_email'] ?? null;
            $recebe_sms = $data['recebe_sms'] ?? null;
    
            // Verificar se todos os campos necessários estão preenchidos
            if ($nome && $email && $profissao && $nascimento && $telefone && $celular && $celular_whatsapp !== null && $recebe_email !== null && $recebe_sms !== null) {
                try {
                    global $servername, $dbname, $username, $password; // Torna as variáveis globais acessíveis
    
                    // Conectar ao banco de dados
                    $conn = new \PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
                    $conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    
                    // Iniciar a transação
                    $conn->beginTransaction();
    
                    // Preparar a declaração SQL para inserir todos os campos no banco de dados
                    $sql = "INSERT INTO contato (
                        nome,
                        nascimento, 
                        email,
                        celular,
                        telefone, 
                        profissao,
                        celular_whatsapp, 
                        recebe_email, 
                        recebe_sms
                    ) VALUES (
                        :nome, 
                        :nascimento, 
                        :email, 
                        :celular, 
                        :telefone, 
                        :profissao,
                        :celular_whatsapp, 
                        :recebe_email, 
                        :recebe_sms
                    )";
    
                    $stmt = $conn->prepare($sql);
    
                    // Bind dos parâmetros para as respectivas variáveis
                    $stmt->bindParam(':nome', $nome);
                    $stmt->bindParam(':nascimento', $nascimento);
                    $stmt->bindParam(':email', $email);
                    $stmt->bindParam(':celular', $celular);
                    $stmt->bindParam(':telefone', $telefone);
                    $stmt->bindParam(':profissao', $profissao);
                    $stmt->bindParam(':celular_whatsapp', $celular_whatsapp, \PDO::PARAM_BOOL);
                    $stmt->bindParam(':recebe_email', $recebe_email, \PDO::PARAM_BOOL);
                    $stmt->bindParam(':recebe_sms', $recebe_sms, \PDO::PARAM_BOOL);
    
                    // Executar a declaração SQL
                    $stmt->execute();
    
                    // Confirmar a transação
                    $conn->commit();
    
                    // Construir a resposta
                    $response = Response::transformar_json(200, 'Novo contato inserido com sucesso!', [
                        'nome' => $nome,
                        'email' => $email
                    ]);
                } catch (\PDOException $e) {
                    // Reverter a transação em caso de erro
                    $conn->rollBack();
                    $response = Response::transformar_json(500, 'Erro ao inserir contato: ' . $e->getMessage());
                }
    
                // Fechar a conexão
                $conn = null;
            } else {
                $response = Response::transformar_json(400, 'Dados incompletos');
            }
        } else {
            $response = Response::transformar_json(405, 'Método de requisição inválido');
        }
    
        // Enviar a resposta JSON
        echo $response;
    }

    public static function updateContacts(){
        try {
            global $servername, $dbname, $username, $password; // Torna as variáveis globais acessíveis
            
            // Conectar ao banco de dados
            $conn = new \PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
            $conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            
            if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
                // Ler o corpo da requisição PUT
                $input = file_get_contents("php://input");
                $data = json_decode($input, true); // Decodificar JSON para um array associativo
    
                // Verificar se a decodificação foi bem-sucedida
                if (json_last_error() === JSON_ERROR_NONE) {
                    // Verificar se o ID está presente
                    $id = $data['id'] ?? null;
                    if ($id === null) {
                        $response = Response::transformar_json(400, 'ID do contato não fornecido');
                        echo $response;
                        return;
                    }
                    
                    $nome = $data['nome'] ?? null;
                    $nascimento = $data['nascimento'] ?? null;
                    $email = $data['email'] ?? null;
                    $celular = $data['celular'] ?? null;
                    $telefone = $data['telefone'] ?? null;
                    $profissao = $data['profissao'] ?? null;
                    $celular_whatsapp = $data['celular_whatsapp'] ?? null;
                    $recebe_email = $data['recebe_email'] ?? null;
                    $recebe_sms = $data['recebe_sms'] ?? null;
    
                    if ($nome && $nascimento && $email && $profissao && $telefone && $celular) {
                        // Preparar a declaração SQL para verificar se o contato existe
                        $sql = "SELECT * FROM contato WHERE id = :id";
                        $stmt = $conn->prepare($sql);
                        $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
                        $stmt->execute();
    
                        $contato = $stmt->fetch(\PDO::FETCH_ASSOC);
                        if ($contato) {
                            // Iniciar a transação
                            $conn->beginTransaction();
    
                            // Preparar a declaração SQL para atualizar o contato
                            $sql = "UPDATE contato SET 
                                        nome = :nome, 
                                        nascimento = :nascimento, 
                                        email = :email, 
                                        celular = :celular, 
                                        telefone = :telefone, 
                                        profissao = :profissao,
                                        celular_whatsapp = :celular_whatsapp, 
                                        recebe_email = :recebe_email, 
                                        recebe_sms = :recebe_sms 
                                    WHERE id = :id";
    
                            $stmt = $conn->prepare($sql);
    
                            // Associar os parâmetros às variáveis
                            $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
                            $stmt->bindParam(':nome', $nome);
                            $stmt->bindParam(':nascimento', $nascimento);
                            $stmt->bindParam(':email', $email);
                            $stmt->bindParam(':celular', $celular);
                            $stmt->bindParam(':telefone', $telefone);
                            $stmt->bindParam(':profissao', $profissao);
                            $stmt->bindParam(':celular_whatsapp', $celular_whatsapp, \PDO::PARAM_BOOL);
                            $stmt->bindParam(':recebe_email', $recebe_email, \PDO::PARAM_BOOL);
                            $stmt->bindParam(':recebe_sms', $recebe_sms, \PDO::PARAM_BOOL);
    
                            // Executar a declaração SQL
                            $stmt->execute();
    
                            // Confirmar a transação
                            $conn->commit();
    
                            // Retornar os dados do contato atualizado
                            $response = Response::transformar_json(200, 'Contato atualizado com sucesso', [
                                'id' => $id,
                                'nome' => $nome,
                                'email' => $email
                            ]);
                        } else {
                            $response = Response::transformar_json(404, 'Contato não encontrado');
                        }
                    } else {
                        $response = Response::transformar_json(400, 'Dados incompletos');
                    }
                } else {
                    $response = Response::transformar_json(400, 'JSON inválido');
                }
            } else {
                $response = Response::transformar_json(405, 'Método de requisição inválido');
            }
        } catch (\PDOException $e) {
            if (isset($conn)) {
                $conn->rollBack();
            }
            $response = Response::transformar_json(500, 'Erro ao atualizar contato: ' . $e->getMessage());
        }
        
        // Enviar a resposta JSON
        echo $response;
    
        // Fechar conexão
        $conn = null;
    }

    public static function delContactById($id){
        try {
            global $servername, $dbname, $username, $password; // Torna as variáveis globais acessíveis
            // Conectar ao banco de dados
            $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
            // Preparar a declaração SQL para recuperar os dados do contato
            $sql = "SELECT * FROM contato WHERE id = :id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
    
            // Verificar se o contato existe
            $contato = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($contato) {
                // Preparar a declaração SQL para deletar o contato
                $sql = "DELETE FROM contato WHERE id = :id";
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                $stmt->execute();
    
                // Retornar os dados do contato deletado
                $response = Response::transformar_json(200, 'Contato excluido com sucesso', $contato);
            } else {
                $response = Response::transformar_json(404, 'Contato nao encontrado');
            }
        } catch (PDOException $e) {
            $response = Response::transformar_json(500, 'Erro ao excluir contato: ' . $e->getMessage());
        }

    
        // Enviar a resposta JSON
        echo $response;
    
            // Fechar conexão
        $conn = null;
    }

    public static function updateTables(){
        // o dataBD é a conexao com o banco de dado no xamp databasephp

        global $servername, $dbname, $username, $password;
        
        $conn = new \PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        // Consultar o banco de dados

        $sql = " SELECT 
            nome,
            nascimento, 
            email, 
            celular
            FROM contato"; 

        $stmt = $conn->prepare($sql);
        $stmt->execute();
        
        $data_list = array(); // Inicializar a lista
        
        // Definir o array resultante para o modo associativo
        $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $data_list = $stmt->fetchAll(); // Adiciona todos os resultados à lista
        $response = Response::transformar_json(200, 'success', $data_list);
        // Enviar a resposta JSON
        echo $response;

       // Fechar conexão
        $conn = null;         
    }

}