# Contributing

We welcome contributions from everyone. Below is a technical overview of the project and some guidelines on how to make sure your contribution gets accepted.

## Project layout
The project follows the Symfony 2 [file structure](http://symfony.com/doc/current/quick_tour/the_architecture.html). The logic is split into 3 main bundles:
* **CommonBundle**: Contains generic reusable logic that could theoretically be exported into a separate library. Contents can include time manipulation functions, twig helper functions etc
* **SiteBundle**: This bundle contains the main components of pixelbonus required to create courses, generate QR codes and redeem them. This includes the necessary model classes (entities), controllers and views, as well as resource files such as translations and form types.
* **UserBundle**: This bundle extends [FOSUserBundle](https://github.com/FriendsOfSymfony/FOSUserBundle) and contains the functionality necessary to handle all user related functionality. This includes registration, login, password retrieval etc.

## Deployment
The official way to deploy the project is by using the [relevant puppet module](https://forge.puppetlabs.com/dnna/pixelbonus) from puppet forge.

Note that the puppet deployment is meant to complete successfully on the first application. If puppet apply fails at certain steps, it may cause the application to enter an unstable state that will need to be manually fixed by deleting the created pixelbonus directory and applying again.

## How to contribute
All contributions should be in the form of pull requests. For simple contributions like string changes or minor layout tweaks the pull request is sufficient.
For more complicated changes, we recommend that an issue should be created to accompany the pull request. The issue can be used as a forum for discussion about the proposed changes, while the pull request will be used purely for code review.

Some further steps you can follow to ensure your contribution is accepted are:
* Write [good commit messages](http://tbaggery.com/2008/04/19/a-note-about-git-commit-messages.html)
* Follow the [Symfony 2 coding standards](http://symfony.com/doc/current/contributing/code/standards.html)
