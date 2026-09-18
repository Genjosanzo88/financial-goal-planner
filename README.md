# Financial Goal Planner

A small financial planning application built with PHP 8.4
and native JavaScript.

It allows a user to define a financial goal, estimate capital
growth and compare several return scenarios.

## Features

- Financial goal projection
- Monthly compound growth
- Required monthly contribution calculation
- Estimated time to reach a financial target
- Conservative, moderate and dynamic scenarios
- Native SVG capital projection chart
- Financial plan persistence
- Recent financial plans
- REST API
- Unit and integration tests

## Stack

- PHP 8.4
- Native JavaScript
- Web Components
- HTML5
- CSS
- PDO
- MySQL 8.4
- Composer
- PHPUnit
- Docker

No PHP or JavaScript framework is used.

## Architecture

The application uses a lightweight layered architecture:

    Presentation
         ↓
    Application
         ↓
       Domain
         ↑
    Infrastructure

### Domain

Contains financial rules and value objects.

It has no dependency on HTTP, PDO, MySQL or JavaScript.

### Application

Coordinates the application's use cases.

### Infrastructure

Implements external concerns such as persistence using PDO
and MySQL.

### Presentation

Contains the HTTP entry point, REST controllers and native
JavaScript UI.

## Run locally

Requirements:

- Docker
- Docker Compose

Start the application:

```bash
docker compose up --build -d