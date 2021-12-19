#!/usr/bin/env bash

# Start the sql proxy
cloud_sql_proxy  -credential_file=/var/www/.secrets/New-life-e84ef312c8a6.json -instances=$CLOUDSQL_INSTANCE=tcp:3306 &

# Execute the rest of your ENTRYPOINT and CMD as expected.
exec "$@"