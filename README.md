## Getting Started

1. If not already done, [install Docker Compose](https://docs.docker.com/compose/install/) (v2.10+)
2. Run `make build` to build fresh images
3. Run `make up` to set up and start a fresh Symfony project
4. Run `make start` to build and start the Docker containers
5. Open `https://localhost` in your favorite web browser and [accept the auto-generated TLS certificate](https://stackoverflow.com/a/15076602/1352334)
6. Run `make down` to stop the Docker containers.

## Usage
1. To run scraping please run `make scrape` 
2. To open cli please run `make bash`
3. In bash, you can run `composer scrape` to scrape data from the website

## Available localhost endpoints
1. `GET https://localhost/product` - get all scraped data
2. `GET https://localhost/product/{id}` - get specific product by id
3. `DELETE https://localhost/product/{id}` - delete specific product by id
