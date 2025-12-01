⚡ Comando Rápido para Testar

  # 1. Criar banco
  mysql -u root -p12345678 < /home/luis-miguel/Downloads/biliotecaPHP/app/script_banco.txt

  # 2. Criar usuário admin
  mysql -u root -p12345678 -e "USE biblioteca; INSERT INTO usuario (nome, usuario, senha) VALUES ('Admin', 'admin', '123456');"

  # 3. Iniciar servidor
  cd /home/luis-miguel/Downloads/biliotecaPHP/biblioteca && php -S localhost:8080

  # 4. Abrir no navegador: http://localhost:8080/login/login.php
