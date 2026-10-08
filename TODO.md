# TODO

- [ ] Make the repo private. First give the server a read-only GitHub deploy key, because
      `scripts/lightsail-launch.sh` and any rebuilds clone over anonymous HTTPS.
      Update the clone URL in the launch script and add the key to the instance.
