-- Creazione tabella finance_paymenttype mancante
CREATE TABLE IF NOT EXISTS `finance_paymenttype` (
  `id` int NOT NULL AUTO_INCREMENT,
  `paymenttype` varchar(60) DEFAULT NULL,
  `iduser` int DEFAULT NULL,
  `datainserimento` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Inserimento dati di esempio per finance_paymenttype
INSERT INTO `finance_paymenttype` (`paymenttype`, `iduser`, `datainserimento`) VALUES
('Contanti', 1, NOW()),
('Carta di Credito', 1, NOW()),
('Bonifico', 1, NOW()),
('PayPal', 1, NOW()); 