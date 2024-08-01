## SETUP

1. clone repository
2. go to the project and run command cp .env.example .env 
3. make sure that you are connected to your DB
example
```

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=8889
DB_DATABASE=admin
DB_USERNAME=root
DB_PASSWORD=root
DB_SOCKET=/Applications/MAMP/tmp/mysql/mysql.sock

```
4. Once your .env file has been created and your DB is connected
run php artisan migrate:fresh --seed 

5. Run php artisan serve --port=8000
your URL would look like ```http://127.0.0.1:8000/api/{apis}```
This will be used in frontend
NOTE: whatever your port is, the important thing is to copy it in the frontend base URL

6. Test the APIs via Postman

### URLS

login
http://127.0.0.1:8000/api/login

```
{
    "email": "admin1@example.com",
    "password": "password"
}
```

get value of the "token"
and use it all over the request that requires login


get users
http://127.0.0.1:8000/api/1/my-users
in Authorization tab
choose the "Bearer Token" type
and paste the token from the one you copied the login result

update client 
http://127.0.0.1:8000/api/{adminId}/update-my-client/{clientId}
```
{
    "first_name": "Trust",
    "last_name": "God",
    "birthday": "1992-09-13",
    "contact_no": "+639453311165",
    "password": "admin123",
    "email": "swuckert@example.org",
    "interest_ids": [5,3,4],
    "client_id": 17
}

```

delete client 
http://127.0.0.1:8000/api/{adminId}/delete-my-client/{clienId}

create client
http://127.0.0.1:8000/api/1/create-my-client
```{
    "first_name": "Ryan",
    "last_name": "Reynolds",
    "birthday": "1992-09-13",
    "contact_no": "+639453311165",
    "password": "admin123",
    "password_confirmation": "admin123",
    "email": "burt.example@example.org",
    "interest_ids": [1,6,3]
}
```




admin registration
http://127.0.0.1:8000/api/register
```
{
    "first_name": "Huge",
    "last_name": "Jacked Man",
    "email": "hey@gmail.com",
    "password": "admin123",
    "birthday": "1992-11-14"
}

```