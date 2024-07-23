## My Pet API.
Uma simples API que permite que usuários cadastrados e autenticados via JWT, cadastrem informações sobre seus pets.

### Setup:

Este projeto utiliza o Docker para lidar com containers. Portanto o único requisito fundamental é que você tenha o docker instalado antes de fazer o clone do repositório.

#### 1 Passo - Faça um clone do repositório na sua máquina:
```
 git clone git@github.com:DevAlysonh/my-pets-api.git
```
#### 2 Passo - Copie os arquivos de configuração (.env):
```
cp .env.example .env
cp .env.example .env.testing
```
#### 3 Passo - Adicione as credenciais de acesso ao banco, nos dois arquivos, conforme a seguir:
##### .env:
```
DB_CONNECTION=mysql
DB_HOST=pets-db
DB_PORT=3306
DB_DATABASE=my_pets
DB_USERNAME=root
DB_PASSWORD=rootsecret
```
##### .env.testing:
```
DB_CONNECTION=mysql
DB_HOST=pets-db
DB_PORT=3306
DB_DATABASE=my_pets_testing
DB_USERNAME=root
DB_PASSWORD=rootsecret
```

#### 4 Passo - Faça o build da aplicação:
```
docker-compose up -d --build
```
Se tudo ocorreu bem até aqui, os containers devem estar UP, e você pode seguir para o próximo passo.

#### 5 Passo - Instale as dependências do projeto, e rode as migrations:
```
docker exec -it pets-api composer install
```
```
docker exec -it pets-api php artisan key:generate
```
```
docker exec -it pets-api php artisan key:generate --env=testing
```
```
docker exec -it pets-api php artisan migrate
```
```
docker exec -it pets-api php artisan migrate --env=testing
```
```
docker exec -it pets-api php artisan jwt:secret
```
```
docker exec -it pets-api php artisan jwt:secret --env=testing
```

Se tudo ocorreu bem, nossa aplicação deve estar disponível em: http://localhost:80/api/ . E a documentação com swagger disponível em: http://localhost/api/documentation#/

## Utilizando a API
### Autenticação:

#### /api/auth/register
#### Método: POST
Este endpoint permite cadastrar um novo usuário.
##### Campos obrigatórios:
```
{
  "name": "string",
  "email": "user@example.com",
  "password": "string",
  "password_confirmation": "string"
}
```
Ao criar um novo registro de usuário, uma instancia do usuário já autenticada é devolvida na sessão, junto com o JWT Token, portanto, ao registrar o usuário, o restante da API já está disponível.

#### /api/auth/login
#### Método: POST
Este endpoint permite que um usuário cadastrado faça login na aplicação.
##### Campos obrigatórios:
```
{
  "email": "user@example.com",
  "password": "string",
}
```
Quando logado, um JWT token é retornado, permitindo que o usuário faça outras requisições à API. Se as credenciais falharem, um erro com status 401 será retornado, indicando que o usuário não tem autorização para logar.

#### /api/me
#### Método: GET
Este endpoint retorna o usuário autenticado na sessao.
##### Exemplo de resposta:
```
{
    "id": 2,
    "name": "Test User",
    "email": "testUser@example.com",
    "email_verified_at": null,
    "created_at": "2024-07-23T04:26:43.000000Z",
    "updated_at": "2024-07-23T04:26:43.000000Z"
}
```
Se não houver usuário logado, um erro 401 será retornado indicando não autorizado.

#### /api/logout
#### Método: POST
Este endpoint encerra a sessão do usuário.
Se não houver usuário logado, um erro 401 será retornado indicando não autorizado.

#### /api/refresh
#### Método: POST
Este endpoint atualiza a sessão do usuário, gerando um novo token de autenticação, caso o dele esteja vencido, ou perto de vencer.
##### Exemplo de resposta:
```
{
    "access_token": "jwtToken.....",
    "token_type": "bearer",
    "expires_in": 3600
}
```
Se não houver usuário logado, um erro 401 será retornado indicando não autorizado.

### Gerenciando Pets:

#### /api/pets/my_pets
#### Método: GET
Este endpoint retorna uma lista de animais de estimação do usuário autenticado.
##### Exemplo de resposta:
```
{
    "user_pets": [
        {
            "id": 2,
            "name": "Luna",
            "age": 5,
            "user_id": 2,
            "breed_id": 2,
            "specie_id": 1,
            "created_at": "2024-07-23T04:27:06.000000Z",
            "updated_at": "2024-07-23T04:27:06.000000Z"
        },
        {
            "id": 6,
            "name": "rock",
            "age": 5,
            "user_id": 2,
            "breed_id": 2,
            "specie_id": 1,
            "created_at": "2024-07-23T05:20:35.000000Z",
            "updated_at": "2024-07-23T05:20:35.000000Z"
        },
        {
            "id": 7,
            "name": "nasha",
            "age": 5,
            "user_id": 2,
            "breed_id": 2,
            "specie_id": 1,
            "created_at": "2024-07-23T05:20:41.000000Z",
            "updated_at": "2024-07-23T05:20:41.000000Z"
        }
    ]
}
```
Se não houver usuário logado, um erro 401 será retornado indicando não autorizado. Se o usuário autenticado não tiver nenhum animal cadastrado, será retornado um erro 404, indicando que nenhum dado foi encontrado.

#### /api/pets/
#### Método: POST
Este endpoint permite cadastrar um novo pet.
##### Campos obrigatórios:
```
{
  "name": "string",
  "age": "string",
  "breed": "string",
  "specie": "string"
}
```
Se não houver usuário logado, um erro 401 será retornado indicando não autorizado.

#### /api/pets/{petId}
#### Método: GET
Este endpoint permite buscar um pet do cliente, e exibir o perfil do mesmo.
##### Campos obrigatórios:
```
{
    "name": "Luna",
    "age": 5,
    "owner": {
        "id": 2,
        "name": "Test User"
    },
    "breed": {
        "id": 2,
        "name": "rotweiller",
        "specie_id": 1,
        "created_at": "2024-07-23T04:27:06.000000Z",
        "updated_at": "2024-07-23T04:27:06.000000Z"
    },
    "specie": {
        "id": 1,
        "name": "cachorro",
        "created_at": "2024-07-23T04:09:41.000000Z",
        "updated_at": "2024-07-23T04:09:41.000000Z"
    }
}
```
Se não houver usuário logado, um erro 401 será retornado indicando não autorizado. Se o usuário tentar acessar um animal inexistente, um erro 404 será retornado indicando que nada foi encontrado. Se o usuário tentar acessar um animal que não o pertence, um erro 401 será retornado, indicando não autorizado.
