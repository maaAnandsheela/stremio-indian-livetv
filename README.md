# stremio-indian-livetv


This is a [Stremio](https://www.stremio.com/) add-on for Indian Live TV channels from zee5,sony and mixed channels from mxplayer site. Contains More than 250+ channels combined from all three catalogs.

It is a php and apache app and can be run on major free hosting service.

Demo : https://stremio-indian-livetv-eta.now.sh/manifest.json

## Features

- Includes custom Configuration for most of the things.
- Includes Home Feed of beeg.com on discover tab of stremio and various other tags of beeg which can be configured before deploying.
- Supports Docker Installation.
- Caching the requests in file cache.
- Since its php and apache app can be deployed on any free hosting with cloudflare to support free ssl.
- Custom cache time for feed and meta content.
- Includes now.json for deploying on zeit.co

## Deploying with Docker (preferred for localhost)

To Run on Docker Container

```bash
git clone https://github.com/maaAnandsheela/stremio-indian-livetv
cd stremio-indian-livetv
docker build -t stremio-indian-livetv .
docker run --name indlivtv -d -p 80:80 stremio-indian-livetv
```

To Stop the container

```bash
docker stop indlivtv
```

## Deploying on zeit.co

Preffered zeit.co because it provides india region for hosting. The addon has to be hosted on India region.

- Already Includes now.json to deploy on zeit.co
- Make sure cache status is false before deploying on Zei. Default is false.


## Configuration 

- Includes a config.php to config the default variables before setup.
- `cache_status` to define cacahe status.
- `cache_path`  to define file cache path.
- `cache_catalog_ttl` expire time for catalog feeds cache and also valid for genres.
- `catalog_catalog_ttl` expire time for meta of cahe streams.
- And other basic manifest variables can also be configured from config.php

## Known Issues

- zee5 streams not working on desktop stremio. ALready contacted stremio about that.


## Screenshots

![Screenshot](/captures/screenshot1.png)

![Screenshot](/captures/screenshot2.png)

![Screenshot](/captures/screenshot3.png)
