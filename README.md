# Prefill from JSON Bot
![CI](https://github.com/airslate-oss/prefill-bot-php-example/actions/workflows/main.yml/badge.svg)

This is an example of the airSlate bot, written in pure PHP. This bot fills the fields with data from a JSON file. More information can be found in the documentation.

## Installation

1. Clone the repository:

```bash
git clone https://github.com/airslate-oss/prefill-bot-php-example.git
```

2. Open the bot's directory:

```bash
cd prefill-bot-php-example
```

3. Run the initial setup command to install composer packages. This command will also create an `.env` file and generate an application key:

```bash
docker compose run --rm setup
```

4. Start the container with the bot:

```bash
docker-compose up -d
```

The bot is now ready to receive requests! Next, you need to configure the bot in the Developer Console.

## Usage

1. Start the container:

```bash
docker-compose up -d
```

2. Open the `bash` inside the container:

```bash
docker-compose exec app bash
```

3. Check the quality of the code, run it inside the container:

```bash
vendor/bin/pint --test
vendor/bin/phpstan analyse -c phpstan.neon
vendor/bin/phpmd ./app text codesize
```

4. Run the tests, run it inside the container:

```bash
vendor/bin/phpunit
```

5. Stop the container:

```bash
docker-compose down
```

6. Build an image from the scratch:

```bash
docker build -t "airslate-oss/prefill-bot-php-example" docker/8.0
```

## Contributing

Pull requests are welcome. For major changes, please open an issue first
to discuss what you would like to change.

## Support

Should you have any question, any remark, or if you find a bug, please [open an issue](https://github.com/airslate-oss/prefill-bot-php-example/issues).

## License

[Apache Licence 2.0](https://choosealicense.com/licenses/apache-2.0/)
