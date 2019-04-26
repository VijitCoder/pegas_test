# Pegas. Тест

## Задача

Условия задачи:

На страницу вывести форму с полями input:email, select:категория из <http://www.icndb.com/api/> При заполнении формы на емейл нужно отправить письмо с темой "Случайная шутка из %имя категории%"
В теле письма должна быть случайная шутка из этой категории Эту же шутку нужно записать в файл на диске

Требования:

Работу с API необходимо реализовать самому с использованием <http://docs.guzzlephp.org/en/stable/>
Приложение должно быть на базе Symfony (3 или 4)

Пожелания:

Написание тестов будет плюсом.

Упор делайте не на скорость выполнения, а на качество исполнения. Предоставленное решение мы будем оценивать на соответствие принципам SOLID и чистого кода.

Рекомендуемые книги:

Martin, Robert C. (2009). Clean Code: A Handbook of Agile Software Craftsmanship

Principles of Package Design
Preparing your code for reuse
Matthias Noback

A Year With Symfony
Writing healthy, reusable Symfony2 code
Matthias Noback

## Решение

_Забавляет приведеннный список книг. Конечно упор на качество, а не на скорость. Но врядли работодатель будет **столько** ждать,пока я изучу приведенные книги :) Я читал одну из них - "Чистый код" Мартина, изучение еще не закончил. На том и стою._

Guzzle. Я с этой библиотекой напрямую не работал, только через адаптер движка (Laravel). Для использования этой библиотеки в Symfony есть как минимум два пакета. Использовал один из них, т.к. не смог настроить через конфиги Symfony библиотеку, скачанную отдельно.

## Свалка

Список категорий
GET <http://api.icndb.com/categories>

Случайная шутка в категории
GET <http://api.icndb.com/jokes/random?limitTo=[nerdy]>

---

Удалил `symfony/debug-pack`. Без него заработала функция `dump()` на бэкенде, но отвалилась в представлениях. Вот [отсылка](https://github.com/symfony/symfony/issues/25283#issuecomment-395600944) к проблеме.

Вообще, это набор библиотек, сам по себе этот пакет представляет только composer.json. Некогда разбираться, что с ним не так.

Урок про [debug-pack](https://symfonycasts.com/screencast/symfony/debugging-packs)

---

<http://pegas.loc/>

CSRF Sf <https://symfony.com/doc/current/security/csrf_in_login_form.html#rendering-the-csrf-field>

TODO: 
- валидация формы: все поля обязательны; допустимое мыло; известная категория.
- отправка запроса на случайную шутку 
- мылим шутку
- сохраняем на диск
