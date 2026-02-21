# Upgrade para Laravel 12

Este projeto já foi preparado com constraints compatíveis no `composer.json`.

## Estado atual

- `laravel/framework`: `^12.0`
- `laravel/sanctum`: `^4.0`
- `laravel/tinker`: `^2.10.1`
- `laravel/breeze`: `^2.3`
- `nunomaduro/collision`: `^8.6`
- `phpunit/phpunit`: `^11.5.3`
- `php`: `^8.2` (ambiente atual está em PHP 8.3)

## Passos para concluir (quando houver internet)

1. Atualizar lock e vendor:

```bash
composer update laravel/framework laravel/sanctum laravel/tinker laravel/breeze laravel/pint laravel/sail nunomaduro/collision phpunit/phpunit fakerphp/faker --with-all-dependencies
```

2. Reinstalar frontend:

```bash
npm install
```

3. Limpar caches:

```bash
php artisan optimize:clear
```

4. Rodar migrações:

```bash
php artisan migrate
```

5. Rodar testes:

```bash
php artisan test
```

## Observações

- O projeto continua com estrutura de bootstrap do Laravel 10 (`bootstrap/app.php`). Isso é suportado e não exige migração para o novo esqueleto.
- `phpunit.xml` foi ajustado para SQLite em memória durante testes, reduzindo falhas por dependência de MySQL local.
- Se houver conflito de pacote após `composer update`, rodar:

```bash
composer why-not laravel/framework 12.*
```

e ajustar dependências conflitantes.
