Customer Order Parser
=================

The input file is read in chunks and multiple output files may be created based on chunk size.

Setup
---------

```bash
docker-compose -f docker/docker-compose.yml up -d --build

docker exec order_parser_cli composer install
```

Running the parser command
------------------------

```bash
docker exec order_parser_cli php bin/console app:parse-order

Options:
--input-file-url="https://url_for_input_file"
--output-file-format=csv [Allowed values: csv, jsonl, yaml] [Default: csv]
```