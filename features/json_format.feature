Feature: Serializing Single Object
    In order to save objects for APIs
    As a developer
    I want to serialize the object in the JSON format

    Scenario: Simple properties
        Given a file called "User.php" with:
            """
            class User
            {
                public $name;
                public $age;
            }
            """
        And a file called "config/user.yml" with:
            """
            User:
                properties:
                    name:
                        type: string
                        map: key
                    age:
                        type: number
            """
        And a "User" object with "name = 'John Doe'" and "age = 32"
        When I serialize the object in the "json" format
        Then the result should be:
            """
            {"John Doe":{"age":32}}
            """
