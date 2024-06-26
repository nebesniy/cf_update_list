# cf_update_list.php
Скрипт `cf_update_list.php` запрашивает IP-адреса репозитория [C24Be/AS_Network_List](https://github.com/C24Be/AS_Network_List/) и обновляет его в листе Cloudflare.

В файле `blacklists/blacklist.txt` данного репозитория содержится список диапазонов IP-адресов, которые так или иначе принадлежат российским государственным органам. Мы будем его использовать для глобальной блокировки на уровне Cloudflare.

После редактирования скрипта и загрузки на сервер, установите регулярное исполнение через CRON (раз в сутки будет оптимально).

## Предварительные настройки

Зайдите в аккаунт Cloudflare, перейдите в Manage account — Configurations. На вкладке Lists создайте новый лист (кнопка Create list): `Identifier` и `Description` — по желанию, `Type` — IP.

Перейдите в созданный лист (Edit), скопируйте URL текущей страницы в адресной строке. Вы получите URL такого вида: `https://dash.cloudflare.com/ACCOUNT_ID/configurations/lists/LIST_ID` с вашими значениями. Они нам пригодятся для дальнейшей конфигурации.

Далее перейдите в настройки домена Cloudflare, к которым хотите применить правила блокировки по листу. На вкладке Security — WAF создайте новое правило (Create rule). 

`Rule name` — по желанию, `Field` — IP Source Address, `Operator` — is in list, `Value` — выберите созданный лист. `Choose action` — Block. Сохраните правило. Создайте такое же правило для всех доменов, которые хотите защитить.

## Конфигурация

`ACCOUNT_ID` — ID вашего аккаунта Cloudflare. Берём из URL, которую мы получили при создании листа.

`LIST_ID` — ID вашего листа. Берём из URL, которую мы получили при создании листа.

`API_KEY` — ключ API вашего аккаунта Cloudflare. Можно получить в настройках профиля на вкладке API Tokens — Global API Key.

`EMAIL` — адрес электронной почты аккаунта Cloudflare.
