# Financial Goal Planner

Aplicación sencilla de planificación financiera desarrollada con **PHP 8.4** y **JavaScript nativo**.

El proyecto está planteado como una muestra técnica centrada en fundamentos de ingeniería de software, separación de responsabilidades, lógica de negocio, APIs REST y JavaScript moderno, evitando utilizar frameworks de aplicación.

## Tecnologías

- PHP 8.4
- JavaScript nativo
- HTML / CSS
- Composer
- Apache
- Docker
- Docker Compose
- Git

El proyecto no requiere instalar PHP ni Composer directamente en el sistema operativo.

## Requisitos

Solo es necesario tener instalado:

- Docker Desktop

PHP y Composer se ejecutan dentro del contenedor de la aplicación.

## Instalación

Clonar el repositorio:

```bash
git clone https://github.com/Genjosanzo88/financial-goal-planner.git
```

Entrar en la carpeta del proyecto:

```bash
cd financial-goal-planner
```

Construir y levantar los contenedores:

```bash
docker compose up --build -d
```

Instalar las dependencias de PHP:

```bash
docker compose exec app composer install
```

La aplicación estará disponible en:

```text
http://localhost:8080
```

## Comandos útiles

### Levantar la aplicación

```bash
docker compose up -d
```

### Detener la aplicación

```bash
docker compose down
```

### Reconstruir los contenedores

```bash
docker compose up --build -d
```

### Comprobar el estado de los contenedores

```bash
docker compose ps
```

### Acceder al contenedor de PHP

```bash
docker compose exec app bash
```

### Consultar la versión de PHP

```bash
docker compose exec app php -v
```

### Consultar la versión de Composer

```bash
docker compose exec app composer --version
```

### Regenerar el autoload de Composer

```bash
docker compose exec app composer dump-autoload
```

## Estructura del proyecto

```text
financial-goal-planner/
├── public/
│   └── index.php
├── src/
├── tests/
├── .dockerignore
├── .gitignore
├── compose.yaml
├── composer.json
├── Dockerfile
└── README.md
```

A medida que avance el desarrollo, el código PHP se organizará en distintas capas para separar la lógica de dominio, los casos de uso, la infraestructura y la capa HTTP.

## Arquitectura

El proyecto seguirá una arquitectura limpia ligera, evitando introducir complejidad que no sea necesaria para el alcance de la aplicación.

La estructura general será:

```text
Presentación
     ↓
Aplicación
     ↓
Dominio
     ↑
Infraestructura
```

El objetivo principal es mantener la lógica de negocio independiente de detalles como HTTP, base de datos o interfaz de usuario.

## Principios de desarrollo

El proyecto intenta mantener una implementación sencilla siguiendo algunos principios básicos:

- La lógica de negocio debe ser independiente de HTTP y de la persistencia.
- Las dependencias deben apuntar hacia el dominio.
- El código debe ser sencillo de probar.
- Se priorizan las capacidades nativas del lenguaje frente a dependencias innecesarias.
- Los patrones de diseño se utilizarán únicamente cuando resuelvan un problema real.
- Los comentarios deben explicar decisiones o intención, no repetir lo que ya expresa el código.

## Autoload de Composer

El proyecto utiliza autoload PSR-4:

```json
"autoload": {
    "psr-4": {
        "App\\": "src/"
    }
}
```

Por ejemplo:

```php
namespace App\Domain\FinancialPlan;
```

corresponde con:

```text
src/Domain/FinancialPlan/
```

## Estado actual

Actualmente el proyecto contiene la configuración inicial del entorno de desarrollo mediante Docker, PHP 8.4 y Composer.

La lógica de planificación financiera, la API y la interfaz web se implementarán de forma incremental.

## Licencia

MIT