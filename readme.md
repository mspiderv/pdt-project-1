# PDT Project 1 by Matej Víťaz

## Installation
```
$ git clone https://github.com/mspiderv/pdt-project-1
$ cd pdt-project-1
$ sudo chmod a+x ./install
$ sudo ./install.sh
```

## Import map data
```
$ osm2pgsql -H localhost -s -U postgres -d {DB_NAME} --latlong {FILE}
```
