# php-gae-tools
Deploy, secret management and devserver for Google App Engine for PHP. This is not a general liberary, but an opinionated
way of doing "things". Google App Engine have no good way of storing secrets out of the box. I have choosen to add environment
variables to `app.yaml` at deploy-time. This would work both from your local machine and from a pipeline. The secrets
I store in a normal propfile stored at users home-directory. This way it could be shared between applications which is
deployed in the same Google App Engine project.

To have a consistent way between the production environment and the development environment we do the same with variables
used during local development.

Needless to say, this approach will work best for soloprojects, or projects with few developers.

## Scripts
This liberary contains two scripts, `deploy` and `serve`. They are configured with using the "extra" field of composer.json.a

### Deploy (to Google App Engine)

#### Configuration
```json
  "extra": {
    "gcloud:project": "myprojectkey",
  }
```

### Serve (with the build in server)

#### Configuration
```json
  "extra": {
    "devserve:entrypoint": "entrypoint.php",
    "devserve:port": 2004,
    "devserve:public-folder": "/"
  }
```

## Tests
Test can be ran with `composer test` and the project is using phpunit as a testrunner.


