# Prefill from JSON Bot
![CI](https://github.com/airslate-oss/php-example-bots/actions/workflows/main.yml/badge.svg)

This is an example of the airSlate bot that fills in fields with data from a JSON file. More information can be found in the documentation.

## Installation

1. Clone the repository:

```bash
git clone https://github.com/airslate-oss/php-example-bots.git
```

2. Open the bot's directory:

```bash
cd php-example-bots/bots/prefill-from-json-bot
```

3. Install composer packages:

```bash
composer install --ignore-platform-reqs
```

4. Create an initial `.env` file:

```bash
make prepare
```

5. Start the container with the bot:

```bash
make up
```

The bot is now ready to receive requests! Next, you need to configure the bot in the Developer Console.

## Usage

1. Start the container:

```bash
make up
```

2. Check the quality of the code:

```bash
make cs
```

3. Run the tests:

```bash
make test
```

4. Stop the container:

```bash
make down
```

5. Build an image from the scratch:

```bash
make build
```

## Contributing

Pull requests are welcome. For major changes, please open an issue first
to discuss what you would like to change.

## Support

Should you have any question, any remark, or if you find a bug, please [open an issue](https://github.com/airslate-oss/php-example-bots/issues).

## License

[Apache Licence 2.0](https://choosealicense.com/licenses/apache-2.0/)
