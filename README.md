# PPE3

PPE 3 est un site vitrine d'une boutique en ligne (e-commerce), il a été réalisé à l'aide du Framework Symfony 4.
Le code est responsive.

![image](https://user-images.githubusercontent.com/45235527/96744438-532ee780-13c5-11eb-9cbd-46b79fc5dd3a.png)

# Documentation

Configuration du projet : <a href="https://drive.google.com/file/d/1hEvdeTFaUARG0PUgI91X9p1ww5X0OiR-/view?usp=sharing">Tutoriel</a>

Requirements
============

- laragon : https://laragon.org/
- WAMP pour windows https://www.wampserver.com/ (ou LAMP pour linux/max)
- cmder pour windows https://cmder.net/ (pas besoin pour linux/mac)

Installation
============

`composer install`

`php bin/console doctrine:database:create`

`php bin/console doctrine:schema:update --force`

`php bin/console doctrine:fixtures:load`

`php bin/console server:run`

http://127.0.0.1:8000


# Results

![ppe3-1](https://user-images.githubusercontent.com/45235527/96742800-938d6600-13c3-11eb-998a-874680b5f667.PNG)

![ppe3-2](https://user-images.githubusercontent.com/45235527/96742849-9f792800-13c3-11eb-9648-144ed88a1d33.PNG)
