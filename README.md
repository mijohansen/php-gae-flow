# php-gae-flow
Deploy and devserver for Google App Engine for PHP. This is not a 
general liberary, but an opinionated way of doing "things". Google App 
Engine have no good way of storing secrets out of the box. I have 
choosen to add environment variables to `app.yaml` at deploy-time. This 
would work both from your local machine and from a pipeline. The secrets
I store in a normal propfile stored at users home-directory. This way it 
could be shared between applications which is deployed in the same 
Google App Engine project.

To have a consistent way between the production environment and the 
development environment we do the same with variables used during local 
development.

Needless to say, this approach will work best for soloprojects, or 
projects with few developers.

## Environment variables
These are stored in standard [INI files] which has to be placed in the
users home directory outside of the current project.

* `~/.{{myprojectkey}}/prod.env` for the production deploys.
* `~/.{{myprojectkey}}/dev.env` for the devserver.

This way they can be shared between applications in the same project
and is kept relatively secure outside the project. Not ideally, but at
least you don't have to check in files in the project.

## Scripts

This liberary contains two scripts, `deploy` and `serve`. They are 
configured with using the "extra" field of composer.json.a

### Deploy (to Google App Engine)

This uses the [gcloud command-line tool] for deploying. This has to be
on the PATH for this to work. 

#### Configuration

In composer.json add the following to the [extra data fields].

```json
{
  "extra": {
    "gcloud:project": "myprojectkey",
  }
}
```

### Serve (with the build in server)
This is just a simple wrapper around that the [PHP built-in webserver]. 
But it does call a router script which is configured to mimic the 
default behaviour when you deploy an application with Google App Engine. 
This does not read app.yaml to resolve static routes. But if you have a 
fairly simple setup that should not be a problem.

#### Configuration

In composer.json add the following to the [extra data fields]. 
```json
{
  "extra": {
    "serve:addr": "0.0.0.0",
    "serve:docroot": "/",
    "serve:entrypoint": "entrypoint.php",
    "serve:port": 2004
  }
}
```

## Tests
Test can be ran with `composer test` and the project is using phpunit as 
a testrunner.


[PHP built-in webserver]: https://www.php.net/manual/en/features.commandline.webserver.php
[gcloud command-line tool]: https://cloud.google.com/sdk/gcloud/
[INI files]: https://en.wikipedia.org/wiki/INI_file 
[extra data fields]: https://getcomposer.org/doc/04-schema.md#extra