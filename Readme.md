# Firebase Authenticator for PrestaShop

## About

![Firebase logo](https://firebase.google.com/_static/images/firebase/touchicon-180.png)

Handle login on the PrestaShop back-office with Firebase.

### Configuration

There is no configuration page for this module. However, you can save the Firebase API key by using the self-configuration feature of PrestaShop.
Set your key in the file `self_config.yml`, and execute the following command in the root of your shop:

```
app/console prestashop:module configure firebaseauthenticator
```

Your module will be ready to accept login attemps.

### Logins

There are two ways to handle the authentication:

#### Login via email / Password

This module overrides the ajax URL request on the login page, and tries a login on Firebase with the credentials. If they match the employee with the same email, he is grantes access to the shop.

#### Login via API key

You can also make the merchant redirected to his shop by using a specific link. The only information you need is his [custom token][4]. You can send it via GET or POST param as `api_token`, by calling this URL in all cases:
```
http://<shop URL>/<admin folder>/index.php?controller=AdminLogin&module=firebaseauthenticator
```


##### Legacy page -> Orders
```
http://<shop domain>/<Admin folder>/index.php?controller=AdminLogin&module=firebaseauthenticator&redirect=AdminOrders
```

##### Symfony page -> Existing product -> ID 1
```
http://<shop domain>/<Admin folder>/index.php?controller=AdminLogin&module=firebaseauthenticator&redirect=admin_product_form&redirectOptions=id%3D1
```

For the list of Symfony routes, look at the routing*.yml files in `src/PrestaShopBundle/Resources/config/`

## Contributing

PrestaShop modules are open source extensions to the [PrestaShop e-commerce platform][prestashop]. Everyone is welcome and even encouraged to contribute with their own improvements!

Just make sure to follow our [contribution guidelines][contribution-guidelines].

## License

This module is released under the [Academic Free License 3.0][AFL-3.0] 

[prestashop]: https://www.prestashop.com/
[contribution-guidelines]: https://devdocs.prestashop.com/1.7/contribute/contribution-guidelines/project-modules/
[AFL-3.0]: https://opensource.org/licenses/AFL-3.0
