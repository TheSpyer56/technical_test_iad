# :computer: technical_test_iad :office:

## :notebook: Description

This simple REST API allows a basic management of a contact database.
It has been written in PHP with the Symfony 4 framework.
The persistent demo database provided on the repository runs on MySQL. The API can however, in theory, work with another database like PostgreSQL for example.

## :rocket: How to run it ?

You can use ```docker-compose up -d``` command at the root of repository to build and connect everything without doing anything else.
Then, you just need to call route (_i recommand to use **Postman**_) with this format: ```http://localhost:8080/route``` and you are ready to use our awesome API !

## :gear: Routes documentation
|      **Name**      | **Method** |              **Route**             |                                                                                          **Description**                                                                                          |
|:------------------:|:----------:|:----------------------------------:|:-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------:|
| _create_contact_   |    POST    | /contact/create                    | Create a contact from payload and insert it into database                                                                                                                                         |
| _read_contact_     |     GET    | /contact/{id}                      | Read the contact corresponding to {id} into database and return it with JSON format                                                                                                               |
| _update_contact_   |     PUT    | /contact/{id}                      | Update the contact corresponding to {id} into database                                                                                                                                            |
| _delete_contact_   |   DELETE   | /contact/{id}                      | Delete contact corresponding to {id} on database                                                                                                                                                  |
| _search_contact_   |     GET    | /contact/search/{field}/{contains} | This route returns an array of contacts whose values in the {field} column contain the value {contains}                                                                                           |
| _last_             |     GET    | /contact/last/{number}             | This route returns an array containing the last {number} of contacts added to the database. If {number} is not specified, the route returns an array containing only the last registered contact. |
| _read_all_contact_ |     GET    | /contact                           | This route return a JSON array who contain all contact in database                                                                                                                                |
| _/contact/dump_    |   DELETE   | /contact/dump                      | This route dump the database: contact table is fully purge /!\ Warning: Use this route only if you know what you do /!\                                                                           |

