CREATE TABLE contato (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    nascimento DATE NOT NULL,
    email VARCHAR(255) NOT NULL,
    celular VARCHAR(15) NOT NULL,
    telefone VARCHAR(15) NOT NULL,
    profissao VARCHAR(100) NOT NULL,
    celular_whatsapp BOOLEAN NOT NULL DEFAULT 0,
    recebe_email BOOLEAN NOT NULL DEFAULT 0,
    recebe_sms BOOLEAN NOT NULL DEFAULT 0
);
