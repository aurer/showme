# Showme

A service to show get and post variables in an easy to read page.

## Bookmarklets

There are two bookmarklets:

1. **ShowMeGet** - This simply opens Showme with any request parameters you have in the url at the time.
2. **ShowMeForms** - This finds any forms on the page and alters the action so it submits to Showme, click again to revert the form back to it's original state.

## Running this project

### Run (the app) with Go

1. First you need to start the database container with `docker compose up -d db`. This will spin up a Postgres instance using the default host (localhost) and default password. These can be overridden with the **DB_HOST** and **DB_PASS** environment variables.
2. Make sure all Go dependencies are installed: `go mod download`
3. Start the app with `go run .` and you should be able to see the front-end at http://localhost:3000

### Run with Docker Compose

1. To start the app with the default configuration run: `docker compose up -d --build`

## Environment variables

The default environment variables are as follows and can be overridden with a .env file:
* `DB_HOST=localhost`
* `DB_PASS=PostGres`
* `PORT=3000`