CREATE TABLE `estados` (
  `id` INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
  `sigla` VARCHAR(2) NOT NULL,
  `estado` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `ix_estado`(`estado`)
)
ENGINE = MyISAM
CHARACTER SET utf8 COLLATE utf8_general_ci;

INSERT INTO `estados` (id, sigla, estado ) VALUES 
	(1, 'AC', 'Acre'),
	(2, 'AL', 'Alagoas'),
	(3, 'AM', 'Amazonas'),
	(4, 'AP', 'Amapá'),
	(5, 'BA', 'Bahia'),
	(6, 'CE', 'Ceará'),
	(7, 'DF', 'Distrito Federal'),
	(8, 'ES', 'Espírito Santo'),
	(9, 'GO', 'Goiás'),
	(10, 'MA', 'Maranhão'),
	(11, 'MG', 'Minas Gerais'),
	(12, 'MS', 'Mato Grosso do Sul'),
	(13, 'MT', 'Mato Grosso'),
	(14, 'PA', 'Pará'),
	(15, 'PB', 'Paraíba'),
	(16, 'PE', 'Pernambuco'),
	(17, 'PI', 'Piauí'),
	(18, 'PR', 'Paraná'),
	(19, 'RJ', 'Rio de Janeiro'),
	(20, 'RN', 'Rio Grande do Norte'),
	(21, 'RO', 'Rondônia'),
	(22, 'RR', 'Roraima'),
	(23, 'RS', 'Rio Grande do Sul'),
	(24, 'SC', 'Santa Catarina'),
	(25, 'SE', 'Sergipe'),
	(26, 'SP', 'São Paulo'),
	(27, 'TO', 'Tocantins')
;