# Unicred – APIs de Cooperados (PHP + Java) com Docker
CRUD de Cooperados implementado em duas APIs que compartilham o mesmo MySQL:

PHP/Laravel (porta 8080)
Java/Spring Boot (porta 8081)

### Como rodar 

```bash 
git clone <seu-repo> unicred
cd unicred
cp .env.example .env

# na raiz 
docker compose up -d --build

#verificar se tabela esta presente no docker mysql 
docker compose exec db mysql -u unicred -punicred -e "USE unicred; SHOW TABLES LIKE 'cooperados';"

```

### URLs

- PHP (Laravel): http://localhost:8080/api/cooperados
- Java (Spring Boot): http://localhost:8081/api/cooperados


#### Testar
```bash 
#testar
cd java-api
./mvnw test
# se for container 
docker run --rm -v "$PWD/java-api":/app -w /app maven:3.9.8-eclipse-temurin-21 mvn -q test

# php 
cd unicred-php
docker compose exec app php artisan test

```

 - Collection postaman em : unicred-php/UNICRED.postman_collection.json

 #### exemplo de payload 
 {
  "nome": "Fulano de Tal",
  "cpfCnpj": "111.444.777-35",
  "data": "1990-01-02",
  "rendaFaturamento": 12000.50,
  "telefone": "+55 (41) 98888-8888",
  "email": "fulano@ex.com"
}
