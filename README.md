## User Account Management API

Company Z provides essential online services for tens of thousands of users through their
platform ZPlatform.
Company Z is going through stages of growth and adding 1000’s of users daily - while still serving
millions of transactions per month. One of the key features of the platform that need improvement
are:

1. ● Ease the on-boarding process of new users.
2. ● Highly available and performant application to handle 1000’s of requests simultaneously.
3. ● Store and manage user data at orders of magnitude of scale.
4. ● Implement best in class security features.

<img src="https://res.cloudinary.com/diaylgu7a/image/upload/v1698736817/Screenshot_2023-10-31_at_08.20.01_m4kz18.png">

### Installation

#### Install manually

1. Clone this repository : git clone https://github.com/hktom/user-acount-management-irembo.git
2. Execute : cd user-acount-management-irembo
3. Execute : copy .env.example .env **(if you have windows)** or cp .env.example .env **(if you have linux/macos)**
4. Execute: docker-compose up -d --build **(if you have docker installed)**
5. Execute: docker-compose exec app composer install
6. Execute migrations: docker-compose exec app php artisan migrate
7. Execute migrations: docker-compose exec app php artisan key:generate
8. Execute migrations: docker-compose exec app php artisan jwt:secret
9. Execute migrations: docker-compose exec app php artisan db:seed
10. Access the application via http://localhost:8000

#### Already installed 

1. Execute: docker-compose up -d
2. Execute: docker-compose down

#### Install with Makefile

1. Clone this repository : git clone https://github.com/hktom/user-acount-management-irembo.git
2. Execute : cd user-acount-management-irembo
3. make boot **(install dependencies and run migration)**

#### Other commands
1. make up **(start docker)**
2. make down **(stop docker)**

#### For you to send email you need to configure a Sengrid API KEY and Complete your env with the following

1. **SENDGRID_API_KEY="your sengrid API KEY"**
2. **MAILER_NAME = "Your email sender name"**
3. **EMAIL_SENDER="your sendgrid API email "**

#### In seeding we create an admin user, if you want to change his email value you can do it in the .env file

1. **ADMIN_MAIL="your admin mail"**

### Testing

1. Execute: docker-compose exec app php artisan test
