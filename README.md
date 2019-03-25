# Documentation

## Prerequisites

```php
require __DIR__ . DIRECTORY_SEPARATOR . 'vendor/autoload.php';

use Celtak\DogDatabase;
```


## Connect to the database

How to connect to the database?

```php
$database = new DogDatabase([
    'host' => 'localhost:8889',
    'dbname' => 'azurWeb',
    'username' => 'root',
    'password' => 'root'
], true);
```

The second parameter is a boolean that allows to display or not the errors.

## *A table ("users") in a database*
To understand how it works, we will use a table.

Table: users

| first         | name          | old  |
| ------------- | ------------- | ---- |
| Mélanie       | Rodrigues     |  24  |
| Anthony       | Jones         |  40  |
| Audrey        | Jones         |  24  |

## Work on a table

```php
$database->setTable('users');
```

## Create

How to insert new data?

```php
// Insert new data

$database->insert([
    ['first', 'Henrique'],
    ['name', 'Rodrigues'],
    ['old', 33],
]);
```


What is the result?

| id  | first         | name          | old  |
| ----| ------------- | ------------- | ---- |
|  1  | Melanie       | Rodrigues     |  24  |
|  2  | Anthony       | Jones         |  40  |
|  3  | Audrey        | Jones         |  24  |
|  4  | Henrique      | Rodrigues     |  33  |




## Read

### *getAll()*
Read the whole database.

```php
$read = $database->getAll();

// All the contents of the database
dump($read);
```

Use filters (where, order by and limit).

__Example 1__

```php
$read = $database->getAll([
    'where' => ['name', 'Rodrigues'],
    'orderBy' => 'old',
]);

// Just "Melanie Rodrigues" and "Henrique Rodrigues"
dump($read);
```

__Example 2__

```php
$read = $database->getAll([
     'limit' => [1, 3]
]);

// Just "Melanie Rodrigues", "Anthony Jones" and "Audrey Jones"
dump($read);
```


### *getFiels()*
Get some fields.

```php
$read = $database->getFields(['name', 'first']);

// We just get the "name" and "first" fields of only "Anthony Jones" and "Audrey Jones"
dump($read);
```

With filters.

```php
$read = $database->getFields(['name', 'first'], [
    'where' => ['name', 'Jones']
]);

// We just get the "name" and "first" fields from all the database
dump($read);
```


### *getId()*
Get by id.

```php
$read = $database->getId(3);

// We get the data related to id 3, "Audrey Jones"
dump($read);
```

## Update

### *update()*

Using the required "where" filter.

```php
// Modify the first name Henrique by Rik

$database->update(
      [
          ['first', 'Rik']
      ],
      [
          'where' => ['first', 'Henrique'],
      ]
  );
```

### *updateId()*

Using the id.

```php
// Modify the first name by John and the name by Taylor from id 3

$database->updateId(3,
    [
        ['first', 'John'],
        ['name', 'Taylor'],
    ]);
```

## Delete

### *delete()*

Using the required "where" filter.

```php
// Delete all data from id 2

$database->delete([
    'where' => ['id', '2']
]);
```

### *deleteId()*

```php
// Delete all data from id 1

$database->deleteId(1);
```

## Custom query

To read.

```php
dump($database->getCustomizedQuery('SELECT * FROM users WHERE id = "10"'));
```

To write.

```php
$database->customizedExec('INSERT INTO utilisateurs (nom, prenom, age) VALUES ("Henrique", "Rodrigues", "33")');
```


# License

MIT.









