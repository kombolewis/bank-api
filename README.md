## Bank API

A simple API built in Laravel to simulate the functionalities of a small banking institution

## Documentation
Documentation is hosted at https://documenter.getpostman.com/view/8835770/2s8YzQVibF


## Installation
The application is setup to utilize docker containers to run. The initilization, however, partly depends on local composer and php to complete.
You therefore require, php, composer & docker to get started.
If you have these, get started by running the command below:

```
make setup

```

This will initiliaze the environment files, run migrations & seed among others.
Once it completes, ensure to copy the `Client ID` and `Client secret` from the `Password grant client` output message from the setup process above. Fill the `PASSPORT_CLIENT_ID` and `PASSPORT_CLIENT_SECRET` values in the  `.env` file  respectfully

The application is basically setup and ready to go. You can run tests through

```
make test

```
 
In order to explore the endpoints, an admin user is setup during setup above to  aid with this.
The credentials are as below:

```
{
	"username":"admin@example.com",
	"password":"password"
}

```
The auth login endpoint is at `localhost/api/auth/login`

All the best.