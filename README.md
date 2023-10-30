# Meerketball Website

## Authors

Cédric CAHUZAC

Robin L'HUILLIER

[Charlotte PARIENTI FABRE](https://gitlab.com/CharlottePF)

[Lorenzo PUCCIO](https://github.com/StOil-L)

Nicolas RUIZ

## Context

This project was carried out during my studies at the University of Montpellier as a second year Computer Science Bachelors Degree student.

It constitutes as the sole assignment for the L2 Programming Project subject (subject code: HLIN405).

Meerketball is a basketball tournament management website. Its frontend is handled with HTML, CSS and JavaScript while its backend uses PHP and a MySQL database, managed with PHPMyAdmin.

## Features

| **Features**                                                 | **No user role / NULL** | **User** | **Manager** | **Administrator** |
|--------------------------------------------------------------|-------------------------|----------|-------------|-------------------|
| Check public tournaments : ongoing, finished, upcoming       |            Y            |     Y    |      Y      |         Y         |
| Display specific tournament tree                             |            Y            |     Y    |      Y      |         Y         |
| Sign up and log in as specific user role                     |            Y            |     Y    |      Y      |         Y         |
| Log out and delete account                                   |            N            |     Y    |      Y      |         Y         |
| Modify user profile information                              |            N            |     Y    |      Y      |         Y         |
| Toggle colorblind stylesheet                                 |            Y            |     Y    |      Y      |         Y         |
| Create and manage tournament                                 |            N            |     N    |      Y      |         Y         |
| Create team                                                  |            N            |     N    |      Y      |         Y         |
| Sign up user / team to tournament                            |            N            |     N    |      Y      |         Y         |
| Create randomized tournament                                 |            N            |     N    |      Y      |         Y         |
| Check involved tournament                                    |            N            |     Y    |      Y      |         Y         |
| Access backend admin page (password hash, database reset...) |            N            |     N    |      N      |         Y         |

## Getting Started

### Prerequisites

Before cloning this project, please ensure to have *AMP software installed onto your device.

Clone this repository in the `www` folder with the method of your choice, then follow these instructions:
- modify the fields used in `CadreStatique/connexion.php` to match those of the account you wish to host the website's database with
- log into PHPMyAdmin using the MySQL server with said account's credentials
- open the Import tab of PHPMyAdmin and select this repository's `ultimate-bdd.sql` file, then click the Run button at the bottom of the tab

### Usage

Upon starting up the *AMP software of your choice, navigate to `localhost/meerketball-website/` in the web browser of your choice. This should automatically redirect you to Meerketball's home page.