## About Laratask Project

Laratask is a project management system which follows Agile methodology:

-   Issues management [Stories, Tasks, Bugs, Tickets]
-   Isuses Statuses lifecycle [Todo, In progress, Pending, Completed, Verified, Canceled]
-   Project Cycle [completion percentage, Sprints]
-   Users managment
-   User roles [Super admin, Admin, Agent]
-   Dashboard [Online users, Charts, Latest Projects]
-   Notification System and tracker (Broadcasting using pusher js)

## Laratask Setup

-   Clone the repo
-   Go into the repo
-   run below commands in terminal

```sh
composer install
php artisan key:generate
cpy .env.example .env
```

-   Create database
-   Put Database info inside .env file
-   Run the below commands in your terminal

```sh
php artisan migrate
php artisan db:seed
```

-   Create a PUSHER channel on [https://pusher.com/] and update pusher api credentials in .env file

## Admin Roles

| Role        | Email                | Password |
| ----------- | -------------------- | -------- |
| Super Admin | superadmin@admin.com | 12341234 |
| Admin       | admin@admin.com      | 12341234 |
