Customer Order Parser
=================

Setup
---------

docker-compose -f docker/docker-compose.yml up -d --build\
docker exec order_parser_cli composer install

Running the parser command
------------------------
docker exec order_parser_cli php bin/console app:parse-order

Options:\
--input-file-url="https://url_for_input_file"\
--output-file-format=csv [Allowed values: csv, jsonl, yaml] [Default: csv]