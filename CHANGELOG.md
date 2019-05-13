# Changelog

All notable changes to `laravel-mvc` will be documented in this file.

## V2.1 Alpha  13-05-2019

- Fix Interface Controller namespace

## V2.1 Alpha  13-05-2019

- Add Controllers and Form Requests to readme
- Make interface for Controller to add Resource methods

## V2.0 Alpha  13-05-2019

- Init Repository, Model and Resource

## V1.9 Alpha  13-05-2019

- Allow more composer versions for Illuminate

## V1.8 Alpha  12-05-2019

- Remove framework packages

## V1.7 Alpha  12-05-2019

- Update laravel skeleton
- Add dev dependencies

## V1.6 Alpha  12-05-2019

- Require laravel skeleton

## V1.5 Alpha  12-05-2019

- Add abstract controller + rest module + make command
- Add Form Requests + make command
- Add separate lumen/laravel logic
- Add Form Request Validation middleware

#### Missing

- validation middleware logic for Laravel
- New test cases
- Updated readme

## V1.4 Alpha  18-04-2019

- Add support for multiple sort queries. 

## V1.3 Alpha  01-04-2019

- Add group by to prevent multiple records with joins 

## V1.2 Alpha  29-03-2019

- Select only table fields in first method
- Add join method to fix already joined conflicts in param methods 

## V1.1 Alpha  29-03-2019

- Fix spelling mistake readme and add first() to readme 

## V1.0 Alpha  29-03-2019

- Make params static and change name, because query is conflicting with other requests

## V0.9 Alpha  28-12-2018

- Fix tests + check for params to exists on model

## V0.8 Alpha  28-12-2018

- Remove where and replace it with params so that you can manage complex querying inside the Repository

## V0.7 Alpha  11-12-2018

- Query all other queries than where on the repository->model because it will get to complex

## V0.6 Alpha  11-12-2018

- Add where, orderBy and select and replace filters 

## V0.5 Alpha  11-12-2018

- Add pagination with filtering and tests 

## V0.4 Alpha  08-11-2018

- Fix Repository command for lumen app_path does not exist

## V0.3 Alpha  08-11-2018

- Fix Repository command
- Change Provider name

## V0.2 Alpha  08-11-2018

- Update readme

## V0.1 Alpha  08-11-2018

- Initial testing release
