## Google Drive Files Fetcher Demo.

## Create your Google Drive API keys

Detailed information on how to obtain your API ID and secret:

-   [Getting your Client ID and Secret](https://console.developers.google.com/)


## Set up this project locally

```
git clone git@github.com:NassarX/gd-files-fetcher.git
```

##Install Dependencies
```
$ composer install
```

## Update `.env` file

Add the keys you created to your `.env` file. You can copy the [`.env.example`](.env.example) file and fill in the blanks.

```
$ cp .env.example .env
```

```
GOOGLE_SERVER_KEY=API_KEY
GOOGLE_CLIENT_ID=xxx.apps.googleusercontent.com
GOOGLE_APP_SECRET=APP_SECRET
GOOGLE_REDIRECT=http://localhost:8000/auth/google/callback
```

from the `.env` file set `DB_DATABASE`, `DB_USERNAME`  and `DB_PASSWORD`

Generate the app key
```
$ php artisan key:generate
```

then run the migrations
```
$ php artisan migrate
```

<br>
finally

```
$ php artisan serve
```

and now, you can access the application by `http://localhost:8000`
