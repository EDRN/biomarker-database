# Notes

Various notes.


## Docker

Please note that the following environment varialbes are pertinent:

-   `EDRN_DATA_DIR` set to where you'd like to persist data. There should be a
    `mysql` subdirectory there that the MySQL image can use that contains a
    prepared database (see "Preparing the Database" below).
-   `EDRN_FOCUSBMDB_PORT` set to the port number you'd like things to listen
    on; this maps the app's container's port to this number, from which you
    can then reverse-proxy to the world.



### Image Builds

To build the image, run:

    docker image build --tag edrn-bmdb .


### Preparing the Database

First, extract the DB from the current operational site with `mysqldump`.

Then: `mkdir -p data/entrypoint data/mysql` and copy the `dump.sql` file into
`data/entrypoint`.

Then:

    docker container run \
        --rm \
        --name initdb \
        --volume `pwd`/data/entrypoint:/docker-entrypoint-initdb.d \
        --volume `pwd`/data/mysql:/var/lib/mysql \
        --env MYSQL_ROOT_PASSWORD=4CLJvuxyo9Tud2ag \
        --env MYSQL_DATABASE=cbmdb \
        --env MYSQL_USER=cbmdb \
        --env MYSQL_PASSWORD=cbmdb \
        mysql:5.6.47

Use something other than `SECRET` for the password and save the
`MYSQL_ROOT_PASSWORD` some place safe in case it's needed. From another window
run `docker container stop initdb` after a few minutes.


### Starting and Stopping

To get everything going, set the EDRN_DATA_DIR and EDRN_FOCUSBMDB_PORT as
needed and then find the directory with the `docker-compose.yaml` file for
this app and run:
    
    docker-compose --project-name edrnfocusbmdb up --detach

You can check on things with:

    docker-compose --project-name edrnfocusbmdb logs --follow

And bring it all down with:

    docker-compose --project-name edrnfocusbmdb down

Maintenance and poking around? Try:

docker-compose --project-name edrnfocusbmdb exec focusbmdb-app /bin/bash
