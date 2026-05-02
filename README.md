# Quote
Simple REST API to get Fund quotes from www.finanzen.ch

```shell
curl 'https://www.finanzen.ch/ajax/SearchController_SuggestJson' \
  --compressed \
  -X POST \
  -H 'User-Agent: Mozilla/5.0 (X11; Linux x86_64; rv:150.0) Gecko/20100101 Firefox/150.0' \
  -H 'Content-Type: application/json' \
  --data-raw '{"query":"ch0002789847"}'
```