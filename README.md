## Bank API

A simple API built in Laravel to simulate the functionalities of a small banking instituion


## Installation
Requires docker, php and composer to run:

`make setup`

This will initiliaze the environment files, run migrations and seed, and get you ready to go
Copy the `Client ID` and `Client secret` from the `Password grant client` during setup above to the `.env` file `PASSPORT_CLIENT_ID` and `PASSPORT_CLIENT_SECRET` respectfully

You can run tests at this point

`make test`

The setup seed creates an admin user to help exploring the applications endpoints.The credentials are as below
```
{
	"username":"admin@example.com",
	"password":"password"
}

```
The auth login endpoint is at `localhost/api/auth/login`

All the best.
m