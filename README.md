## Task
You need to develop API service:

There are two entities - articles (id, title) and tags (id, name).
Tags can be bounded to articles (many to many).

API methods:

- get article by id
- create/edit article with tags bounded. Query must return article
- remove article
- create/edit tag
- get list of tags
- get list of articles with filtering by tags. If query has more than one tag, API should return only articles having all tags from query


The response must be in JSON.

## Prerequisites for project running
Having Docker is a must.

## Running project
To start this project just run `make start` in this directory. This will download all docker images and will start a server on `http://localhost:8000/`.

To stop docker containers run `make stop`

To login to bash in php container run `make bash`