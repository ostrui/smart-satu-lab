1. Создаем базу, можно назвать как хочется и подправить в директории config/db, у меня база называется smartsatu (dbname=smartsatu)
2. Запускаем миграции при помощи консольной команды yii migrate, нажимаем y для подтверждения
3. Запросы которые реализованы:

Весь функционал реализован в директории api, было сделана прослойка сервисов для вынесения бизнес логики.

все запросы вида - http://your.site/api/v1/controller/method, ответы все в формате JSON.

- посмотреть все товары

запрос GET

/api/v1/products 
в ответ придет массив дата со всеми товарами

- добавить товар в корзину

запрос POST

/api/v1/products/add-to-cart

передаваемые параметры

id:1 (id товара)
qty:4 (кол-во товара)

в ответ массив вида

{
    "status": "OK",
    "message": "Товар Linovo успешно добавлен в корзину",
    "data": {
        "cart": [
            {
                "name": "MacBook",
                "price": 3000,
                "qty": "2",
                "total": 6000
            },
            {
                "name": "Linovo",
                "price": 200.3,
                "qty": 4,
                "total": 801.2
            }
        ],
        "cart.qty": 6,
        "cart.sum": "6,801.20"
    }
}

- удалить товар из корзины

запрос POST

/api/v1/products/remove-item

передаваемые параметры теже

id:1 (id товара)
qty:4 (кол-во товара)

в ответ массив корзины с уменьшенным количеством и стоимостью

{
    "status": "OK",
    "message": "Товар Linovo в кол-ве 2 удален из корзины",
    "data": {
        "cart": [
            {
                "name": "MacBook",
                "price": 3000,
                "qty": "2",
                "total": 6000
            },
            {
                "name": "Linovo",
                "price": 200.3,
                "qty": 2,
                "total": 400.6
            }
        ],
        "cart.qty": 4,
        "cart.sum": "6,400.60"
    }
}

- очистить корзину

запрос POST

/api/v1/products/clear-cart

в ответ информационное собщение

{
    "status": "OK",
    "message": "Корзина успешно очищена"
}

- сохранение заказа в базу

запрос POST

/api/v1/orders/save-order

в ответ получаем данные по заказу

{
    "status": "OK",
    "message": "Заказ успешно размещен.",
    "data": {
        "order": [
            {
                "name": "MacBook",
                "qty": 2,
                "total": 6000
            },
            {
                "name": "Linovo",
                "qty": 4,
                "total": 801.2
            }
        ]
    }
}

- получение всех заказов

запрос POST

/api/v1/orders/get-orders

в ответ массив

{
    "status": "OK",
    "message": "Список заказов",
    "data": {
        "orders": [
            {
                "products": [
                    {
                        "name": "MacBook",
                        "price": 3000,
                        "qty": 2,
                        "total": 6000
                    },
                    {
                        "name": "Linovo",
                        "price": 200.3,
                        "qty": 4,
                        "total": 801.2
                    }
                ],
                "orderStatus": "Создан",
                "amount": 6801.2
            }
        ]
    }
}

- получение одного конкретного заказа по id

запрос POST

/api/v1/orders/get-order

передаваемый параметр

id: 1

в ответ массив

{
    "status": "OK",
    "message": "Заказ № 1",
    "data": {
        "products": [
            {
                "name": "MacBook",
                "price": 3000,
                "qty": 2,
                "total": 6000
            },
            {
                "name": "Linovo",
                "price": 200.3,
                "qty": 4,
                "total": 801.2
            }
        ],
        "orderStatus": "Создан",
        "amount": 6801.2
    }
}

- изменение статуса заказа

запрос PUT

/api/v1/orders/1

в конце строки передаем id заказа в данном случае 1

передаем параметр

status: обработан

в ответ

{
    "status": true,
    "code": 200,
    "error": "",
    "data": {
        "id": 1,
        "status": "обработан"
    }
}

4. Консольные команды.

- проверка работают ли консольные команды

yii v1/products/index

ответ : Hello SmartSatu

- получение товаров

yii v1/products/get

ответ:

# 0:MacBook
# 1:Linovo
# 2:HP
# 3:Samsung
# 4:LG
# 5:Motorola
# 6:Iphone
# 7:Ipad
# 8:Huawei

- получение заказов

yii v1/orders/get

ответ

Заказ # 1

Статус: Обработан

Детали

 Товар: MacBook
 Цена: 3000
 Количество: 2
 Итого: 6000

 Товар: Linovo
 Цена: 200.3
 Количество: 4
 Итого: 801.2

Сумма: 6801.2










