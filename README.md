TRAX Sync
=========

## Installation

- `composer require trax/sync`
- `php artisan vendor:publish --tag=trax-sync-config`
- `php artisan migrate`


## Commands

Configure your connections in `config/trax-sync.php`, then:

- `php artisan trax:push <connection>` to start or continue synchronization
- `php artisan trax:push <connection> --all` to restart synchronization


## License and copyright

Distributed under the [EUPL 1.2 license](https://eupl.eu/1.2/en/).

Copyright 2019 Sébastien Fraysse, http://fraysse.eu, <sebastien@fraysse.eu>.



