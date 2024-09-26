# symfony-payments
- to install the project pull the repo and run command composer install inside the project folder
- the excute the command **symfony server:start** to run tests use command **php bin/phpunit**
- **payments.postman_collection** included also to test the api endpoints
- what i made is the **APIs endpoints**, **command**, **phpunit tests**
# files i created to look into
  - **src/Controller/API folder**
  - **src/Command/GatewaysCommand**
  - **config/services.yaml** (Modified)
  - **src/Gateways** to create payment interface, factory, payments files
  - **tests/gateways** to create tests you can run the tests using **php bin/phpunit**
  - you can test the command it like this example [**php bin/console Gateways shift4 USD 1000**] -> shift4 or aci
