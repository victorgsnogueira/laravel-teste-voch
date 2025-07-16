# Laravel Teste Voch

## Requisitos

- **PHP**: 8.2 ou superior
- **MySQL**: 8 ou superior
- **Composer**

## Como rodar o projeto

### 1. Clone o repositório
```bash
git clone https://github.com/victorgsnogueira/laravel-teste-voch.git
```

### 2. Navegue até a pasta do projeto
```bash
cd laravel-teste-voch
```

### 3. Rode os comandos para criar e rodar o container
```bash
./vendor/bin/sail up
```

### 4. Instale as dependências do backend e frontend
```bash
./vendor/bin/sail composer install
./vendor/bin/sail npm install
```

### 5. Configure o banco de dados

1. Entre no container do MySQL:
   ```bash
   ./vendor/bin/sail exec mysql bash
   ```

2. Acesse o MySQL como root:
   ```bash
   mysql -u root -ppassword
   ```

3. Execute as queries abaixo no MySQL:
   ```sql
   CREATE DATABASE voch;
   GRANT ALL PRIVILEGES ON voch.* TO 'sail'@'%';
   FLUSH PRIVILEGES;
   EXIT;
   ```

4. Saia do container:
   ```bash
   exit
   ```

### 6. Rode as migrações
```bash
./vendor/bin/sail artisan migrate
```

## 7. Populando o banco de dados com seeders (opcional)

Para popular o banco de dados com dados iniciais, utilize os seeders. Primeiro, certifique-se de que o banco de dados está configurado e as migrações foram executadas. Em seguida, rode o comando:

```bash
./vendor/bin/sail artisan db:seed
```

Se precisar resetar o banco de dados e rodar os seeders novamente, utilize:

```bash
./vendor/bin/sail artisan migrate:fresh --seed
```

### 8. Inicie o projeto
```bash
./vendor/bin/sail npm run dev
```



## Processamento de tarefas em segundo plano

Para processar tarefas como exportações, utilize o comando:
```bash
./vendor/bin/sail artisan queue:work
```