
# Complicado Vet

Desafio técnico.

## Instalação

Após clonar o projeto, use o Composer para instalar as dependências.
```bash
composer install
```

## Arquivo de configuração
Para configurar o serviço é so criar uma cópia do arquivo **'env.example'** e renomear para **'.env'** na raíz do projeto, e configurar o as configurações de banco de dados:
```bash
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=complicadovet
DB_USERNAME=root
DB_PASSWORD=
```

## Iniciando o serviço
Para iniciar o servidor de desenvolvimento executar o comando abaixo:

```bash
php artisan serve
```

## 1. Gerando o CSV
O arquivo **'complicadovet.sql'** se encontra na pasta **'public'** do projeto, para gerar os arquivos cliente.csv e animal.csv é so enviar uma requisição GET para a rota: **127.0.0.1:8000/api/gerar-csv**, os arquivos de saída também estarão na pasta **'public'**.

## 2. Upload de Arquivos
Para acessar a tela de uploads é so acessar o serviço local no seu navegador

```bash
http://127.0.0.1:8000
```

***Importante:***
Antes de upar os arquivos executar o comando abaixo para reinstalar a base de dados com a estrutura esperada. Esse comando vai **dropar** a base atual e **criar** a base para receber os arquivos 'csv'. (Cuidado com as configurações de banco para não usar esse comando em outra base de dados.)
```bash
php artisan migrate:fresh
```
## 3. Processamento
Após a acessar a tela de upload é so enviar os arquivos: primeiro o arquivo **cliente.csv**, após a mensagem de sucesso, retorne para tela de upload e envie o arquivo **animal.csv**, o resultado do processamento pode ser visto no banco de dados. Qualquer dúvida estou a disposição.