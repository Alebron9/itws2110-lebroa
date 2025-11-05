# Lab 6 - PHP Calculator with OOP

## 1: What does each class does

The `Operation` abstract class defines the template with validation and abstract methods that subclasses implement. Each operation subclass implements `operate()` and `getEquation()` for its specific calculation. When a button is clicked, PHP identifies which operation was pressed, instantiates the corresponding class, and calls `getEquation()` to display the result.

## 2: $_GET

`$_GET` would expose operands in the URL and browser history, while `$_POST` keeps data hidden. `$_POST` is preferable because it's more secure, follows standard form submission practices, and has no length limitations. Both methods work functionally the same, but `$_POST` is the correct choice for form submissions.

## 3: What is better

Instead of multiple buttons with different names, use a single hidden input field named `operation` with values like "add" or "subtract". This simplifies the PHP code to check just `$_POST['operation']` instead of multiple conditionals. Alternatively, give all buttons the same name with different values to eliminate separate POST key checks entirely.