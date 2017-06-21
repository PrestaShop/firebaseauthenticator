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

You can also make the merchant redirected to his shop by using a specific link. The only information you need is his custom token. You can send it via GET or POST param as `api_token`, by calling this URL in all cases:
```
http://<shop URL>/<admin folder>/index.php?controller=AdminLogin&module=firebaseauthenticator
```

## Contributing

PrestaShop modules are open-source extensions to the PrestaShop e-commerce solution. Everyone is welcome and even encouraged to contribute with their own improvements.

### Requirements

Contributors **must** follow the following rules:

* **Make your Pull Request on the "dev" branch**, NOT the "master" branch.
* Do not update the module's version number.
* Follow [the coding standards][1].

### Process in details

Contributors wishing to edit a module's files should follow the following process:

1. Create your GitHub account, if you do not have one already.
2. Fork the ps_socialfollow project to your GitHub account.
3. Clone your fork to your local machine in the ```/modules``` directory of your PrestaShop installation.
4. Create a branch in your local clone of the module for your changes.
5. Change the files in your branch. Be sure to follow [the coding standards][1]!
6. Push your changed branch to your fork in your GitHub account.
7. Create a pull request for your changes **on the _'dev'_ branch** of the module's project. Be sure to follow [the commit message norm][2] in your pull request. If you need help to make a pull request, read the [Github help page about creating pull requests][3].
8. Wait for one of the core developers either to include your change in the codebase, or to comment on possible improvements you should make to your code.

That's it: you have contributed to this open-source project! Congratulations!

[1]: http://doc.prestashop.com/display/PS16/Coding+Standards
[2]: http://doc.prestashop.com/display/PS16/How+to+write+a+commit+message
[3]: https://help.github.com/articles/using-pull-requests
